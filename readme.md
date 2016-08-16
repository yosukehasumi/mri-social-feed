MRI Social Feed
===============
This is a crazy simple twitter feed that pulls posts from respective API's and saves them as Wordpress
custom posts. I've added a simple function for pulling these posts from WP, which looks like this:

  mri_social_feed(array(
    'scope' => 'facebook',
    'count' => 5,
    'account' => 'account_name'
  ));

The loop that it outputs can be modified by creating a file called mri-facebook-loop.php (options so far include
"facebook", "instagram", "twitter") in your theme directory.
