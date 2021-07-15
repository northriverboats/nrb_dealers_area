<?php

    /*
    Plugin Name: NRB Dealers Area
    Plugin URI: http://www.elder-geek.net/
    Description: Load VueJS applications on wordpress pages
    Author: Fredrick W. Warren
    Version: 0.2
    Author URI: http://www.home.elder-geek.net/
    */

    /*  Copyright 2021 elder-geek.net

        This program is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published by
        the Free Software Foundation; either version 2 of the License, or
        (at your option) any later version.

        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.
     */

    // load api file
    include_once (plugin_dir_path(__FILE__). "/api.php");

    // load js css
    function nrb_dealers_area_shortcode( $atts, $content = null )
    {
        $exists = file_exists(plugin_dir_path(__FILE__) . "/dist/static");
        if ($exists) {
          wp_enqueue_style("nrb_dealers_area-css");
          wp_enqueue_script("nrb_dealers_area-manifest");
          wp_enqueue_script("nrb_dealers_area-vendor");
          wp_enqueue_script("nrb_dealers_area-app");
        } else {
          wp_enqueue_style("nrb_dealers_area-css");
          wp_enqueue_style("nrb_dealers_area-chunk-vendors-css");
          wp_enqueue_script("nrb_dealers_area-vendor");
          wp_enqueue_script("nrb_dealers_area-app");
          wp_enqueue_script("nrb_dealers_area-chunk-vendors");
        }
        $content = '<div id="app" style="-ms-transform: scale(1.5, 1.5); -webkit-transform: scale(1.5, 1.5); transform: scale(1.5, 1.5);"></div>';
        return $content;
    }

    // Our Shortcode Action
    add_shortcode( 'nrb_dealers_area', 'nrb_dealers_area_shortcode' );

    // Register the Stylesheet so it's ready to go.
    function nrb_dealers_area_enqueue_scripts()
    {
      $dir = plugin_dir_path(__FILE__);

      if (file_exists($dir."/dist/static")) {
          foreach(glob($dir."/dist/static/css/app*.css") as $filename) {
              wp_register_style("nrb_dealers_area-css", plugins_url(substr($filename,strlen($dir)),__FILE__), array(), null, false);
          }
          foreach(glob($dir."/dist/static/js/manifest*.js") as $filename) {
              wp_register_script("nrb_dealers_area-manifest", plugins_url(substr($filename,strlen($dir)),__FILE__), array(), null, true);
          }
          foreach(glob($dir."/dist/static/js/vendor*.js") as $filename) {
              wp_register_script("nrb_dealers_area-vendor", plugins_url(substr($filename,strlen($dir)),__FILE__), array(), null, true);
          }
          foreach(glob($dir."/dist/static/js/app*.js") as $filename) {
              wp_register_script("nrb_dealers_area-app", plugins_url(substr($filename,strlen($dir)),__FILE__), array(), null, true);
          }
          wp_register_style("nrb_dealers_area-css", plugins_url("{$dir}/dist/static/css/app.css",__FILE__), array(), null, false);
          wp_register_script("nrb_dealers_area-manifest", plugins_url("{$dir}/dist/static/js/manifest.js",__FILE__), array(), null, true);
          wp_register_script("nrb_dealers_area-vendor", plugins_url("{$dir}/dist/static/js/vendor.js",__FILE__), array(), null, true);
      } else {
          foreach(glob($dir."/dist/css/app.*.css") as $filename) {
              wp_register_style("nrb_dealers_area-css", plugins_url(substr($filename,strlen($dir)),__FILE__), array(), null, false);
            }
            foreach(glob($dir."/dist/css/chunk-vendors.*.css") as $filename) {
                wp_register_style("nrb_dealers_area-chunk-vendors-css", plugins_url(substr($filename,strlen($dir)),__FILE__), array(), null, false);
            }
            foreach(glob($dir."/dist/js/chunk-vendors.*.js") as $filename) {
                wp_register_script("nrb_dealers_area-chunk-vendors", plugins_url(substr($filename,strlen($dir)),__FILE__), array(), null, true);
            }
            foreach(glob($dir."/dist/js/app.*.js") as $filename) {
                wp_register_script("nrb_dealers_area-app", plugins_url(substr($filename,strlen($dir)),__FILE__), array(), null, true);
            }
      }
      wp_localize_script("nrb_dealers_area-app", 'wp_info', array(
        'rest_url' => esc_url_raw( rest_url() ),
        'nonce' => wp_create_nonce( 'wp_rest' ),
        'success' => __( 'Post submitted', "nrbv_dealers_area-app" ),
        'failure' => __( 'Post could not be processed.', "nrb_dealers_area-app" ),
        'current_user_id' => get_current_user_id())
      );
    }

// Enqueue Stylesheet Action
add_action( 'wp_enqueue_scripts', 'nrb_dealers_area_enqueue_scripts' );  // registered so it can be loaded on page with short code
