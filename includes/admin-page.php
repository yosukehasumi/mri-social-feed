<div class="wrap">
  <h2>MRI Social Media Feeds</h2>
  <form method="post" action="options.php">
    <?php settings_fields( 'mri_social_feed_settings_group' ); ?>
    <?php do_settings_sections( 'mri_social_feed_settings_group' ); ?>

    <div class="mri-tab-titles">
      <div class="mri-tab-title active" data-id="1">Facebook</div>
      <div class="mri-tab-title" data-id="2">Twitter</div>
      <div class="mri-tab-title" data-id="3">Instagram</div>
    </div>
    <div class="mri-tab-contents">
      <div class="mri-tab-content active" data-id="1"><?php require_once plugin_dir_path(__FILE__).'admin-page-facebook.php'; ?></div>
      <div class="mri-tab-content" data-id="2"><?php require_once plugin_dir_path(__FILE__).'admin-page-twitter.php'; ?></div>
      <div class="mri-tab-content" data-id="3"><?php require_once plugin_dir_path(__FILE__).'admin-page-instagram.php'; ?></div>
    </div>

    <?php submit_button(); ?>
  </form>

  <form action="<?php echo admin_url( 'admin-post.php' ); ?>">
    <input type="hidden" name="action" value="mri_social_feed_flush_cache">
    <?php submit_button( 'Flush Cache' ); ?>
  </form>

</div>
