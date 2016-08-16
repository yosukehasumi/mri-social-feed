<?php
class MRISocialFeedInstagram {

  //---------------------------------------------------------------
  public static function scrape_instagram() {
    if(!get_option('ig_enable') != null) return;

    $accounts = get_option('ig_accounts' );

    foreach($accounts as $account){
      $user_id      = $account['ig_user_id'];
      $access_token = $account['ig_access_token'];

      if(!$access_token || !$user_id) continue;

      $url = 'https://api.instagram.com/v1/users/'.$user_id.'/media/recent/?access_token='.$access_token;

      $result = self::curl_to_instagram($url);

      foreach ($result->data as $instagram) {
        self::loop_posts($instagram, $user_id);
      }
    }
  }

  private static function curl_to_instagram($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);

    $result = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($result);
    return $result;
  }

  //---------------------------------------------------------------
  private static function loop_posts($instagram, $user_id){

    if($instagram->type != 'image') return;

    $posts = get_posts(array(
      'meta_key'       => 'instagram_id',
      'meta_value'     => $instagram->id,
      'post_type'      => 'mri_instagram_post',
      'posts_per_page' => -1,
      'post_status'    => 'any'
    ));


    if(count($posts) > 0){
      foreach($posts as $post){
        wp_delete_post( $post->ID, true );
      }
    }

    $post_id = wp_insert_post(array(
      'post_title' => $instagram->id,
      'post_status' => 'publish',
      'post_type'   => 'mri_instagram_post',
      'post_date'   => date_i18n('Y-m-d H:i:s', $instagram->created_time)
    ));

    wp_set_object_terms($post_id, $user_id, 'mri_instagram_category');

    add_post_meta( $post_id, 'instagram_id', $instagram->id, true );
    add_post_meta( $post_id, 'instagram_link', $instagram->link, true );
    add_post_meta( $post_id, 'instagram_image', $instagram->images->low_resolution->url, true );

  }
}
