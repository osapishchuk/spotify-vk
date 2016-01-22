<html>
<body>
<h1>STEP 1</h1>
<h2><?php echo '<a href="' . $authorize_url . '">Sign in with VK</a>'; ?></h2>
<?php echo 'access token: ' . $access_token['access_token']
    . '<br />expires: ' . $access_token['expires_in'] . ' sec.'
    . '<br />user id: ' . $access_token['user_id'] . '<br /><br />';
?>
</body>
</html>