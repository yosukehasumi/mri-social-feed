<?php
/*
Plugin Name:  Medium Rare Social Feed
Plugin URI:
Description:  Seriously boilerplate social feed
Version:      1.0.0
Author:       Yosuke Hasumi
Author URI:   http://mediumrareinc.com
*/
class MRISocialFeed {
  //---------------------------------------------------------------
  public static function init() {
    require_once plugin_dir_path(__FILE__).'includes/Facebook/autoload.php';
    require_once plugin_dir_path(__FILE__).'includes/twitter-api-php/TwitterAPIExchange.php';
    require_once plugin_dir_path(__FILE__).'includes/mri_social_feed_facebook.php';
    require_once plugin_dir_path(__FILE__).'includes/mri_social_feed_twitter.php';
    require_once plugin_dir_path(__FILE__).'includes/mri_social_feed_instagram.php';
    require_once plugin_dir_path(__FILE__).'display-functions.php';

    self::register_post_types();
    self::setup_admin_page();
    self::start_cronjobs();
    flush_rewrite_rules();
  }

  //---------------------------------------------------------------
  public static function deactivate_mri_social_feed() {
    wp_clear_scheduled_hook('scrape_facebook');
    wp_clear_scheduled_hook('scrape_twitter');
    wp_clear_scheduled_hook('scrape_instagram');
    self::delete_posts();
  }
  //---------------------------------------------------------------
  public static function mri_social_feed_flush_cache() {
    self::delete_posts();
    self::scrape_all();
    wp_redirect(admin_url('options-general.php?page=mri-social-feed'));
    exit;
  }
  //---------------------------------------------------------------
  private static function delete_posts() {
    $fb_posts = get_posts(array(
      'post_type' => 'mri_facebook_post',
      'posts_per_page' => -1
    ));
    $tw_posts = get_posts(array(
      'post_type' => 'mri_twitter_post',
      'posts_per_page' => -1
    ));
    $ig_posts = get_posts(array(
      'post_type' => 'mri_instagram_post',
      'posts_per_page' => -1
    ));

    $fb_terms = get_terms('mri_facebook_category', array('hide_empty' => false));
    $tw_terms = get_terms('mri_twitter_category', array('hide_empty' => false));
    $ig_terms = get_terms('mri_instagram_category', array('hide_empty' => false));

    foreach ($fb_posts as $post) wp_delete_post( $post->ID, true);
    foreach ($tw_posts as $post) wp_delete_post( $post->ID, true);
    foreach ($ig_posts as $post) wp_delete_post( $post->ID, true);

    if ( count($fb_terms) > 0 ){
      foreach ( $fb_terms as $term ) wp_delete_term( $term->term_id, 'mri_facebook_category' );
    }
    if ( count($tw_terms) > 0 ){
      foreach ( $tw_terms as $term ) wp_delete_term( $term->term_id, 'mri_twitter_category' );
    }
    if ( count($ig_terms) > 0 ){
      foreach ( $ig_terms as $term ) wp_delete_term( $term->term_id, 'mri_instagram_category' );
    }
  }
  //---------------------------------------------------------------
  private static function scrape_all() {
    MRISocialFeedFacebook::scrape_facebook();
    MRISocialFeedTwitter::scrape_twitter();
    MRISocialFeedInstagram::scrape_instagram();
  }

