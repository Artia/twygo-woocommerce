<? php
/ **
* Nome do plug-in: Twygo WooCommerce
* URI do plug-in: https://www.yourwebsiteurl.com/
* Descrição: Integração entre Twygo e WooCommerce.
* Versão: 1.0
* Autor: Henrique Schmeller
* URI do autor: https://github.com/henriquesml
** /

add_action('save_post_product', 'send_to_twygo_on_product_save', 10, 3);
function send_to_twygo_on_product_save( $post_id, $post, $update ) {
    $product = wc_get_product( $post_id );
    header('Location: https://google.com');
}
?>
