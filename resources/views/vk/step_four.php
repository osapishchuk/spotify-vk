<h1>STEP 3</h1>
<?php echo 'access token: ' . $access_token['access_token']
    . '<br />expires: ' . $access_token['expires_in'] . ' sec.'
    . '<br />user id: ' . $access_token['user_id'] . '<br /><br />';
?>
<?php var_dump($songsImported); ?>
<?php var_dump($resErrors); ?>