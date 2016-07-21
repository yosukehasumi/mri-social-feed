<?php
class MRISocialFeedFacebook {

  //---------------------------------------------------------------
  public static function scrape_facebook() {
    if(!get_option('fb_enable') != null) return;

    $app_id                = get_option('fb_app_id');
    $app_secret            = get_option('fb_app_secret');
    $default_graph_version = 'v2.6';
    $default_access_token  = get_option('fb_default_access_token');
    $accounts              = get_option('fb_accounts');

    if( !$app_id || !$app_secret || !$default_graph_version || !$default_access_token || (count($accounts) == 0) ) return;

    $fb = new Facebook\Facebook([
      'app_id'                => $app_id,
      'app_secret'            => $app_secret,
      'default_graph_version' => $default_graph_version,
      'default_access_token'  => $default_access_token,
    ]);

    foreach($accounts as $facebook_pagename){
      try {
        $response = $fb->get('/'.$facebook_pagename.'/posts');
      } catch(Facebook\Exceptions\FacebookResponseException $e) {
        error_log('Graph returned an error: ' . $e->getMessage());
        exit;
      } catch(Facebook\Exceptions\FacebookSDKException $e) {
        error_log('Facebook SDK returned an error: ' . $e->getMessage());
        exit;
      }

      try {
        $page = $fb->get('/'.$facebook_pagename);
      } catch(Facebook\Exceptions\FacebookResponseException $e) {
        error_log('Graph returned an error: ' . $e->getMessage());
        exit;
      } catch(Facebook\Exceptions\FacebookSDKException $e) {
        error_log('Facebook SDK returned an error: ' . $e->getMessage());
        exit;
      }

      $graphEdge = $response->getGraphEdge();
      $getGraphObject = $page->getGraphObject();
      foreach($graphEdge as $graphNode){
        self::loop_facebook($graphNode, $getGraphObject, $facebook_pagename);
      }
    }
  }
  //---------------------------------------------------------------
  private static function loop_facebook($graphNode, $getGraphObject, $facebook_pagename){
    $id = $graphNode['id'];
    $link = 'https://facebook.com/'.str_replace('_', '/posts/', $id);
    $title = (isset($graphNode['story']) ? $graphNode['story'] : $getGraphObject['name'] );
    $title = preg_replace('/(https?:\/\/[^\s"<>]+)/','<a target="_blank" href="$1">$1</a>', $title);
    $title = preg_replace('/(^|[\n\s])#([^\s"\t\n\r<:]*)/is', '$1<a target="_blank" href="https://www.facebook.com/hashtag/$2">#$2</a>', $title);
    $timestamp = $graphNode['created_time'];
    $timestamp->setTimezone(new DateTimeZone('America/Vancouver'));
    $content = htmlspecialchars($graphNode['message']);
    $content = preg_replace('/(https?:\/\/[^\s"<>]+)/','<a target="_blank" href="$1">$1</a>', $content);
    $content = preg_replace('/(^|[\n\s])#([^\s"\t\n\r<:]*)/is', '$1<a target="_blank" href="https://www.facebook.com/hashtag/$2">#$2</a>', $content);

    $posts = get_posts(array(
      'meta_key'    => 'facebook_id',
      'meta_value'  => $id,
      'post_type'   => 'mri_facebook_post',
      'posts_per_page' => 1
    ));

    if(count($posts) == 0){
      $post_id = wp_insert_post(array(
        'post_title'  => $title,
        'post_status' => 'publish',
        'post_type'   => 'mri_facebook_post',
        'post_date'   => $timestamp->format('Y-m-d H:i:s'),
        'post_content'=> $content
      ));
      if(!add_post_meta( $post_id, 'facebook_id', $id, true ) ) {
        update_post_meta ( $post_id, 'facebook_id', $id );
      }
    }else{
      $post = $posts[0];
      $post->post_title   = $title;
      $post->post_date    = $timestamp->format('Y-m-d H:i:s');
      $post->post_content = $content;
      $post_id = wp_update_post( $post );
    }

    wp_set_object_terms($post_id, $facebook_pagename, 'mri_facebook_category');
    if(!add_post_meta( $post_id, 'facebook_link', $link, true ) ) {
      update_post_meta ( $post_id, 'facebook_link', $link );
    }
  }
}
