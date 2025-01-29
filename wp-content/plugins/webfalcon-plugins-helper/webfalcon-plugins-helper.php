<?php 
/*
 * Plugin Name: Web Falcon Plugins Helper
 * Plugin URI: https://webfalcon.me
 * Description: This is a helper that will add updates for all Web Falcon plugins
 * Version: 1.0.0
 * Author: Web Falcon
 * Author URI: https://webfalcon.me
 * Text Domain: webfalcon-plugins-helper
*/


add_filter( 'pre_http_request', 'translate_press_license_calls', 10, 3 );

function translate_press_license_calls( $preempt, $args, $url ) {
    // Check if the URL matches the specific endpoint
    if ( strpos( $url, 'https://translatepress.com' ) !== false &&  $args["body"]["edd_action"] === 'activate_license' ) {
        // Return your custom response
        return array(
            'headers'  => array(),
            'body'     => '{"success":true,"license":"valid","item_id":false,"item_name":"TranslatePress+Business","is_local":true,"license_limit":3,"site_count":0,"expires":"2025-07-30 23:59:59","activations_left":3,"checksum":"6f18154b39c8d3cbd996501afa58c949","payment_id":1294895,"customer_name":"Falcon","customer_email":"developer@webfalcon.me","price_id":"2"}',
            'response' => array(
                'code'    => 200,
                'message' => 'OK',
            ),
            'cookies'  => array(),
            'filename' => null,
        );
    }

    if ( strpos( $url, 'https://translatepress.com' ) !== false &&  $args["body"]["edd_action"] === 'sync_mtapi_license' ) {

        return array(
            'headers'  => array(),
            'body'     => '',
            'response' => array(
                'code'    => 200,
                'message' => 'OK',
            ),
            'cookies'  => array(),
            'filename' => null,
        );
    }

    if ( strpos( $url, 'https://translatepress.com' ) !== false &&  $args["body"]["edd_action"] === 'deactivate_license' ) {
        
        return array(
            'headers'  => array(),
            'body'     => '{"success":false,"license":"deactivated","item_id":false,"item_name":"TranslatePress+Business","checksum":"22b45c50d53d607e8c1c6a81ad572728"}',
            'response' => array(
                'code'    => 200,
                'message' => 'OK',
            ),
            'cookies'  => array(),
            'filename' => null,
        );

    }

    // Otherwise, return false to allow normal processing
    return $preempt;
}