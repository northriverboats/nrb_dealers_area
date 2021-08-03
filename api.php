<?php

if ( ! defined( 'ABSPATH' ) ) wp_die( 'restricted access' );

require __DIR__ . '/vendor/autoload.php';

use Analog\Logger;

$loggerDA = new Analog;
$debug = array_change_key_case(getallheaders(), CASE_LOWER)['x-debug'] == 'true';
$debug_console = True;

if ($debug & $debug_console) {
  $loggerDA->handler (Analog\Handler\ChromeLogger::init ());
} else {
  $log_file = dirname(__FILE__).'/tmp/log.txt';
  $loggerDA->Handler (Analog\Handler\File::init ($log_file));
}

add_action( 'rest_api_init', 'nrb_dealers_area_register_routes' );

/*
 * WP_REST_Server::READABLE   = ‘GET’
 * WP_REST_Server::EDITABLE   = ‘POST, PUT, PATCH’
 * WP_REST_Server::DELETABLE  = ‘DELETE’
 * WP_REST_Server::ALLMETHODS = ‘GET, POST, PUT, PATCH, DELETE’
 *
 * https://code.tutsplus.com/tutorials/wp-rest-api-creating-updating-and-deleting-data--cms-24883
 * https://generatewp.com/snippet/pnkkpve/
 * https://webkul.com/blog/add-custom-rest-api-route-wordpress/
 * https://developer.wordpress.org/reference/functions/register_rest_route/
 *
 */


/**
 * Register the /wp-json/nrb_dealers_area/foo route
 *
 *    GET    nrb_dealers_area/foo
 *    GET    nrb_dealers_area/bar
 *    GET    nrb_dealers_area/bar/(?P<id>[\s\S]+)
 *
 * USERS
 *    GET    nrb_dealers_area/userinfo
 *
 * DEALERS
 *    GET    nrb_dealers_area/dealerships
 *    GET    nrb_dealers_area/dealers
 *    GET    nrb_dealers_area/dealers/(?P<uid>[\s\S]+)
 *    POST   nrb_dealers_area/dealers
 *    POST   nrb_dealers_area/dealers/(?P<uid>[\s\S]+)
 *    GET    nrb_dealers_area/rates
 *
 * HULLS
 *    GET    nrb_dealers_area/drihulls
 *    GET    nrb_dealers_area/oprhulls
 *    GET    nrb_dealers_area/hulls
 *    GET    nrb_dealers_area/hulls/(?P<hull>[\s\S]+)
 *
 * DRIs
 *    GET    nrb_dealers_area/dri/pdf/(?P<uid>[\s\S]+)
 *    GET    nrb_dealers_area/dri/(?P<uid>[\s\S]+)
 *    GET    nrb_dealers_area/dri
 *    POST   nrb_dealers_area/dri
 *    DELETE nrb_dealers_area/dri
 *
 * OPRs
 *    GET    nrb_dealers_area/opr/pdf/(?P<uid>[\s\S]+)
 *    GET    nrb_dealers_area/opr/(?P<uid>[\s\S]+)
 *    GET    nrb_dealers_area/opr
 *    POST   nrb_dealers_area/opr
 *    DELETE nrb_dealers_area/opr
 *
 * CONTACT US
 *    GET    nrb_dealers_area/contact_us/pdf/(?P<id>[\s\S]+)
 *    GET    nrb_dealers_area/contact_us
 *    GET    nrb_dealers_area/contact_us/(?P<id>[\d]+)
 *    POST   nrb_dealers_area/contact_us/(?P<id>[\d]+)
 *
 * WARRANTY FORM
 *    POST   nrb_dealers_area/warranty/form/(?P<uid>[\s\S]+)
 *    POST   nrb_dealers_area/warranty/form
 *
 * WARRANTY
 *    GET    nrb_dealers_area/warranty/reasons
 *    GET    nrb_dealers_area/warranty/parts/(?P<id>[\s\S]+)
 *    GET    nrb_dealers_area/warranty/labor/(?P<id>[\s\S]+)
 *    GET    nrb_dealers_area/warranty/notes/(?P<id>[\s\S]+)
 *    GET    nrb_dealers_area/warranty/(?P<id>[\s\S]+)
 *    GET    nrb_dealers_area/warranty
 *    POST   nrb_dealers_area/warranty/warranty/(?P<uid>[\s\S]+)
 *    POST   nrb_dealers_area/warranty/warranty
 *
 */

