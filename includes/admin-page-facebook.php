<h3>Facebook Settings</h3>
<hr />
<table class="form-table">
  <tr valign="top">
    <th scope="row">Enable FB Cron</th>
    <td><input type="checkbox" name="fb_enable" <?php checked( get_option('fb_enable') != null ); ?> /></td>
  </tr>

  <tr valign="top">
    <th scope="row">App ID</th>
    <td><input type="text" name="fb_app_id" placeholder="app_id" value="<?php echo esc_attr( get_option('fb_app_id') ); ?>" /></td>
  </tr>

  <tr valign="top">
    <th scope="row">App Secret</th>
    <td><input type="text" name="fb_app_secret" placeholder="app_secret" value="<?php echo esc_attr( get_option('fb_app_secret') ); ?>" /></td>
  </tr>

  <tr valign="top">
    <th scope="row">Default Access Token</th>
    <td><input type="text" name="fb_default_access_token" placeholder="default_access_token" value="<?php echo esc_attr( get_option('fb_default_access_token') ); ?>" /></td>
  </tr>
</table>

<br/><br/><br/>

<h3>Accounts </h3>
<hr />
<table class="form-table mri-repeater">
  <tr>
    <td><a href="#" class="mri-repeater-add-row-trigger">Add Account</a></td>
  </tr>
  <tr valign="top" class="template-row">
    <th scope="row">Pagename</th>
    <td><input type="text" data-name="fb_accounts[]" placeholder="pagename" value="" /></td>
    <td><a href="#" class="mri-repeater-remove-row-trigger">Remove Account</a></td>
  </tr>
  <?php if(get_option('fb_accounts')) : ?>
    <?php foreach(get_option('fb_accounts') as $index => $pagename) : ?>
      <tr valign="top">
        <th scope="row">Pagename</th>
        <td><input type="text" name="fb_accounts[<?php echo $index; ?>]" placeholder="pagename" value="<?php echo esc_attr($pagename); ?>" /></td>
        <td><a href="#" class="mri-repeater-remove-row-trigger">Remove Account</a></td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
</table>

