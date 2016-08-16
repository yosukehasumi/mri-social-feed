<?php
class MRISocialFeedTwitter {

  //---------------------------------------------------------------
  public static function scrape_twitter() {
    if(!get_option('tw_enable') != null) return;

    $oauth_access_token        = get_option('tw_oauth_access_token');
    $oauth_access_token_secret = get_option('tw_oauth_access_token_secret');
    $consumer_key              = get_option('tw_consumer_key');
    $consumer_secret           = get_option('tw_consumer_secret');
    $accounts                  = get_option('tw_accounts' );

    if( !$oauth_access_token || !$oauth_access_token_secret || !$consumer_key || !$consumer_secret || (count($accounts) == 0) ) return;

    $settings = array(
      'oauth_access_token'        => $oauth_access_token,
      'oauth_access_token_secret' => $oauth_access_token_secret,
      'consumer_key'              => $consumer_key,
      'consumer_secret'           => $consumer_secret
    );

    foreach($accounts as $index => $account){
      $twitter_username = $account['username'];
      $url           = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
      $getfield      = '?screen_name='.$twitter_username.'&count=100&exclude_replies=true&include_rts=true';
      $requestMethod = 'GET';
      $twitter       = new TwitterAPIExchange($settings);
      $response      = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
      foreach(json_decode($response) as $tweet){
        self::loop_tweets($tweet, $twitter_username);
      }
    }
  }

  //---------------------------------------------------------------
  private static function tweet_text($tweet){
    $tweet_text = htmlspecialchars($tweet->text);
    if(isset($tweet->retweeted_status)){
      $tweet_text = htmlspecialchars($tweet->retweeted_status->text);
    }
    $tweet_start_char = substr($tweet_text, 0, 1);
    $tweet_text = preg_replace('/(https?:\/\/[^\s"<>]+)/','<a target="_blank" href="$1">$1</a>', $tweet_text);
    $tweet_text = preg_replace('/(^|[\n\s])@([^\s"\t\n\r<:]*)/is', '$1<a target="_blank" href="http://twitter.com/$2">@$2 </a>', $tweet_text);
    $tweet_text = preg_replace('/(^|[\n\s])#([^\s"\t\n\r<:]*)/is', '$1<a target="_blank" href="http://twitter.com/search?q=%23$2">#$2 </a>', $tweet_text);
    return $tweet_text;
  }
  //---------------------------------------------------------------
  private static function tweet_title($tweet){
    $link = 'https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id;
    $title = '<a href="'.$link.'" target="_blank">'.$tweet->user->name.'</a>';
    if(isset($tweet->retweeted_status)){
      $screen_name = $tweet->retweeted_status->user->screen_name;
      $name        = $tweet->retweeted_status->user->name;
      $title .= ' Retweeted: <a href="'.$link.'" target="_blank">'.$name.' @'.$screen_name.'</a>';
    }
    return $title;
  }

  //---------------------------------------------------------------
  private static function loop_tweets($tweet, $twitter_username){
    $title = self::tweet_title($tweet);
    $id = $tweet->id;
    $link = 'https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id;
    $timestamp = new DateTime($tweet->created_at);
    $timestamp->setTimezone(new DateTimeZone('America/Vancouver'));
    $tweet_text = self::tweet_text($tweet);

    $posts = get_posts(array(
      'meta_key'       => 'twitter_id',
      'meta_value'     => $id,
      'post_type'      => 'mri_twitter_post',
      'posts_per_page' => -1,
      'post_status'    => 'any'
    ));

    if(count($posts) > 0){
      foreach($posts as $post){
        wp_delete_post( $post->ID, true );
      }
    }

    $post_id = wp_insert_post(array(
      'post_title'  => $title,
      'post_status' => 'publish',
      'post_type'   => 'mri_twitter_post',
      'post_date'   => $timestamp->format('Y-m-d H:i:s'),
      'post_content'=> $tweet_text
    ));

    wp_set_object_terms($post_id, $twitter_username, 'mri_twitter_category');

    add_post_meta ( $post_id, 'twitter_id', $id );
    add_post_meta ( $post_id, 'twitter_link', $link );

    if(isset($tweet->entities->media[0])){
      add_post_meta ( $post_id, 'twitter_image', $tweet->entities->media[0]->media_url );
    }
  }

}