function nrb_dealers_area_register_routes() {

    register_rest_route( 'nrb_dealers_area', 'foo', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_foo',
        'args'      => array(
            'param1' => array(
                'default' => 'view',
            ),
        ),
    ) );

    register_rest_route( 'nrb_dealers_area', 'bar', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_bar',
    ) );

    register_rest_route( 'nrb_dealers_area', 'bar/(?P<id>[\s\S]+)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_bar_id',
    ) );

    /***********************************************
     *
     * USERS
     *
     ************************************************/
    register_rest_route( 'nrb_dealers_area', 'userinfo', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_userinfo',
    ) );

    /***********************************************
     *
     * DEALERS
     *
     ************************************************/

    // cRud all dealership list
    register_rest_route( 'nrb_dealers_area', 'dealerships', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_dealerships',
    ) );

    // cRud all dealers
    register_rest_route( 'nrb_dealers_area', 'dealers', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_dealers',
    ) );

    // cRud one dealer
    register_rest_route( 'nrb_dealers_area', 'dealers/(?P<uid>[\s\S]+)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_dealers_uid',
    ) );

    // crUd one dealer
    register_rest_route( 'nrb_dealers_area', 'dealers/(?P<uid>[\s\S]+)', array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => 'nrb_dealers_area_serve_route_dealer_update',
    ) );

    // Crud one dealer
    register_rest_route( 'nrb_dealers_area', 'dealers', array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => 'nrb_dealers_area_serve_route_dealer_create',
    ) );

    // cRud all dealer labor rates
    register_rest_route( 'nrb_dealers_area', 'rates', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_rates',
    ) );


    /***********************************************
     *
     * HULLS
     *
     ************************************************/

    register_rest_route( 'nrb_dealers_area', 'drihulls', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_drihulls',
    ) );

    register_rest_route( 'nrb_dealers_area', 'oprhulls', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_oprhulls',
    ) );

    register_rest_route( 'nrb_dealers_area', 'hulls', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_hulls',
    ) );

    register_rest_route( 'nrb_dealers_area', 'hulls/(?P<hull>[\s\S]+)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_hulls_hull',
    ) );

    /***********************************************
     *
     * DRIs
     *
     ************************************************/

    register_rest_route( 'nrb_dealers_area', 'dri/pdf/(?P<uid>[\s\S]+)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_dri_pdf',
    ) );

    register_rest_route( 'nrb_dealers_area', 'dri/(?P<uid>[\s\S]+)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_dri',
    ) );

    register_rest_route( 'nrb_dealers_area', 'dri', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_dris',
    ) );

    register_rest_route( 'nrb_dealers_area', 'dri', array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => 'nrb_dealers_area_serve_route_dri_create',
    ) );

    register_rest_route( 'nrb_dealers_area', 'dri/(?P<uid>[\s\S]+)', array(
      'methods'  => WP_REST_Server::DELETABLE,
      'callback' => 'nrb_dealers_area_serve_route_dri_delete',
    ) );

    /***********************************************
     *
     * OPRs
     *
     ************************************************/

    register_rest_route( 'nrb_dealers_area', 'opr/pdf/(?P<uid>[\s\S]+)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_opr_pdf',
    ) );

    register_rest_route( 'nrb_dealers_area', 'opr/(?P<uid>[\s\S]+)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_opr',
    ) );

    register_rest_route( 'nrb_dealers_area', 'opr', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_oprs',
    ) );

    register_rest_route( 'nrb_dealers_area', 'opr', array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => 'nrb_dealers_area_serve_route_opr_create',
    ) );

    register_rest_route( 'nrb_dealers_area', 'opr/(?P<uid>[\s\S]+)', array(
      'methods'  => WP_REST_Server::DELETABLE,
      'callback' => 'nrb_dealers_area_serve_route_opr_delete',
    ) );

    /***********************************************
     *
     * CONTACT US
     *
     ************************************************/

    register_rest_route( 'nrb_dealers_area', 'contact_us/pdf/(?P<id>[\s\S]+)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_contact_us_pdf',
    ) );

    // cRud all contact us forms
    register_rest_route( 'nrb_dealers_area', 'contact_us', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_contact_us',
    ) );

    // cRud single contact_us
    register_rest_route( 'nrb_dealers_area', 'contact_us/(?P<id>[\d]+)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_contact_us_id',
    ) );

    // crUd one contact_us
    register_rest_route( 'nrb_dealers_area', 'contact_us/(?P<uid>[\s\S]+)', array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => 'nrb_dealers_area_serve_route_contact_us_update',
    ) );


    /***********************************************
     *
     * WARRANTY FORM
     *
     ************************************************/

    // crUd one warranty form
    register_rest_route( 'nrb_dealers_area', 'warranty/form/(?P<uid>[\s\S]+)', array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => 'nrb_dealers_area_serve_route_warranty_form_update',
    ) );

    // Crud one warranty from
    register_rest_route( 'nrb_dealers_area', 'warranty/form', array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => 'nrb_dealers_area_serve_route_warranty_form_create',
      ) );

    /***********************************************
     *
     * WARRANTY
     *
     ************************************************/

    // cRud reason list
    register_rest_route( 'nrb_dealers_area', 'warranty/reasons', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_warranty_reasons',
    ) );

    // cRud one warranty parts details by id
    register_rest_route( 'nrb_dealers_area', 'warranty/parts/(?P<id>[\s\S]+)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_warranty_parts_id',
    ) );

    // cRud one warranty labor details by id
    register_rest_route( 'nrb_dealers_area', 'warranty/labor/(?P<id>[\s\S]+)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_warranty_labor_id',
    ) );

    // cRud one warranty notes details by id
    register_rest_route( 'nrb_dealers_area', 'warranty/(?P<id>[\s\S]+)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_warranty_id',
    ) );

    // cRud one warranty by uid
    // register_rest_route( 'nrb_dealers_area', 'warranty/(?P<uid>[\s\S]+)', array(
    //     'methods'  => WP_REST_Server::READABLE,
    //     'callback' => 'nrb_dealers_area_serve_route_warranty_uid',
    // ) );

    // cRud warranty list
    register_rest_route( 'nrb_dealers_area', 'warranty', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'nrb_dealers_area_serve_route_warranty',
    ) );

    // crUd one warranty
    register_rest_route( 'nrb_dealers_area', 'warranty/(?P<uid>[\s\S]+)', array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => 'nrb_dealers_area_serve_route_warranty_update',
    ) );

    // Crud one warranty
    register_rest_route( 'nrb_dealers_area', 'warranty', array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => 'nrb_dealers_area_serve_route_warranty_create',
    ) );

}


/**
 * Generate results for the /wp-json/nrb_dealers_area/foo route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_foo( WP_REST_Request $request ) {
    global $wpdb;
    // Do something with the $request

    // Return either a WP_REST_Response or WP_Error object
    // $bob = $request->get_param('param1');
    // $bob = base62encode(random_bytes(9));
    $response = [];
    // $response['message'] = "Hi Will ... {$bob}";
    $response['item'] = user_to_dealer_name(15);
    return $response;
}



/**
 * Generate results for the /wp-json/nrb_dealers_area/bar route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_bar( WP_REST_Request $request ) {
    global $wpdb;
    // Do something with the $request

    // Return either a WP_REST_Response or WP_Error object
    $response = [];
    $response['message'] = "Hi Fred no data.";
    $response['uid'] = base62encode(random_bytes(9));
    return $response;
}



/**
 * Generate results for the /wp-json/nrb_dealers_area/bar/id route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_bar_id( WP_REST_Request $request ) {
    global $wpdb;

    $id = urldecode($request['id']);

    return "You are in transition with " . array('One','Two','Three','Four','Five','Six')[rand(0,5)];
}



/******************************************************************
 *
 * DEALERS
 *
 ******************************************************************/