  //---------------------------------------------------------------
  private static function register_post_types() {

    register_post_type( 'mri_facebook_post', array(
      'public' => ( get_option('debug') == 'on' ? true : false ),
      'menu_icon' => 'dashicons-star-empty',
      'label'  => 'Facebook',
      'hierarchical' => true,
      'capability_type' => 'post',
      'supports' => array('title', 'editor', 'custom-fields'),
      'taxonomies' => array('mri_facebook_category'),
    ));
    register_post_type( 'mri_twitter_post', array(
      'public' => ( get_option('debug') == 'on' ? true : false ),
      'menu_icon' => 'dashicons-star-empty',
      'label'  => 'Twitter',
      'hierarchical' => true,
      'capability_type' => 'post',
      'supports' => array('title', 'editor', 'custom-fields'),
      'taxonomies' => array('mri_twitter_category'),
    ));
    register_post_type( 'mri_instagram_post', array(
      'public' => ( get_option('debug') == 'on' ? true : false ),
      'menu_icon' => 'dashicons-star-empty',
      'label'  => 'Instagram',
      'hierarchical' => true,
      'capability_type' => 'post',
      'supports' => array('title', 'editor', 'custom-fields'),
      'taxonomies' => array('mri_instagram_category'),
    ));
    register_taxonomy('mri_facebook_category', 'mri_facebook_post', array(
        'label' => 'Accounts',
        'hierarchical' => true,
      )
    );
    register_taxonomy('mri_twitter_category', 'mri_twitter_post', array(
        'label' => 'Accounts',
        'hierarchical' => true,
      )
    );
    register_taxonomy('mri_instagram_category', 'mri_instagram_post', array(
        'label' => 'Accounts',
        'hierarchical' => true,
      )
    );
  }
  //---------------------------------------------------------------
  private static function setup_admin_page(){
    add_action('admin_menu', 'register_mri_social_feed_settings_page');
    add_action('admin_init', 'register_mri_social_feed_plugin_settings' );
    add_action('admin_enqueue_scripts', 'mri_social_feed_enqueue' );

    function mri_social_feed_enqueue($hook) {
      if ( 'settings_page_mri-social-feed' != $hook ) return;
      wp_enqueue_style(  'mri-social-feed-admin-css', plugin_dir_url(__FILE__) . 'css/mri-social-feed.css' );
      wp_enqueue_script( 'mri-social-feed-admin-js', plugin_dir_url(__FILE__) . 'js/mri-social-feed.js' );
    }

    function register_mri_social_feed_settings_page(){
      add_submenu_page( 'options-general.php', 'MRI Social Feed', 'MRI Social Feed', 'manage_options', 'mri-social-feed', 'mri_social_feed_settings_page_html' );
    }

    function register_mri_social_feed_plugin_settings(){
      register_setting( 'mri_social_feed_settings_group', 'debug' );

      register_setting( 'mri_social_feed_settings_group', 'fb_enable' );
      register_setting( 'mri_social_feed_settings_group', 'fb_app_id' );
      register_setting( 'mri_social_feed_settings_group', 'fb_app_secret' );
      register_setting( 'mri_social_feed_settings_group', 'fb_default_access_token' );
      register_setting( 'mri_social_feed_settings_group', 'fb_accounts' );

      register_setting( 'mri_social_feed_settings_group', 'tw_enable' );
      register_setting( 'mri_social_feed_settings_group', 'tw_oauth_access_token' );
      register_setting( 'mri_social_feed_settings_group', 'tw_oauth_access_token_secret' );
      register_setting( 'mri_social_feed_settings_group', 'tw_consumer_key' );
      register_setting( 'mri_social_feed_settings_group', 'tw_consumer_secret' );
      register_setting( 'mri_social_feed_settings_group', 'tw_accounts' );

      register_setting( 'mri_social_feed_settings_group', 'ig_enable' );
      register_setting( 'mri_social_feed_settings_group', 'ig_accounts' );
    }

    function mri_social_feed_settings_page_html(){
      require_once plugin_dir_path(__FILE__ ).'includes/admin-page.php';
    }
  }
  //---------------------------------------------------------------
  private static function start_cronjobs(){

    if ( ! wp_next_scheduled( 'scrape_facebook' ) ) wp_schedule_event( time(), 'hourly', 'scrape_facebook' );
    add_action( 'scrape_facebook', array( 'MRISocialFeedFacebook', 'scrape_facebook') );

    if ( ! wp_next_scheduled( 'scrape_twitter' ) ) wp_schedule_event( time(), 'hourly', 'scrape_twitter' );
    add_action( 'scrape_twitter', array( 'MRISocialFeedTwitter', 'scrape_twitter') );

    if ( ! wp_next_scheduled( 'scrape_twitter' ) ) wp_schedule_event( time(), 'hourly', 'scrape_twitter' );
    add_action( 'scrape_twitter', array( 'MRISocialFeedTwitter', 'scrape_twitter') );
  }
}

add_action( 'init', array( 'MRISocialFeed', 'init' ) );
add_action( 'admin_post_mri_social_feed_flush_cache', array( 'MRISocialFeed', 'mri_social_feed_flush_cache' ) );
register_deactivation_hook( __FILE__, array( 'MRISocialFeed', 'deactivate_mri_social_feed' ) );

