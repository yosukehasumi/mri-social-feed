<?php
  $link = get_post_meta( $post->ID, 'twitter_link', true )
?>

<li>
  <div class="header">
    <div class="title"><?php echo $post->post_title; ?> <?php echo mri_time_ago($post->post_date); ?></div>
  </div>
  <div class="content">
    <?php echo $post->post_content; ?>
    <a href="<?php echo $link; ?>" target="_blank" class="float-right view-sm">View in Twitter</a>
  </div>
</li>