function nrb_dealers_area_serve_route_dealerships( WP_REST_Request $request ){
    global $wpdb;

    $response = $wpdb->get_results(
        "SELECT dealer, nickname, dealer_group, contactus
        FROM wp_nrb_dealers
        GROUP BY nickname
        ORDER BY active DESC, nickname");

    return $response;
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/dealers route READ.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_dealers( WP_REST_Request $request ) {
    global $wpdb;

    $response = $wpdb->get_results(
        "SELECT
        `uid`,
        `dealer`,
        `login`,
        `password`,
        `address1`,
        `address2`,
        `city`,
        `state`,
        `zip`,
        `country`,
        `lat`,
        `lng`,
        `phone`,
        `fax`,
        `url`,
        `dealer_group`,
        `email_store`,
        `email_manager`,
        `email_admin`,
        `email_sales`,
        `email_parts`,
        `email_service`,
        `email_warranty`,
        `service_rate`,
        `active`
        FROM wp_nrb_dealers
        ORDER BY `active` DESC, `dealer`");

    return $response;
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/dealers/id route READ.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_dealers_uid( WP_REST_Request $request ) {
    global $wpdb;

    $uid   = $request['uid'];
    $response = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_nrb_dealers WHERE uid = %s",$uid));
    unset($response->id);
    return $response;
}



/**
 * Generate results for the /wp-json/nrb_dealers_area/dealers/id route UPDATE.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_dealer_update( WP_REST_Request $request ) {
    global $wpdb;

    $uid   = $request['uid'];
    $params = $request->get_params();
    $wpdb->update('wp_nrb_dealers',$params,array('uid'=>$uid));
    $response['uid'] = $uid;
    // $response['query'] = $wpdb->last_query;
    // $response['result'] = $wpdb->last_result;
    // $response['error'] = $wpdb->last_error;
    return array($response);
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/dealers route CREATE.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_dealer_create( WP_REST_Request $request ) {
    global $wpdb;

    $params = $request->get_params();
    $params['uid'] = base62encode(random_bytes(9));
    $wpdb->insert('wp_nrb_dealers',$params);
    $response['message'] = 'success';
    $response['uid'] = $params['uid'];
    // $response['query'] = $wpdb->last_query;
    // $response['result'] = $wpdb->last_result;
    //$response['error'] = $wpdb->last_error;
    return array($response);
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/rates route CREATE.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_rates( WP_REST_Request $request ) {
    global $wpdb;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $dealer = user_to_dealer_group_name(wp_get_current_user()->id);
    $response = $wpdb->get_results("SELECT dealer, dealer_group, service_rate FROM wp_nrb_dealers GROUP BY dealer_group");
    if ($dealer != 'North River Boats') {
        foreach($response as $item) {
            $result[] = array('dealer' => $item->dealer, 'service_rate' => ($item->dealer == $dealer ? $item->service_rate : 0));
        }
        return $result;
    } else {
        return $response;
    }

}



/******************************************************************
 *
 * Hulls and Hull Serials
 *
 ******************************************************************/



/**
 * Generate results for the /wp-json/nrb_dealers_area/drihulls route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_drihulls( WP_REST_Request $request ) {
    global $wpdb;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $dealer = user_to_dealer_group_name(wp_get_current_user()->id);

    if ($dealer == 'North River Boats') {
        // return all hulls  -- also need to  deal with no dealer go from hull to dealer for NRB fillouts
        $sql = "SELECT t1.hull_serial_number AS id, t1.hull_serial_number AS label, t1.dealership, t1.model,  t1.date_delivered "
            . "FROM wp_nrb_hulls t1 "
            . "LEFT OUTER JOIN wp_nrb_dri t2  ON t1.hull_serial_number = t2.hull_serial_number "
            . "WHERE t2.hull_serial_number IS NULL "
            . "ORDER BY SUBSTR(t1.hull_serial_number, 12,3), t1.hull_serial_number";
    } else {
        // return hulls for dealer
        $sql = $wpdb->prepare("SELECT t1.hull_serial_number AS id, t1.hull_serial_number AS label, t1.dealership, t1.model,  t1.date_delivered  "
            . "FROM wp_nrb_hulls t1 "
            . "LEFT OUTER JOIN wp_nrb_dri t2  ON t1.hull_serial_number = t2.hull_serial_number "
            . "WHERE t2.hull_serial_number IS NULL "
            . "AND t1.dealership = %s "
            . "ORDER BY SUBSTR(t1.hull_serial_number, 12,3), t1.hull_serial_number", array($dealer));
    }

    $response = $wpdb->get_results($sql,  OBJECT);

    return $response;
}

/**
 * Generate results for the /wp-json/nrb_dealers_area/oprhulls route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_oprhulls( WP_REST_Request $request ) {
    global $wpdb;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $dealer = user_to_dealer_group_name(wp_get_current_user()->id);

    if ($dealer == 'North River Boats') {
        // return all hulls  -- also need to  deal with no dealer go from hull to dealer for NRB fillouts
        $sql = "SELECT t1.hull_serial_number AS id, t1.hull_serial_number AS label, t1.dealership, t1.model  "
            . "FROM wp_nrb_hulls t1 "
            . "LEFT OUTER JOIN wp_nrb_opr t2  ON t1.hull_serial_number = t2.hull_serial_number "
            . "WHERE t2.hull_serial_number IS NULL "
            . "ORDER BY SUBSTR(t1.hull_serial_number, 12,3), t1.hull_serial_number";
    } else {
        // return hulls for dealer
        $sql = $wpdb->prepare("SELECT t1.hull_serial_number AS id, t1.hull_serial_number AS label, t1.dealership, t1.model  "
            . "FROM wp_nrb_hulls t1 "
            . "LEFT OUTER JOIN wp_nrb_opr t2  ON t1.hull_serial_number = t2.hull_serial_number "
            . "WHERE t2.hull_serial_number IS NULL "
            . "AND t1.dealership = %s "
            . "ORDER BY SUBSTR(t1.hull_serial_number, 12,3), t1.hull_serial_number", array($dealer));
    }
    $response = $wpdb->get_results($sql,  OBJECT);
    return $response;
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/hulls route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_hulls( WP_REST_Request $request ) {
    global $wpdb;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $dealer = user_to_dealer_group_name(wp_get_current_user()->id);
    $resposne = array();

    if ($dealer == 'North River Boats') {
        // return all hulls  -- also need to  deal with no dealer go from hull to dealer for NRB fillouts
        $sql = "SELECT SUBSTRING(t1.hull_serial_number,5) AS id, t1.dealership, t1.model  "
            . "FROM wp_nrb_hulls t1 "
            . "LEFT OUTER JOIN wp_nrb_opr t2  ON t1.hull_serial_number = t2.hull_serial_number "
            . "ORDER BY t1.hull_serial_number";
        $response['dealer'] = $wpdb->get_results($sql,  OBJECT);
        $response['nondealer']  = array();
    } else {
        // return hulls for dealer
        $sql = $wpdb->prepare("SELECT SUBSTRING(t1.hull_serial_number,5) AS name, SUBSTRING(t1.hull_serial_number,5) AS id, t1.dealership, t1.model  "
            . "FROM wp_nrb_hulls t1 "
            . "LEFT OUTER JOIN wp_nrb_opr t2  ON t1.hull_serial_number = t2.hull_serial_number "
            . "WHERE t1.dealership = %s "
            . "ORDER BY t1.hull_serial_number", array($dealer));
        $response['dealer'] = $wpdb->get_results($sql,  OBJECT);
        $sql = $wpdb->prepare("SELECT SUBSTRING(t1.hull_serial_number,5) AS name, SUBSTRING(t1.hull_serial_number,5) AS id, %s as dealership, t1.model  "
            . "FROM wp_nrb_hulls t1 "
            . "LEFT OUTER JOIN wp_nrb_opr t2  ON t1.hull_serial_number = t2.hull_serial_number "
            . "WHERE t1.dealership != %s "
            . "ORDER BY t1.hull_serial_number", array($dealer, $dealer));
        $response['nondealer'] = $wpdb->get_results($sql,  OBJECT);
    }
    return $response;
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/hulls/hull route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_hulls_hull( WP_REST_Request $request ) {
    global $wpdb;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $hull   = urldecode($request['hull']);
    $dealer = user_to_dealer_group_name(wp_get_current_user()->id);

    if ($dealer == 'North River Boats') {
        // return all hulls  -- also need to  deal with no dealer go from hull to dealer for NRB fillouts
        $sql = $wpdb->prepare("SELECT t1.* "
            . "FROM wp_nrb_hulls t1 "
            . "LEFT OUTER JOIN wp_nrb_opr t2  ON t1.hull_serial_number = t2.hull_serial_number "
            . "WHERE t1.hull_serial_number = %s "
            . "ORDER BY t1.hull_serial_number", array($hull));
    } else {
        // return hulls for dealer
        $sql = $wpdb->prepare("SELECT t1.* "
            . "FROM wp_nrb_hulls t1 "
            . "LEFT OUTER JOIN wp_nrb_opr t2  ON t1.hull_serial_number = t2.hull_serial_number "
            . "WHERE t1.hull_serial_number = %s " // -- AND  t1.dealership = %s  $dealer
            . "ORDER BY t1.hull_serial_number", array($hull));
    }
    $response = $wpdb->get_row($sql,  OBJECT);
    $response->hull_serial_number = substr($response->hull_serial_number, 4);
    unset($response->id);
    if ($response->dealership != $dealer) {
      foreach(array('last_name', 'first_name', 'phone', 'mailing_address', 'mailing_city', 'mailing_state') as $key) {
        $response->{$key} = "";
      }
    }
    return $response;
}



/******************************************************************
 *
 * users and dealers
 *
 ******************************************************************/

/**
 * Generate results for the /wp-json/nrb_dealers_area/userinfo route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_userinfo( WP_REST_Request $request ) {
    global $wpdb;

    $row = $request->get_params();

    $response = array();
    $user =  wp_get_current_user();
    if ($user) {
        $response['dealership'] = user_to_dealer_group_name($user->id);
    } else {
        $response['dealership'] = "None";
        $response['service_rate'] = "0";
    }

    return $response;
}



/******************************************************************
 *
 * Dealer Receipt Inspection Form -- DRI
 *
 ******************************************************************/

/**
 * Generate results for the /wp-json/nrb_dealers_area/dris route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_dris( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $dealer = user_to_dealer_group_name(wp_get_current_user()->id);

    if ($dealer == 'North River Boats') {
        // return all dris  -- also need to  deal with no dealer go from hull to dealer for NRB fillouts
        $sql = "SELECT uid as id, dealership, date_received, hull_serial_number, model, checked_by, email_address "
           . "FROM wp_nrb_dri "
           . "ORDER BY date_received";
    } else {
        // return dris for dealer
        $sql = $wpdb->prepare("SELECT uid as id, dealership, date_received, hull_serial_number, model, checked_by, email_address "
            . "FROM wp_nrb_dri "
            . "WHERE dealership = %s "
            . "ORDER BY date_received", array($dealer));
    }

    $response = $wpdb->get_results($sql,  OBJECT);

    return $response;
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/dri route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_dri( WP_REST_Request $request ) {
    global $wpdb;

    $folder = "/var/www/html/pdfs/";
    $url = "/pdfs/";

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $uid   = urldecode($request['uid']);
    $row = (array) $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_nrb_dri WHERE uid = %s",$uid));
    $row['id'] = $row['uid'];
    unset($row['uid']);
    $filename = "DRI-" . str_replace(' ', '', $row["hull_serial_number"]) . ".pdf";

    return $row;
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/dri route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_dri_create( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    // FWW change row to $result
    $row = $request->get_params();

    $row['uid'] = base62encode(random_bytes(9));
    $row['date_received'] = substr($request['date_received'],6,4)."-".substr($request['date_received'],0,2)."-".substr($request['date_received'],3,2);
    $row['submitted'] = date("Y-m-d H:i:s");

    $dri_folder = '/var/www/html/wp-content/uploads/nrbforms/dri/';
    $dri_url = 'https://www.northriverboats.com/wp-content/uploads/nrbforms/dri/';
    for ($i =0; $i < count($_FILES['files']['name']); $i++) {
      $name = $_FILES['files']['name'][$i + 1];
      $ext =  $ext = pathinfo($name, PATHINFO_EXTENSION);
      $tmp_name = $_FILES['files']['tmp_name'][$i + 1];
      $file = $dri_folder . str_replace(' ','_',$request['hull_serial_number']) . '_' . strval($i+1) . '.' . $ext;
      $row['file_'.strval($i+1)] = $dri_url . str_replace(' ','_',$request['hull_serial_number']) . '_' . strval($i+1) . '.' . $ext;
      $result = move_uploaded_file($tmp_name, $file);
    }

    // add read $_POST and process to $row here
    $result = $wpdb->insert('wp_nrb_dri',$row);
    if ($result = 1) {
        $row['id'] = $wpdb->insert_id;
    } else {
        // This is an error send to admin
        $to = 'fredw@northriverboats.com';
        $subject = "DRI Form Insert Error";
        $body = print_r($row, true);
        $body .= "\n\nLAST QUERY ========================================\n";
        $body .= print_r($wpdb->last_query, true);
        $body .= "\n\nLAST RESULT =======================================\n";
        $body .= print_r($wpdb->last_result, true);
        $body .= "\n\nLAST ERROR ========================================\n";
        $body .= print_r($wpdb->last_error, true);
        $header = 'From: North River Website <webmaster@northriverboats.com>';
        mail($to,$subject,$body, $header);
    }

    // create HTML form response
    ob_start();
    require('templates/dri_template.php');
    $html = ob_get_contents();
    ob_end_clean();

    // Create PDF
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML(utf8_encode($html));
    $attachment = $mpdf->Output('', 'S');

    // PHPMailer Object
    $mail = new PHPMailer\PHPMailer\PHPMailer;
    $mail->From = "webmaster@northriverboats.com";
    $mail->FromName = "North River Website";
    $mail->addReplyTo("fredw@northriverboats.com", "Fredrick W. Warren");
    if ($debug) {
        $mail->addAddress("fredw@northriverboats.com", "Fred Warren");
    } else {
        $mail->addAddress("jamesg@northriverboats.com", "James Green");
    }
    $mail->isHTML(true);

    $mail->Subject = "Dealer Receipt Inspection Form - " . $row['hull_serial_number'];
    $mail->Body = $html;
    $mail->AltBody = "This is the plain text version of the email content";
    $mail->AddStringAttachment($attachment, "DRI - {$row['hull_serial_number']}.pdf", 'base64', 'application/pdf');

    if(!$mail->send()) {
        Analog::log ("DRI Mailer Error: " . $mail->ErrorInfo);
        Analog::log (print_r($row, true));
    }

    if ($debug) {
      Analog::debug('Useful diagnostic message');
    }
    // fix up result
    $row['id'] = $row['uid'];
    unset($row['uid']);
    return $row;
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/dri route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_dri_pdf( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;
    $folder = "/var/www/html/pdfs/";
    $url = "/pdfs/";

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $uid   = urldecode($request['uid']);
    $row = (array) $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_nrb_dri WHERE uid = %s",$uid));
    $filename = "DRI-" . str_replace(' ', '', $row["hull_serial_number"]) . ".pdf";

    // remove old files
    $interval = strtotime('-8 hours');
    foreach (glob($folder."*") as $file) {
      if (filemtime($file) <= $interval ) unlink($file);
    }

    // create HTML form response
    ob_start();
    require('templates/dri_template.php');
    $html = ob_get_contents();
    ob_end_clean();

    // Create Dealer PDF
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML(utf8_encode($html));
    $mpdf->Output($folder . $filename, \Mpdf\Output\Destination::FILE);

    return array("filename" => $url . $filename);
}

/**
 * Generate results for the /wp-json/nrb_dealers_area/dri route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_dri_delete( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    // FWW change row to $result
    $row = $request->get_params();

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $uid   = urldecode($request['uid']);
    $row = (array) $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_nrb_dri WHERE uid = %s",$uid));

    if (!$row) {
      return array("status" => "not found");
    }

    $files = array_intersect_key($row, array_flip(['file_1', 'file_2', 'file_3', 'file_4', 'file_5']));

    # delet files
    foreach ($files as $file) {
      if ($file) {
        unlink($file);
      }
    }

    # delete record
    $result = $wpdb->delete('wp_nrb_dri', array('uid' => $uid));

    return array("status" => "ok");
}



/******************************************************************
 *
 * Original Purchaser Registration Form -- OPR
 *
 ******************************************************************/

/**
 * Generate results for the /wp-json/nrb_dealers_area/oprs route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_oprs( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $dealer = user_to_dealer_group_name(wp_get_current_user()->id);
    if ($dealer == 'North River Boats') {
      $stanza = "--";
    } else {
      $stanza = "WHERE dealership = '".$dealer."'";
    }

      // return all oprs  -- also need to  deal with no dealer go from hull to dealer for NRB fillouts
    $sql = ("SELECT
          uid as id,
          dealership,
          submitted,
          date_deposit,
          date_delivered,
          hull_serial_number,
          model,
          first_name,
          last_name,
          street_city,
          street_state
        FROM wp_nrb_opr
        " .  $stanza  . "
        ORDER BY submitted");

    $response = $wpdb->get_results($sql,  OBJECT);

    return $response;
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/opr route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_opr( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;
    $folder = "/var/www/html/pdfs/";
    $url = "/pdfs/";

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $uid   = urldecode($request['uid']);
    $row = (array) $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_nrb_opr WHERE uid = %s",$uid));
    $row['id'] = $row['uid'];
    unset($row['uid']);
    $filename = "OPR-" . str_replace(' ', '', $row["hull_serial_number"]) . ".pdf";

    return $row;
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/opr route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_opr_create( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    // FWW convert $row to $result
    $row = $request->get_params();

    $row['uid'] = base62encode(random_bytes(9));
    $row['submitted'] = date('Y-m-d H:i:s');

    // Check and remove from opr_deletes table
    $result = (array) $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_nrb_opr_deletes WHERE hull_serial_number = %s",$row['hull_serial_number']));
    if ($result) {
        $result = $wpdb->delete('wp_nrb_opr_deletes', array('hull_serial_number' => $row['hull_serial_number']));
    }

    $result = $wpdb->insert('wp_nrb_opr',$row);
    if ($result == false) {
        // This is an error send to admin
        $to = 'fredw@northriverboats.com';
        $subject = "OPR Form Insert Error";
        $body = print_r($row, true);
        $body .= "\n\nLAST QUERY ========================================\n";
        $body .= print_r($wpdb->last_query, true);
        $body .= "\n\nLAST RESULT =======================================\n";
        $body .= print_r($wpdb->last_result, true);
        $body .= "\n\nLAST ERROR ========================================\n";
        $body .= print_r($wpdb->last_error, true);
        $header = 'From: North River Website <webmaster@northriverboats.com>';
        mail($to,$subject,$body, $header);
        return new WP_Error( 'Interenal Server Error', 'Not able to submit opr due to server error', array( 'status' => 500 ) );
    }

    // read pin number from hulls
    $result = (array) $wpdb->get_row($wpdb->prepare("SELECT pin FROM wp_nrb_hulls WHERE hull_serial_number = %s",$row['hull_serial_number']));
    $row['pin'] = $result['pin'];

    // set X in OPR column of hulls
    $wpdb->update('wp_nrb_hulls',array('opr' => 'X'),array('hull_serial_number' => $row['hull_serial_number']));

    // other thing
    $temp_png = tempnam(sys_get_temp_dir(),"img_");
    $im = imagecreatefromjpeg(dirname(__FILE__).'/templates/gt.jpg');
    $black = imagecolorallocate($im, 32, 32, 32);
    imagettftext($im, 16, 0, 363, 649, $black, dirname(__FILE__).'/templates/Trade_Gothic_LT_Bold_Condensed_No18.ttf', $row['hull_serial_number']);
    imagettftext($im, 16, 0, 415, 681, $black, dirname(__FILE__).'/templates/Trade_Gothic_LT_Bold_Condensed_No18.ttf', $row['pin']);
    imagepng($im, $temp_png);

    // create HTML form response
    ob_start();
    require('templates/opr_template.php');
    $html = ob_get_contents();
    ob_end_clean();

    // Create Factory PDF
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML(utf8_encode($html));
    $attachment = $mpdf->Output('', 'S');

    // Create Survey PDF letter
    ob_start();
    require('templates/opr_letter_template.php');
    $html1 = ob_get_contents();
    ob_end_clean();

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML(utf8_encode($html1));
    $letter = $mpdf->Output('', 'S');

    // Create Survey HTML letter
    ob_start();
    require('templates/goldenticket_html_template.php');
    $html2 = ob_get_contents();
    ob_end_clean();

    // Create Survey Text letter
    ob_start();
    require('templates/goldenticket_text_template.php');
    $text = ob_get_contents();
    ob_end_clean();

    // PHPMailer Object To Customer
    if (strlen($row['email']) > 2) {
        $mail = new PHPMailer\PHPMailer\PHPMailer;
        $mail->From = "webmaster@northriverboats.com";
        $mail->FromName = "North River Website";
        $mail->addReplyTo("saral@northriverboats.com", "Sara Lynn");
        if ($debug) {
            $mail->addAddress("fredw@northriverboats.com", "Fred Warren");
        } else {
            $mail->addAddress($row['email'], $row['first_name'] . " " . $row['last_name']);
        }
        $mail->isHTML(true);
        $mail->Subject = "Receive 2 free hats for your purchase of a North River Boat";
        $mail->AddEmbeddedImage($temp_png, 'logoimg', 'logo.png');
        $mail->Body = $html2;
        $mail->AltBody = $text;

        if(!$mail->send()) {
            Analog::log ("OPR Mailer to Customer Error: " . $mail->ErrorInfo);
            Analog::log (print_r($row, true));
        }
    }

    // PHPMailer Object To Factory
    $mail = new PHPMailer\PHPMailer\PHPMailer;
    $mail->From = "webmaster@northriverboats.com";
    $mail->FromName = "North River Website";
    $mail->addReplyTo("fredw@northriverboats.com", "Fredrick W. Warren");
    if ($debug) {
        $mail->addAddress("fredw@northriverboats.com", "Fredrick W. Warren");
    } else {
        $mail->addAddress("saral@northriverboats.com", "Sara Lynn");
        $mail->addAddress("sarae@northriverboats.com", "Sara Elder");
        $mail->addAddress("chrisr@northriverboats.com", "Chris Roberts");
        $mail->addAddress("joed@northriverboats.com", "Joe Depweg");
    }
    $mail->isHTML(true);

    if (strlen($row['email']) > 2) {
        $mail->Subject = "Original Purchaser Registration - " . $row['hull_serial_number'];
    } else {
        $mail->Subject = "Original Purchaser Registration - " . $row['hull_serial_number'] . " - PLEASE MAIL COPY";
    }
    $mail->Body = $html;
    $mail->AltBody = $text;
    $mail->AddStringAttachment($attachment, "Survey - {$row['hull_serial_number']}.pdf", 'base64', 'application/pdf');

    if(!$mail->send()) {
        Analog::log ("OPR Mailer to Factory Error: " . $mail->ErrorInfo);
        Analog::log (print_r($row, true));
    }

    return $row;
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/opr/pdf route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_opr_pdf( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;
    $folder = "/var/www/html/pdfs/";
    $url = "/pdfs/";

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $uid   = urldecode($request['uid']);
    $row = (array) $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_nrb_opr WHERE uid = %s",$uid));
    $filename = "OPR-" . str_replace(' ', '', $row["hull_serial_number"]) . ".pdf";

    // remove old files
    $interval = strtotime('-8 hours');
    foreach (glob($folder."*") as $file) {
      if (filemtime($file) <= $interval ) unlink($file);
    }

    // create HTML form response
    ob_start();
    require('templates/opr_template.php');
    $html = ob_get_contents();
    ob_end_clean();

    // Create Dealer PDF
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML(utf8_encode($html));
    $mpdf->Output($folder . $filename, \Mpdf\Output\Destination::FILE);

    return array("filename" => $url . $filename);
}

/**
 * Generate results for the /wp-json/nrb_dealers_area/opr route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_opr_delete( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    // FWW change row to $result
    $row = $request->get_params();

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $uid   = urldecode($request['uid']);
    $row = (array) $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_nrb_opr WHERE uid = %s",$uid));

    if (!$row) {
      return array("status" => "not found");
    }

    # delete record
    $result = $wpdb->delete('wp_nrb_opr', array('uid' => $uid));

    # add to deleted list
    $wpdb->insert('wp_nrb_opr_deletes', array('hull_serial_number' => $row['hull_serial_number']));
    return array("status" => "ok");
}



/******************************************************************
 *
 * Contact Us Form
 *
 ******************************************************************/

/**
 * Generate results for the /wp-json/nrb_dealers_area/contact_us/pdf route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_contact_us_pdf( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;
    $folder = "/var/www/html/pdfs/";
    $url = "/pdfs/";

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $id   = urldecode($request['id']);
    $row = (array) $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_nrb_contact_us WHERE id = %s",$id));
    $filename = "CONTACTUS-" . $id  . ".pdf";

    // remove old files
    $interval = strtotime('-8 hours');
    foreach (glob($folder."*") as $file) {
      if (filemtime($file) <= $interval ) unlink($file);
    }

    // create HTML form response
    ob_start();
    require('templates/contact_us_rec.php');
    $html = ob_get_contents();
    ob_end_clean();

    // Create Dealer PDF
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML(utf8_encode($html));
    $mpdf->Output($folder . $filename, \Mpdf\Output\Destination::FILE);

    return array("filename" => $url . $filename);
}



/**
 * Generate results for the /wp-json/nrb_dealers_area/dealers/id route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */

function nrb_dealers_area_serve_route_contact_us( WP_REST_Request $request ) {
    global $wpdb;

    $dealer = user_to_dealer_group_name(wp_get_current_user()->id);
    if ($dealer == 'North River Boats') {
      $stanza = "--";
    } else {
      $stanza = "WHERE dealership = '".$dealer."'";
    }

    $response = $wpdb->get_results(
        "SELECT *
        FROM `wp_nrb_contact_us`
         " .  $stanza  . "
        ORDER BY `submitted` DESC");

    return $response;
}



/**
 * Generate results for the /wp-json/nrb_dealers_area/hulls/id route.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_contact_us_id( WP_REST_Request $request ) {
    global $wpdb;

    $id   = (int) $request['id'];

    $sql = $wpdb->prepare("SELECT *  "
        . "FROM wp_nrb_contact_us "
        . "WHERE id = %s",$id);

    $response = $wpdb->get_results($sql,  OBJECT)[0];

    return $response;
}

/**
 * Generate results for the /wp-json/nrb_dealers_area/dealers/id route UPDATE.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_contact_us_update( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    // FWW convert $row to $result
    $id   = $request['id'];
    $row = $request->get_params();
    $wpdb->update('wp_nrb_contact_us', $row, array('id'=>$id));

    ob_start();
    var_dump($row['dealership']);
    var_dump($row['nickname']);
    $rez = ob_get_contents();
    ob_end_clean();
    Analog::log($rez);

    // create HTML form response
    ob_start();
    require('templates/contact_us_rec.php');
    $html = ob_get_contents();
    ob_end_clean();

    // Create Dealer PDF
    // $mpdf = new \Mpdf\Mpdf();
    // $mpdf->WriteHTML(utf8_encode($html));
    // $attachment = $mpdf->Output('', 'S');

    // PHPMailer Object To Factory
    $mail = new PHPMailer\PHPMailer\PHPMailer;
    $mail->From = "webmaster@northriverboats.com";
    $mail->FromName = "North River Website";
    $mail->addReplyTo("fredw@northriverboats.com", "Fredrick W. Warren");
    if ($debug) {
        $mail->addAddress("fredw@northriverboats.com", "Fredrick W. Warren");
        $html .= '<p>' . dealerToEmail($row['nickname'],$row['subject']) .' </p>';
    } else {
        $persons = explode(" ; ", dealerToEmail($row['nickname'],$row['subject']));
        foreach ($persons as $person) {
            $mail->addAddress($person);
        }
    }
    $mail->isHTML(true);
    $mail->Subject = 'NRB Customer Contact - ' . $row['name'];
    $mail->Body = $html;
    // $mail->AddStringAttachment($attachment, "ContactUs - {$row['id']}.pdf", 'base64', 'application/pdf');

    if(!$mail->send()) {
        Analog::log ("NRB Customre Contact Resent to Dealer Error: " . $mail->ErrorInfo);
        Analog::log (print_r($row, true));
    }

    // return "You are in transition with " . array('One','Two','Three','Four','Five','Six')[rand(0,5)];
    return $row;
}



/******************************************************************
 *
 * WARRANTY FORM
 *
 ******************************************************************/

/**
 * Generate results for the /wp-json/nrb_dealers_area/warranty/form/id route UPDATE.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_warranty_form_update( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $uid   = $request['uid'];
    $params = $request->get_params();
    // $wpdb->update('wp_nrb_dealers',$params,array('uid'=>$uid));
    // $response['uid'] = $uid;
    $response = $params;
    $response['message'] = 'Form Update Received';

    return array($response);
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/warranty/form route CREATE.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_warranty_form_create( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    $params = $request->get_params();
    $params['uid'] = base62encode(random_bytes(9));
    // $wpdb->insert('wp_nrb_dealers',$params);
    // $response['message'] = 'success';
    // $response['uid'] = $params['uid'];
    $response = $params;
    $response['message'] = 'Form Create Received';
    return array($response);
}


/**



/******************************************************************
 *
 * WARRANTY
 *
 ******************************************************************/

/**
 * Generate results for the /wp-json/nrb_dealers_area/warranty route READ.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_warranty( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $dealer = user_to_dealer_group_name(wp_get_current_user()->id);
    if ($dealer == 'North River Boats') {
      $stanza = "--";
    } else {
      $stanza = "   WHERE h.dealership = '".$dealer."'";
    }

    $response = $wpdb->get_results(
      "  SELECT  w.id, w.uid, SUBSTRING(w.hull_serial_number,5) AS hull_serial_number, DATE_FORMAT(w.date_created, '%m/%d/%Y') as date_created, w.issue, w.status,
             IF(h.last_name ='' AND h.first_name = '' ,'Dealership Inventory',CONCAT(h.last_name, ', ', h.first_name)) as customer, h.dealership
             FROM  wp_nrb_warranty_issues w
             JOIN  wp_nrb_hulls h ON w.hull_serial_number = h.hull_serial_number
        " .  $stanza  . "
         ORDER BY  w.id desc");
    return $response;
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/warranty/id route READ.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_warranty_id( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $dealer = user_to_dealer_group_name(wp_get_current_user()->id);
    if ($dealer == 'North River Boats') {
      $stanza = "";
    } else {
      $stanza = "AND dealership = '".$dealer."'";
    }

    $id   = $request['id'];
    $response = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_nrb_warranty_issues WHERE id = %s " . $stanza, $id));
    $response->mailing_state = state_abbr($response->mailing_state, 'name');
    if(strtoupper($response->mailing_city) === $response->mailing_city) {
      $response->mailing_state = strtoupper($response->mailing_state);
    }
    if(strtoupper($response->state_city) === $response->street_city) {
      $response->street_state = strtoupper($response->street_state);
    }
    $response->hull_serial_number = substr($response->hull_serial_number, 4);
    return $response;
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/warranty/uid route READ.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_warranty_uid( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $dealer = user_to_dealer_group_name(wp_get_current_user()->id);
    if ($dealer == 'North River Boats') {
      $stanza = "";
    } else {
      $stanza = "AND h.dealership = '".$dealer."'";
    }

    $uid   = $request['uid'];
    $response = $wpdb->get_row($wpdb->prepare("SELECT w.id as wid, w.*, h.* FROM wp_nrb_warranty_issues w JOIN wp_nrb_hulls h ON h.hull_serial_number = w.hull_serial_number WHERE w.uid = %s " . $stanza, $uid));
    $response->id = $response->wid;
    unset($response->wid);
    $response->mailing_state = state_abbr($response->mailing_state, 'name');
    $response->street_state = state_abbr($response->street_state, 'name');
    if(strtoupper($response->mailing_city) === $response->mailing_city) {
      $response->mailing_state = strtoupper($response->mailing_state);
    }
    if(strtoupper($response->state_city) === $response->street_city) {
      $response->street_state = strtoupper($response->street_state);
    }
    $response->hull_serial_number = substr($response->hull_serial_number, 4);
    return $response;
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/warranty/id route UPDATE.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_warranty_update( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $uid   = $request['uid'];
    $params = $request->get_params();
    $wpdb->update('wp_nrb_dealers',$params,array('uid'=>$uid));
    $response['uid'] = $uid;

    return array($response);
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/warranty route CREATE.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_warranty_create( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    $params = $request->get_params();
    $params['uid'] = base62encode(random_bytes(9));
    $wpdb->insert('wp_nrb_dealers',$params);
    $response['message'] = 'success';
    $response['uid'] = $params['uid'];
    return array($response);
}


/**
 * Generate results for the /wp-json/nrb_dealers_area/warranty/reasons route READ.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_warranty_reasons( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $response = $wpdb->get_results("SELECT reason FROM wp_nrb_warranty_reasons ORDER BY reason");
    return $response;
}


// Warrenty Part Detail
//    SELECT  @rownum := @rownum + 1 AS rank,
// after FROM or JOIN  , (SELECT @rownum := 0) r

/**
 * Generate results for the /wp-json/nrb_dealers_area/warranty/parts/id route READ.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_warranty_parts_id( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $dealer = user_to_dealer_group_name(wp_get_current_user()->id);
    if ($dealer == 'North River Boats') {
      $stanza = "";
    } else {
      $stanza = "AND h.dealership = '".$dealer."'";
    }

    $id   = $request['id'];
    $response = $wpdb->get_results($wpdb->prepare("
       SELECT  p.id, p.part, p.description, FORMAT(p.qty,2) as qty, FORMAT(p.price,2) as price, p.provided_by
         FROM  wp_nrb_warranty_parts p
         JOIN  wp_nrb_warranty_issues w ON p.warranty_id = w.id
         JOIN  wp_nrb_hulls h ON w.hull_serial_number = h.hull_serial_number
        WHERE  w.id = %s
            " .  $stanza  . "
     ORDER BY  id", $id));
    return $response;
}



/**
 * Generate results for the /wp-json/nrb_dealers_area/warranty/labor/id route READ.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_warranty_labor_id( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $dealer = user_to_dealer_group_name(wp_get_current_user()->id);
    if ($dealer == 'North River Boats') {
      $stanza = "";
    } else {
      $stanza = "AND h.dealership = '".$dealer."'";
    }

    $id   = $request['id'];
    $response = $wpdb->get_results($wpdb->prepare("
       SELECT  p.id, p.part, p.description, FORMAT(p.qty,2) as qty, FORMAT(p.price,2) as price, p.provided_by
         FROM  wp_nrb_warranty_labor p
         JOIN  wp_nrb_warranty_issues w ON p.warranty_id = w.id
         JOIN  wp_nrb_hulls h ON w.hull_serial_number = h.hull_serial_number
        WHERE  w.id = %s
            " .  $stanza  . "
     ORDER BY  id", $id));
    return $response;
}



/**
 * Generate results for the /wp-json/nrb_dealers_area/warranty/notes/id route READ.
 *
 * @param WP_REST_Request $request Full details about the request.
 *
 * @return WP_REST_Response|WP_Error The response for the request.
 */
function nrb_dealers_area_serve_route_warranty_notes_id( WP_REST_Request $request ) {
    global $wpdb;
    global $debug;

    if (! is_user_logged_in()) return new WP_REST_Response(null, 403);

    $dealer = user_to_dealer_group_name(wp_get_current_user()->id);
    if ($dealer == 'North River Boats') {
      $stanza = "";
    } else {
      $stanza = "AND h.dealership = '".$dealer."'";
    }

    $id   = $request['id'];
    $response = $wpdb->get_results($wpdb->prepare("
       SELECT  n.id, n.sequence, n.sent, n.from, n.note,
               IF(CHAR_LENGTH(n.note)<90,n.note,CONCAT(REPLACE(SUBSTRING(n.note,1,90),'\\n',' '),'...')) as tag
         FROM  wp_nrb_warranty_notes n
         JOIN  wp_nrb_warranty_issues w ON n.warranty_id = w.id
         JOIN  wp_nrb_hulls h ON w.hull_serial_number = h.hull_serial_number
        WHERE  w.id = %s
            " .  $stanza  . "
     ORDER BY  id", $id));
    return $response;
}



/******************************************************************
 *
 * Misc Functions
 *
 ******************************************************************/

function user_to_dealer_id($userid) {
    /*
     * wp_info.current_user_id is converted to the dealer_id
     */
    global $wpdb;
    global $debug;

    $sql  = "SELECT IF(d.id > 0, d.id, 0 ) AS dealer_id FROM wp_users u ";
    $sql .= "LEFT JOIN wp_nrb_dealers d ON u.user_login = d.login ";
    $sql .= "WHERE u.id = %d";
    $response = $wpdb->get_row($wpdb->prepare($sql,array($userid)));
    return $response->dealer_id;
}


function user_to_dealer_email($userid, $role) {
    global $wpdb;
    global $debug;

    if (!in_array($role,array('store', 'manager', 'admin', 'sales', 'parts', 'service', 'warranty'))) {
        $role = "store";
    }
    $dealer = user_to_dealer_name($userid);

    $response = $wpdb->get_row($wpdb->prepare("SELECT email_".$role." as email FROM wp_nrb_dealers WHERE dealer = %s",$dealer));
    if ($dealer != "North River Boats") {
        if ($role == "Sales") {
            return $response->email . " ; joed@northriverboats.com ; mikeb@northriverboats.com ; jordana@northriverboats.com";
        }
        return $response->email .  " ; joed@northriverboats.com ; saral@northriverboats.com";
    }

    return $response->email;
}


function user_to_dealer_name($userid) {
    /*
     * wp_info.current_user_id is converted to the dealer_id
     */
    global $wpdb;
    global $debug;

    $sql  = "SELECT IF(d.id > 0, d.dealer, 'North River Boats' ) AS dealer_name FROM wp_users u ";
    $sql .= "LEFT JOIN wp_nrb_dealers d ON u.user_login = d.login ";
    $sql .= "WHERE u.id = %d";
    $response = $wpdb->get_row($wpdb->prepare($sql,array($userid)));
    return $response->dealer_name;
}


function user_to_dealer_group_name($userid) {
    /*
     * wp_info.current_user_id is converted to the dealer_id up to (
     */
    global $wpdb;
    global $debug;

    $sql  = "SELECT IF(d.id > 0, d.dealer_group, 'North River Boats' ) AS dealer_name FROM wp_users u ";
    $sql .= "LEFT JOIN wp_nrb_dealers d ON u.user_login = d.login ";
    $sql .= "WHERE u.id = %d";
    $response = $wpdb->get_row($wpdb->prepare($sql,array($userid)));
    $dealer = $response->dealer_name;
    if (strpos($dealer, " (") ) {
        $dealer = substr($dealer,0, strpos($dealer, " (") );
    }
    return $dealer;
}


/*
function base62encode($data) {
    $outstring = '';
    $l = strlen($data);
    for ($i = 0; $i < $l; $i += 8) {
        $chunk = substr($data, $i, 8);
        $outlen = ceil((strlen($chunk) * 8)/6); //8bit/char in, 6bits/char out, round up
        $x = bin2hex($chunk);  //gmp won't convert from binary, so go via hex
        $w = gmp_strval(gmp_init(ltrim($x, '0'), 16), 62); //gmp doesn't like leading 0s
        $pad = str_pad($w, $outlen, '0', STR_PAD_LEFT);
        $outstring .= $pad;
    }
    return $outstring;
}
 */

function junk1() {
    // set uid for a group of entires
    for ($i=1; $i < 95; $i++) {
        $params = array();
        $params['uid'] = base62encode(random_bytes(9));
        $wpdb->update('wp_nrb_dri',$params,array('id'=>$i));
    }
}



function state_abbr($name, $get = 'abbr') {
    //make sure the state name has correct capitalization:
    if($get != 'name') {
        $name = strtolower($name);
        $name = ucwords($name);
    }else{
        $name = strtoupper($name);
    }
    $states = array(
        'Alabama'=>'AL',
        'Alaska'=>'AK',
        'Arizona'=>'AZ',
        'Arkansas'=>'AR',
        'California'=>'CA',
        'Colorado'=>'CO',
        'Connecticut'=>'CT',
        'Delaware'=>'DE',
        'Florida'=>'FL',
        'Georgia'=>'GA',
        'Hawaii'=>'HI',
        'Idaho'=>'ID',
        'Illinois'=>'IL',
        'Indiana'=>'IN',
        'Iowa'=>'IA',
        'Kansas'=>'KS',
        'Kentucky'=>'KY',
        'Louisiana'=>'LA',
        'Maine'=>'ME',
        'Maryland'=>'MD',
        'Massachusetts'=>'MA',
        'Michigan'=>'MI',
        'Minnesota'=>'MN',
        'Mississippi'=>'MS',
        'Missouri'=>'MO',
        'Montana'=>'MT',
        'Nebraska'=>'NE',
        'Nevada'=>'NV',
        'New Hampshire'=>'NH',
        'New Jersey'=>'NJ',
        'New Mexico'=>'NM',
        'New York'=>'NY',
        'North Carolina'=>'NC',
        'North Dakota'=>'ND',
        'Ohio'=>'OH',
        'Oklahoma'=>'OK',
        'Oregon'=>'OR',
        'Pennsylvania'=>'PA',
        'Rhode Island'=>'RI',
        'South Carolina'=>'SC',
        'South Dakota'=>'SD',
        'Tennessee'=>'TN',
        'Texas'=>'TX',
        'Utah'=>'UT',
        'Vermont'=>'VT',
        'Virginia'=>'VA',
        'Washington'=>'WA',
        'West Virginia'=>'WV',
        'Wisconsin'=>'WI',
        'Wyoming'=>'WY',
        'Alberta'=>'AB',
        'British Columbia'=>'BC',
        'Manitoba'=>'MB',
        'New Brunswick'=>'NB',
        'Newfoundland and Labrador'=>'NL',
        'Nova Scotia'=>'NS',
        'Northwest Territories'=>'NT',
        'Nunavut'=>'NU',
        'Ontario'=>'ON',
        'Prince Edward Island'=>'PE',
        'Quebec'=>'QC',
        'Saskatchewan'=>'SK',
        'Yukon'=>'YT',
        ''=>''
    );
    if($get == 'name') {
        // in this case $name is actually the abbreviation of the state name and you want the full name
        $states = array_flip($states);
    }

    return $states[$name];
}

/**
 * Generate a list of email address to contact at a dealership
 *   the store manager is always added to the list of email addresses
 *
 * @param String $dealer Dealer for whom we want contact information.
 *
 * @param String $role Role at dealerhip we want addresses for
 *
 * @return Sting list of email addresses seperated with ;
 */
function dealerToEmail($nickname, $role) {
    global $wpdb;
    $sql = (
      "SELECT
           nickname,
           email_sales,
           email_parts,
           email_service,
           email_manager,
           email_admin,
           email_warranty
       FROM wp_nrb_dealers
       ORDER BY nickname"
    );
    $response = $wpdb->get_results($sql);
    $mylist = array();
    foreach($response as $item) {
        $manager = explode(" ; ", $item->email_manager);
        $admin = implode(" ; ", array_unique(array_merge(explode(" ; ", $item->email_admin), $manager)));
        $parts = implode(" ; ", array_unique(array_merge(explode(" ; ", $item->email_parts), $manager)));
        $sales = implode(" ; ", array_unique(array_merge(explode(" ; ", $item->email_sales), $manager)));
        $service = implode(" ; ", array_unique(array_merge(explode(" ; ", $item->email_service), $manager)));
        $warranty = implode(" ; ", array_unique(array_merge(explode(" ; ", $item->email_warranty), $manager)));

        $mylist[$item->nickname] = array(
          "Parts" => $parts,
          "Sales" => $sales,
          "Service" => $service,
          "Admin" => $admin,
          "Warranty" => $warranty,
          "Manager" => implode(" ; ", $manager)
        );
    }
    $result = $mylist[$nickname][$role];
    if ($result !== '') {
      if ($nickname !== 'Factory') {
        if ($role == 'Parts') {
          return $result . ' ; kene@northriverboats.com';
        }
        if ($role == 'Sales') {
          return $result . ' ; joed@northriverboats.com ; mikeb@northriverboats.com ; jordana@northriverboats.com';
        }
        return $result . ' ; joed@northriverboats.com ; saral@northriverboats.com';
      }
      return $result;
    }
    return 'fredw@northriverboats.com';
}

function logger_da($message, $level='error') {
    global $loggerDA;
    if ($level = 'error') {
    $level = Analog::ERROR;
    } elseif ($level = 'warning') {
        $level = Analog::WARNING;
    } elseif ($level = 'debug') {
        $level = Analog::DEBUG;
    } elseif ($level = 'urgent') {
        $level = Analog::URGENT;
    } elseif ($level = 'info') {
        $level = Analog::INFO;
    }

    $loggerDA->log ($message, $level);
}


/*
Form mailing
   OPR: saral@northriverboats.com ; stacyd@northriverboats.com ; joed@northriverboats.com
   DRI: jamesg@northriverboats.com ; Store Manager
Survey: saral@northriverboats.com ; stacyd@northriverboats.com ; joed@northriverboats.com
 */
