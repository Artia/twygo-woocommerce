<?php
/**
* Plugin Name: Twygo WooCommerce
* Plugin URI: https://artia.github.io/twygo-woocommerce
* Description: Integração entre Twygo e WooCommerce.
* Version: 1.0
* Author: Henrique Schmeller
* Author URI: https://github.com/henriquesml
**/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once('services/HTMLService.php');
require_once('services/TestButtonService.php');
require_once('services/ApiService.php');

class Twygo_WooCommerce {

    public function __construct() {
        add_action( 'woocommerce_order_status_completed', [$this, 'handle_order'], 10, 1 );
        add_action( 'save_post_product', [$this, 'handle_product'], 10, 3 );
        $this->initialize_settings();
    }

    public function handle_order( $order_id ) {
        $order = wc_get_order( $order_id );
        if ( ! $order ) return;

        $status = $order->get_status();
        if ( $status === 'completed' ) {
            $this->send_order_to_twygo( $order );
        }
    }

    public function handle_product( $post_id, $post, $update ) {
        $product = wc_get_product( $post_id );
        $terms = get_the_terms( $post_id, 'product_tag' );
        $tag = [];

        if ( is_array( $terms ) ) {
            foreach ( $terms as $term ) {
                $tag[] = $term->name;
            }
        }

        if ( in_array( get_option( 'tag_twygo' ), $tag ) ) {
            $this->send_product_to_twygo( $product );
        }
    }

    private function send_order_to_twygo( $order ) {
      $external_ids = [];
      $items = $order->get_items();
      foreach ( $items as $item ) {
        $product_id = $item->get_product_id();
        array_push($external_ids, $product_id);
      }
  
      $data = array(
          'first_name' => $order->billing_first_name,
          'last_name' => $order->billing_last_name,
          'email' => $order->billing_email,
          'enterprise' => empty($order->billing_company) ? 'WooCommerce' : $order->billing_company,
          'events_external_ids' => $external_ids,
          'city' => empty($order->billing_city) ? 'WooCommerce' : $order->billing_city,
          'state' => empty($order->billing_state) ? 'WooCommerce' : $order->billing_state,
          'country' => empty($order->billing_country) ? 'Brasil' : $order->billing_country,
          'phone1' => empty($order->billing_phone) ? '(99) 9999-9999' : $order->billing_phone,
          'phone2' => empty($order->billing_phone) ? '(99) 9999-9999' : $order->billing_phone,
          'address' => empty($order->billing_address_1) ? 'WooCommerce' : $order->billing_address_1,
          'address2' => empty($order->billing_address_2) ? 'WooCommerce' : $order->billing_address_2
      );
  
      $url = get_option( 'url_twygo', 'https://www.twygoead.com' );
      $email = get_option( 'email_twygo' );
      $password = get_option( 'password_twygo' );
  
      $token = getToken($data, $email, $password, $url);
  
      if (!$token) {
          error_log('Erro ao obter o token');
          return;
      }
  
      send_student($token, $data, get_option( 'organization_id_twygo' ), $url);
  }

    private function send_product_to_twygo( $product ) {
      $data = array(
        'external_id' => $product->get_id(),
        'name' => $product->get_name(),
        'description' => preg_replace('/\r\n\r\n/', '<br />', $product->get_description()),
        'price' => $product->get_price(),
        'situation' => $product->get_status() == "publish"
      );
      
      $url = get_option( 'url_twygo', 'https://www.twygoead.com' );
      $email = get_option( 'email_twygo' );
      $password = get_option( 'password_twygo' );
      $token = getToken($data, $email, $password, $url);
      send_course($token, $data, get_option( 'organization_id_twygo' ), $url);
    }

    private function initialize_settings() {
        add_action( 'admin_menu', [$this, 'twygo_woocommerce_menu'] );
        add_action( 'admin_init', [$this, 'settings_init'] );
    }

    public function twygo_woocommerce_menu() {
        add_menu_page(
            __( 'Twygo WooCommerce' ),
            __( 'Twygo WooCommerce' ),
            'manage_options',
            'config-page',
            'page_content'      ,
            'dashicons-desktop',
            56
        );
    }

    public function settings_init() {
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
}

new Twygo_WooCommerce();