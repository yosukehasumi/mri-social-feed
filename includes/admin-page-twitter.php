<h3>Twitter Settings</h3>
<hr />
<table class="form-table">
  <tr valign="top">
    <th scope="row">Enable Twitter Cron</th>
    <td><input type="checkbox" name="tw_enable" <?php checked( get_option('tw_enable') != null ); ?> /></td>
  </tr>

  <tr valign="top">
    <th scope="row">Oauth Access Token</th>
    <td><input type="text" name="tw_oauth_access_token" placeholder="oauth_access_token" value="<?php echo esc_attr( get_option('tw_oauth_access_token') ); ?>" /></td>
  </tr>

  <tr valign="top">
    <th scope="row">Oauth Access Token Secret</th>
    <td><input type="text" name="tw_oauth_access_token_secret" placeholder="oauth_access_token_secret" value="<?php echo esc_attr( get_option('tw_oauth_access_token_secret') ); ?>" /></td>
  </tr>

  <tr valign="top">
    <th scope="row">Consumer Key</th>
    <td><input type="text" name="tw_consumer_key" placeholder="consumer_key" value="<?php echo esc_attr( get_option('tw_consumer_key') ); ?>" /></td>
  </tr>

  <tr valign="top">
    <th scope="row">Consumer Secret</th>
    <td><input type="text" name="tw_consumer_secret" placeholder="consumer_secret" value="<?php echo esc_attr( get_option('tw_consumer_secret') ); ?>" /></td>
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
    <th scope="row">Username</th>
    <td><input type="text" data-name="username" data-scope="tw_accounts" placeholder="username" value="" /></td>
    <td><a href="#" class="mri-repeater-remove-row-trigger">Remove Account</a></td>
  </tr>
  <?php if(get_option('tw_accounts')) : ?>
    <?php foreach(get_option('tw_accounts') as $index => $account) : ?>
      <tr valign="top">
        <th scope="row">Username</th>
        <td><input type="text" name="tw_accounts[<?php echo $index; ?>][username]" placeholder="username" value="<?php echo esc_attr($account['username']); ?>" /></td>
        <td><a href="#" class="mri-repeater-remove-row-trigger">Remove Account</a></td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
</table>

