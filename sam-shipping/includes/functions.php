<?php
function uikar_add_shipping_method()
{
    if (!class_exists('WC_uikar_Shipping_Method')) {
        class WC_uikar_Shipping_Method extends WC_Shipping_Method
        {
            public function __construct()
            {
                $this->id                 = 'flat_rate';
                $this->method_title       = __('Flat rate');
                $this->method_description = __('Flat rate for shipping method');
                $this->enabled            = "yes";
                $this->title              = "Sam Flat rate";
                $this->tax_status = 'taxable';
                $this->init();
                
            }
            function init_form_fields() {

                $this->form_fields = array(

                    'carrierID' => array(
                        'title'       => __( 'Carrier ID'),
                        'type'        => 'text',
                        'description' => __( 'Please enter carrier id.' ),
                        'default'     => ''
                    ),

                    'title' => array(
                        'title'       => __( 'Title' ),
                        'type'        => 'text',
                        'description' => __( 'Please enter your title' ),
                        'default'     => ''
                    ),

                );

            }
            function init()
            {
                $this->init_form_fields();
                $this->init_settings();
                add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
            }
            
            public function calculate_shipping($package = array())
            {
                $rate = array(
                    'label' => $this->title,
                    'cost' => '10.99',
                    'calc_tax' => 'per_item',
                );
                $this->add_rate($rate);
            }
        }
        
        
    }

    if (!class_exists('WC_uikar_Shipping_Method_freeShipping')) {
        class WC_uikar_Shipping_Method_freeShipping extends WC_Shipping_Method
        {
            public function __construct()
            {
                $this->id                 = 'free_shipping';
                $this->method_title       = __('Free Shipping');
                $this->method_description = __('Free Shipping for shipping method');
                $this->enabled            = "yes";
                $this->title              = "Sam Free Shipping";
                $this->tax_status = 'taxable';
                $this->init();
                
            }
            function init_form_fields() {

                $this->form_fields = array(

                    'carrierID' => array(
                        'title'       => __( 'Carrier ID'),
                        'type'        => 'text',
                        'description' => __( 'Please enter carrier id.' ),
                        'default'     => ''
                    ),

                    'title' => array(
                        'title'       => __( 'Title' ),
                        'type'        => 'text',
                        'description' => __( 'Please enter your title' ),
                        'default'     => ''
                    ),

                    'freeshipping' => array(
                        'title'       => __( 'Free Shipping Requires' ),
                        'type'        => 'checkbox',
                        'description' => __( '' ),
                        'default'     => ''
                    ),

                );

            }
            function init()
            {
                $this->init_form_fields();
                $this->init_settings();
                add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
            }
            
            public function calculate_shipping($package = array())
            {
                $rate = array(
                    'label' => $this->title,
                    'cost' => '10.99',
                    'calc_tax' => 'per_item',
                );
                $this->add_rate($rate);
            }
        }
        
        
    }
}

add_action('woocommerce_shipping_init', 'uikar_add_shipping_method');


function uikar_shipping_method($methods)
{
    $methods['uikar_shipping_method'] = 'WC_uikar_Shipping_Method';
    $methods['uikar_shipping_method_freeshipping'] = 'WC_uikar_Shipping_Method_freeShipping';
    return $methods;
}

add_filter('woocommerce_shipping_methods', 'uikar_shipping_method');




add_action( 'woocommerce_after_order_notes', 'carrier_id_checkout' );

function carrier_id_checkout( $checkout ) {

    echo '<div id="carrier_id_checkout"><h2>' . __('Carrier ID') . '</h2>';

    woocommerce_form_field( '_carrier_id', array(
        'type'          => 'text',
        'class'         => array('my-field-class form-row-wide'),
        'label'         => __('Carrier ID'),
        'placeholder'   => __('Enter Carrier ID'),
        ), $checkout->get_value( '_carrier_id' ));

    echo '</div>';

}

add_action('woocommerce_checkout_process', 'uikar_checkout_field_process');
function uikar_checkout_field_process() {
    if ( ! $_POST['_carrier_id'] )
        wc_add_notice( __( 'Please enter something into this new shiny field.' ), 'error' );
}

add_action( 'woocommerce_checkout_update_order_meta', 'uikar_checkout_update_order_meta' );

function uikar_checkout_update_order_meta( $order_id ) {
    $order = new WC_Order($order_id); 
    $order_data = $order->get_data();
    if ( (!empty( $_POST['_carrier_id'])))  {
        update_post_meta( $order_id, '_carrier_id', sanitize_text_field( $_POST['_carrier_id'] ) );
    }
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'uikar_admin_order_meta', 10, 1 );

function uikar_admin_order_meta($order){
    echo '<p><strong>'.__('Carrier ID').':</strong> ' . get_post_meta( $order->id, '_carrier_id', true ) . '</p>';
}

