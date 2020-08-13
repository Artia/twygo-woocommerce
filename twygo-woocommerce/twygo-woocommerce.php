<?php
/**
* Plugin Name: Twygo WooCommerce
* Plugin URI: https://artia.github.io/twygo-woocommerce
* Description: Integração entre Twygo e WooCommerce.
* Version: 1.0
* Author: Henrique Schmeller
* Author URI: https://github.com/henriquesml
**/

require_once('services/ApiService.php');
require_once('services/HTMLService.php');
require_once('services/TestButtonService.php');

add_action( 'admin_menu', 'twygo_woocommerce_menu' );
function twygo_woocommerce_menu() {
  add_menu_page(
    __( 'Twygo WooCommerce' ),
    __( 'Twygo WooCommerce' ),
    'manage_options',
    'config-page',
    'page_content',
    'dashicons-desktop',
    56
  );
}

add_action( 'admin_init', 'settings_init' );
function settings_init() {

  add_settings_section(
    'twygo_woocommerce_config',
    __('Configurações Obrigatórias' ),
    'paragraph',
    'config-page'
  );

  add_settings_field(
    'email_twygo',
    __('E-mail twygo (ADMIN) *'),
    'email_twygo',
    'config-page',
    'twygo_woocommerce_config'
  );

  add_settings_field(
    'password_twygo',
    __('Senha twygo *'),
    'password_twygo',
    'config-page',
    'twygo_woocommerce_config'
  );

  add_settings_field(
    'organization_id_twygo',
    __('ID da sua organização twygo *'),
    'organization_id_twygo',
    'config-page',
    'twygo_woocommerce_config'
  );

  add_settings_field(
    'tag_twygo',
    __('Tag para criação do curso no twygo *'),
    'tag_twygo',
    'config-page',
    'twygo_woocommerce_config'
  );

  add_settings_field(
    'url_twygo',
    __('URL Twygo'),
    'url_twygo',
    'config-page',
    'twygo_woocommerce_config'
  );

  register_setting( 'config-page', 'email_twygo' );
  register_setting( 'config-page', 'password_twygo' );
  register_setting( 'config-page', 'organization_id_twygo' );
  register_setting( 'config-page', 'tag_twygo' );
  register_setting( 'config-page', 'url_twygo' );
}

add_action( 'save_post', 'send_to_twygo', 10, 1 );

function send_to_twygo( $post_ID ) {
  $post_type = get_post_type( $post_ID );
  if ( $post_type == 'shop_order' ) {
    $order = wc_get_order( $post_ID );
    if ( $order->status == "completed" ) {
      $items = $order->get_items();
      $external_ids = [];
      foreach ( $items as $item ) {
        $product_id = $item->get_product_id();
        array_push($external_ids, $product_id);
      }
      $data = array(
        'first_name' => $order->billing_first_name,
        'last_name' => $order->billing_last_name,
        'email' => $order->billing_email,
        'enterprise' => empty($order->billing_company) ? 'WooCommerce' : $order->billing_company,
        'events_external_ids' => $external_ids
      );
      $url = get_option( 'url_twygo' ) == false ? 'https://www.twygoead.com' : get_option( 'url_twygo' );
      $token = getToken($data, get_option( 'email_twygo' ), get_option( 'password_twygo' ), $url);
      send_student($token, $data, get_option( 'organization_id_twygo' ), $url);
    }
  } elseif ( $post_type == 'product' ) {
      $product = wc_get_product( $post_ID );
      $terms = get_the_terms( $post_ID, 'product_tag' );
      $tag = [];
      foreach ( $terms as $term ) {
        array_push($tag, $term->name);
      }
      if (in_array(get_option( 'tag_twygo' ), $tag)) {
        $url = get_option( 'url_twygo' ) == false ? 'https://www.twygoead.com' : get_option( 'url_twygo' );
        $data = array('external_id' => $product->id, 'name' => $product->name, 'description' => $product->description, 'price' => $product->price, 'situation' => $product->status == "publish");
        $token = getToken($data, get_option( 'email_twygo' ), get_option( 'password_twygo' ), $url);
        send_course($token, $data, get_option( 'organization_id_twygo' ), $url);
      }
    }
}

?>
