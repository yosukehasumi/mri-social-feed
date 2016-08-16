<?php
  function mri_social_feed($args) {
    if($args['scope']){
      $scope     = $args['scope'];
      $count     = ($args['count'] ? $args['count'] : 3);
      $tax_query = array();
      switch($scope){
        case 'facebook' :
          $post_type = 'mri_facebook_post';
          if($args['account']){
            $tax_query = array(array(
              'taxonomy' => 'mri_facebook_category',
              'field'    => 'slug',
              'terms'    => $args['account'],
            ));
          }
        break;
        case 'twitter' :
          $post_type = 'mri_twitter_post';
          if($args['account']){
            $tax_query = array(array(
              'taxonomy' => 'mri_twitter_category',
              'field'    => 'slug',
              'terms'    => $args['account'],
            ));
          }
        break;
        case 'instagram' :
          $post_type = 'mri_instagram_post';
          if($args['account']){
            $tax_query = array(array(
              'taxonomy' => 'mri_instagram_category',
              'field'    => 'slug',
              'terms'    => $args['account'],
            ));
          }
        break;
      }

      $mri_social_media_posts = get_posts(array(
        'post_type'      => $post_type,
        'posts_per_page' => $count,
        'orderby'        => 'date',
        'tax_query'      => $tax_query
      ));

      foreach($mri_social_media_posts as $post){
        if(file_exists(get_template_directory().'/mri-'.$scope.'-loop.php')){
          include get_template_directory().'/mri-'.$scope.'-loop.php';
        }else{
          include plugin_dir_path(__FILE__).'templates/mri-'.$scope.'-loop.php';
        }
      }
    }
  }
  //---------------------------------------------------------------
  function mri_time_ago($date,$granularity=2) {
    $date = strtotime($date);
    $difference = time() - $date;
    $periods = array(
      'decade' => 315360000,
      'year' => 31536000,
      'month' => 2628000,
      'week' => 604800,
      'day' => 86400,
      'hour' => 3600,
      'minute' => 60,
      'second' => 1
    );
    if ($difference < 5) { // less than 5 seconds ago, let's say "just now"
      $retval = "posted just now";
      return $retval;
    } else {
      $retval = "";
      foreach ($periods as $key => $value) {
        if ($difference >= $value) {
          $time = floor($difference/$value);
          $difference %= $value;
          $retval .= $time;
          // $retval .= ($retval ? ' ' : '').$time.' ';
          // $retval .= (($time > 1) ? $key.'s' : $key);
          $retval .= substr($key, 0, 1);
          // $granularity--;
          break;
        }
        if ($granularity == '0') { break; }
      }
      return $retval;
    }
  }
