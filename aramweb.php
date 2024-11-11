<?php
/*
Plugin Name: Aram Web Wordpress Customizer
Description: Change the logo in the WordPress login page.
Version: 1.0
Author: Morteza Hatami  
Author URI: https://aramweb.de/
*/
// Prevent direct access to the file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// Plugin activation and deactivation hooks
register_activation_hook(__FILE__, 'wplogin_logo_changer_activate');
register_deactivation_hook(__FILE__, 'wplogin_logo_changer_deactivate');

function wplogin_logo_changer_activate() {
    // Activation tasks (if any)
}

function wplogin_logo_changer_deactivate() {
    // Deactivation tasks (if any)
}
function wplogin_logo_changer_custom_login_logo() {
    ?>
    <style type="text/css">
        .login h1 a {
            background-image: url(<?php echo plugins_url('aram-logo.png', __FILE__); ?>);
            height: 100px; /* Adjust the height as needed */
            width: 100px; /* Adjust the width as needed */
            background-size: contain;
            background-repeat: no-repeat;
            /*padding-bottom: 10px; /* Adjust the logo position as needed */
        }
    </style>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var loginLogoLink = document.querySelector('.login h1 a');
            loginLogoLink.setAttribute('href', 'http://aramweb.de/'); // Set the logo link to http://aramweb.ir/
        });
    </script>
    <?php
}
add_action('login_head', 'wplogin_logo_changer_custom_login_logo');


// Enqueue scripts and styles
add_action( 'admin_enqueue_scripts', 'woo_quantity_control_enqueue_admin_scripts' );
function woo_quantity_control_enqueue_admin_scripts() {
    wp_enqueue_script( 'woo-quantity-control-admin', plugin_dir_url( __FILE__ ) . 'aramweb-backend.js', array( 'jquery' ), '1.0', true );
}

// Add a new tab for quantity controls in the product data meta box
add_filter( 'woocommerce_product_data_tabs', 'woo_quantity_control_product_data_tab' );
function woo_quantity_control_product_data_tab( $tabs ) {
    $tabs['quantity_control'] = array(
        'label'    => __( 'Quantity Control', 'woo-quantity-control' ),
        'target'   => 'quantity_control_data',
        'class'    => array( 'show_if_simple' ),
        'priority' => 21,
    );
    return $tabs;
}

// Add quantity fields to the new tab
add_action( 'woocommerce_product_data_panels', 'woo_quantity_control_product_data_fields' );
function woo_quantity_control_product_data_fields() {
    global $post;
    ?>
    <div id="quantity_control_data" class="panel woocommerce_options_panel">
        <div class="options_group">
            <?php
            woocommerce_wp_text_input( array(
                'id'          => '_min_quantity',
                'label'       => __( 'Minimum Quantity', 'woo-quantity-control' ),
                'type'        => 'number',
                'description' => __( 'Set a minimum quantity for the product.', 'woo-quantity-control' ),
                'desc_tip'    => true,
                'custom_attributes' => array(
                    'min' => '0',
                    'step' => '1',
                ),
            ));
            woocommerce_wp_text_input( array(
                'id'          => '_max_quantity',
                'label'       => __( 'Maximum Quantity', 'woo-quantity-control' ),
                'type'        => 'number',
                'description' => __( 'Set a maximum quantity for the product.', 'woo-quantity-control' ),
                'desc_tip'    => true,
                'custom_attributes' => array(
                    'min' => '0',
                    'step' => '1',
                ),
            ));
            woocommerce_wp_text_input( array(
                'id'          => '_step_quantity',
                'label'       => __( 'Quantity Step', 'woo-quantity-control' ),
                'type'        => 'number',
                'description' => __( 'Set a step value for quantity increments.', 'woo-quantity-control' ),
                'desc_tip'    => true,
                'custom_attributes' => array(
                    'min' => '1',
                    'step' => '1',
                ),
            ));
            ?>
        </div>
    </div>
    <?php
}

// Save quantity control fields
add_action( 'woocommerce_process_product_meta', 'woo_quantity_control_save_fields' );
function woo_quantity_control_save_fields( $post_id ) {
    $min_quantity = isset( $_POST['_min_quantity'] ) ? sanitize_text_field( $_POST['_min_quantity'] ) : '';
    $max_quantity = isset( $_POST['_max_quantity'] ) ? sanitize_text_field( $_POST['_max_quantity'] ) : '';
    $step_quantity = isset( $_POST['_step_quantity'] ) ? sanitize_text_field( $_POST['_step_quantity'] ) : '';

    update_post_meta( $post_id, '_min_quantity', $min_quantity );
    update_post_meta( $post_id, '_max_quantity', $max_quantity );
    update_post_meta( $post_id, '_step_quantity', $step_quantity );
}

add_action( 'woocommerce_before_single_product', 'woo_quantity_control_enqueue_scripts' );
function woo_quantity_control_enqueue_scripts() {
    global $product;

    if ( is_a( $product, 'WC_Product' ) ) {  // Check if $product is a valid WC_Product object
        wp_enqueue_script( 'woo-quantity-control', plugin_dir_url( __FILE__ ) . 'aramweb-frontend.js', array( 'jquery' ), '1.0', true );

        // Ensure the product metadata is retrieved and passed to the script
        wp_localize_script( 'woo-quantity-control', 'woo_quantity_control_params', array(
            'min' => get_post_meta( $product->get_id(), '_min_quantity', true ),
            'max' => get_post_meta( $product->get_id(), '_max_quantity', true ),
            'step' => get_post_meta( $product->get_id(), '_step_quantity', true ),
        ));
    }
}
