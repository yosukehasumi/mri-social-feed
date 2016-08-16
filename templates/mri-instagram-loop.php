<?php
  $link = get_post_meta( $post->ID, 'instagram_link', true );
  $image = get_post_meta( $post->ID, 'instagram_image', true );
  $date = new DateTime($post->post_date);
?>
<li>
  <div class="header">
    <div class="date"><?php echo $date->format('Y m d'); ?></div>
  </div>
  <div class="content">
    <img src="<?php echo $image; ?>" />
    <a href="<?php echo $link; ?>" target="_blank" class="float-right  view-sm">View in Instagram</a>
  </div>
</li>
