<?php
/**
* Plugin Name: Twygo WooCommerce
* Plugin URI: https://github.com/Artia/Twygo-WooCommerce
* Description: Integração entre Twygo e WooCommerce.
* Version: 1.0
* Author: Henrique Schmeller
* Author URI: https://github.com/henriquesml
**/

// Settings Page
function my_admin_menu() {
    add_menu_page(
        __( 'Twygo WooCommerce' ),
        __( 'Twygo WooCommerce' ),
        'manage_options',
        'config-page',
        'page_content',
        'dashicons-schedule',
        3
    );
}
add_action( 'admin_menu', 'my_admin_menu' );

function page_content() {
    ?>
    <h1> <?php esc_html_e( 'Twygo WooCommerce.' ); ?> </h1>
    <form method="POST" action="options.php">
    <?php
    settings_fields( 'config-page' );
    do_settings_sections( 'config-page' );
    test_button();
    submit_button();
    ?>
    </form>
    <?php
}

add_action( 'admin_init', 'settings_init' );

function settings_init() {

    add_settings_section(
        'twygo_woocommerce_config',
        __( 'Configurações Obrigatórias' ),
        'paragraph',
        'config-page'
    );

		add_settings_field(
		   'user_fields',
		   __( 'Suas informações'),
		   'user_fields',
		   'config-page',
		   'twygo_woocommerce_config'
		);

    add_settings_field(
       'organization_fields',
       __( 'Informações da Organização'),
       'organization_fields',
       'config-page',
       'twygo_woocommerce_config'
    );

    add_settings_field(
       'tag_field',
       __( 'Tag para inscrição no curso, após pedido finalizado.'),
       'tag_field',
       'config-page',
       'twygo_woocommerce_config'
    );

		register_setting( 'config-page', 'user_fields' );
    register_setting( 'config-page', 'organization_fields' );
    register_setting( 'config-page', 'tag_field' );
}


function paragraph() {
    echo '<p>Adicione os campos abaixo para que a integração funcione corretamente.</p>';
}

function test_button() {
    echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
      <script>
        function test_infos() {
          const email = document.getElementById('email_twygo').value;
          const password = document.getElementById('password_twygo').value;

          $.ajax({
              url: 'http://172.19.0.1:3000/oauth/token',
              dataType: 'json',
              type: 'POST',
              data: {
                grant_type: 'password',
                username: email,
                password: password
              },
              success: function(data, status){
                alert('Sucesso.');
              },
              error: function(err){
                alert('Informações inválidas, tente novamente.');
              }
          });
        }
      </script>
      <button
        style='background-color: #00c617;
        height: 30px;
        border-radius: 5px;
        border: 1px solid #00c617;
        cursor: pointer;
        color: #fff;
        font-size: 12px;'
        type='button'
        onClick='test_infos();'
      >
        Testar informações
      </button>";
}

function user_fields() {
    ?>
    <label for="email_twygo"><?php _e( 'E-mail twygo *' ); ?></label>
    <input
      type="email"
      id="email_twygo"
      name="email_twygo"
      value="<?php echo get_option( 'email_twygo' ); ?>"
      placeholder="E-mail da sua conta do Twygo"
    >

    <label for="password_twygo"><?php _e( 'Senha twygo *' ); ?></label>
    <input
      type="password"
      id="password_twygo"
      name="password_twygo"
      value="<?php echo get_option( 'password_twygo' ); ?>"
      placeholder="Senha da sua conta do Twygo"
    >
    <?php
}

function organization_fields() {
    ?>
    <label for="organization_id_twygo"><?php _e( 'ID organização twygo *' ); ?></label>
    <input
      type="number"
      id="organization_id_twygo"
      name="organization_id_twygo"
      value="<?php echo get_option( 'organization_id_twygo' ); ?>"
      placeholder="Sua organização do Twygo"
    >
    <?php
}

function tag_field() {
    ?>
    <label for="tag_twygo"><?php _e( 'Tag' ); ?></label>
    <input
      type="text"
      id="tag_twygo"
      name="tag_twygo"
      value="<?php echo get_option( 'tag_twygo' ); ?>"
      placeholder="Ex: published"
    >
    <?php
}

// hooks -----------------------------------------------------------------------------------------------
add_action( 'added_post_meta', 'send_to_twygo', 10, 4 );
add_action( 'updated_post_meta', 'send_to_twygo', 10, 4 );

function send_to_twygo( $meta_id, $post_id, $meta_key, $meta_value ) {
  // $teste = get_option( 'organization_id_twygo' );
  // var_dump($teste);
  // exit();
  $post_type = get_post_type( $post_id );
  if ( $post_type == 'shop_order' ) {
    $order = wc_get_order( $post_id );
    if ( $order->status == "completed" ) {
      $items = $order->get_items();
      $external_ids = [];
      foreach ( $items as $item ) {
        $product_id = $item->get_product_id();
        $terms = get_the_terms( $product_id, 'product_tag' );
        foreach ($terms as $term) {
          if ( $term->name == "published" ) {
            array_push($external_ids, $product_id);
          }
        }
      }
      $data = array(
        'first_name' => $order->billing_first_name,
        'last_name' => $order->billing_last_name,
        'email' => $order->billing_email,
        'enterprise' => empty($order->billing_company) ? 'WooCommerce' : $order->billing_company,
        'external_ids' => $external_ids
      );
      $token = getToken($data);
      send_student($token, $data);
    }
  } elseif ( $post_type == 'product' ) {
      $product = wc_get_product( $post_id );
      $terms = get_the_terms( $post_id, 'product_tag' );
      $tag = [];
      foreach ( $terms as $term ) {
        array_push($tag, $term->name);
      }
      $data = array('external_id' => $product->id, 'name' => $product->name, 'description' => $product->description, 'situation' => in_array("published", $tag));
      if ($product->status == "publish") {
        $token = getToken($data);
        send_course($token, $data);
      }
    }
}

function getToken($data) {
  $response = wp_remote_post('http://172.19.0.1:3000/oauth/token', array(
    'method'      => 'POST',
    'timeout'     => 45,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking'    => true,
    'headers'     => array(),
    'body'        => array(
      'grant_type' => 'password',
      'username' => 'henrique.schmeller@euax.com.br',
      'password' => '123456'
    ),
    )
  );
  $response_decoded = json_decode($response['body']);
  return $response_decoded->access_token;
}

function send_student($token, $data) {
  $response = wp_remote_post('http://172.19.0.1:3000/api/v1/o/2/students', array(
    'method'      => 'POST',
    'timeout'     => 45,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking'    => true,
    'headers'     => array(
       'Authorization' => 'Bearer ' . $token,
     ),
    'body'        => $data,
    )
  );
}

function send_course($token, $data) {
  $response = wp_remote_post('http://172.19.0.1:3000/api/v1/o/2/courses', array(
    'method'      => 'POST',
    'timeout'     => 45,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking'    => true,
    'headers'     => array(
       'Authorization' => 'Bearer ' . $token,
     ),
    'body'        => $data,
    )
  );
}

?>
