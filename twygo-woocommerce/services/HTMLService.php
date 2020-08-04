<?php
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

  function paragraph() {
      echo "
        <p>Adicione os campos abaixo corretamente, para que a integração com o TWYGO seja efetuada com sucesso.</p>
        <div
          id='msg'
          style='display: none;
            width: 440px;
            height: 40px;
            border-radius: 4px;
            display: flex;
            justify-content: center;
            align-items: center;'
        >

        </div>
      ";
  }

  function email_twygo() {
      ?>
      <input
        type="email"
        id="email_twygo"
        name="email_twygo"
        value="<?php echo get_option( 'email_twygo' ); ?>"
        placeholder="E-mail da sua conta do Twygo"
      >
      <?php
  }

  function password_twygo() {
      ?>
      <input
        type="password"
        id="password_twygo"
        name="password_twygo"
        value="<?php echo get_option( 'password_twygo' ); ?>"
        placeholder="Senha da sua conta do Twygo"
      >
      <?php
  }

  function organization_id_twygo() {
      ?>
      <input
        type="number"
        id="organization_id_twygo"
        name="organization_id_twygo"
        value="<?php echo get_option( 'organization_id_twygo' ); ?>"
        placeholder="EX: 1"
      >
      <?php
  }

  function tag_twygo() {
      ?>
      <input
        type="text"
        id="tag_twygo"
        name="tag_twygo"
        value="<?php echo get_option( 'tag_twygo' ); ?>"
        placeholder="Ex: published"
      >
      <?php
  }

  function url_twygo() {
      ?>
      <input
        type="text"
        id="url_twygo"
        name="url_twygo"
        value="<?php echo get_option( 'url_twygo' ); ?>"
        placeholder="Ex: https://www.twygoead.com"
      >
      <?php
  }


?>
