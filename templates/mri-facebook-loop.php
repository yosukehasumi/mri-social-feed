<?php
  $link = get_post_meta( $post->ID, 'facebook_link', true );
  $date = new DateTime($post->post_date);
?>
<li>
  <div class="header">
    <div class="date"><?php echo $date->format('Y m d'); ?></div>
    <div class="title"><?php echo $post->post_title; ?></div>
  </div>
  <div class="content">
    <?php echo $post->post_content; ?>
    <a href="<?php echo $link; ?>" target="_blank" class="float-right  view-sm">View in Facebook</a>
  </div>
</li>
