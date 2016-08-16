<h3>Instagram Settings</h3>
<hr />
<table class="form-table">
  <tr valign="top">
    <th scope="row">Enable Instagram Cron</th>
    <td><input type="checkbox" name="ig_enable" <?php checked( get_option('ig_enable') != null ); ?> /></td>
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
    <td>Access Token <input type="text" data-scope="ig_accounts" data-name="ig_access_token" placeholder="access_token" value="" /></td>
    <td>User Id    <input type="text" data-scope="ig_accounts" data-name="ig_user_id" placeholder="user_id" value="" /></td>
    <td><a href="#" class="mri-repeater-remove-row-trigger">Remove Account</a></td>
  </tr>
  <?php if(get_option('ig_accounts')) : ?>
    <?php foreach(get_option('ig_accounts') as $index => $account) : ?>
      <tr valign="top">
        <td>Access Token <input type="text" name="ig_accounts[<?php echo $index; ?>][ig_access_token]" placeholder="access_token" value="<?php echo esc_attr($account['ig_access_token']); ?>" /></td>
        <td>User Id <input type="text" name="ig_accounts[<?php echo $index; ?>][ig_user_id]" placeholder="user_id" value="<?php echo esc_attr($account['ig_user_id']); ?>" /></td>
        <td><a href="#" class="mri-repeater-remove-row-trigger">Remove Account</a></td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
</table>

