<?php
  $scheme = $_SERVER['REQUEST_SCHEME'];
  $server = $_SERVER['SERVER_NAME'];
  $protocol = $scheme . '://';

  $public_dir = '/RussellE-demo';

  $base_url = $protocol . $server . $public_dir;
  $assets_url = $protocol . $server . $public_dir . '/assets';
  $admin_assets_url = $protocol . $server . $public_dir . '/admin/assets';

  $root_dir = dirname(__DIR__, 1);

?>
