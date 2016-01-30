<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>
<h1>STEP 3</h1>
<ul>
<?php foreach ($songsToImport as $songToImport) { ?>
        <li id="<?php echo $songToImport['aid'];?>">
        <p class="artist">artist: <?php echo $songToImport['artist'];?> </p>
        <p class="title">title: <?php echo $songToImport['title'];?> </p>
        <p class="aid">audio id: <?php echo $songToImport['aid'];?> </p>
        <p class="oid">author id: <?php echo $songToImport['oid'];?> </p>
        <a class="import-button" href="javascript:void()">IMPORT</a>
    </li>
<?php } ?>
</ul>
<?php echo 'access token: ' . $access_token['access_token']
    . '<br />expires: ' . $access_token['expires_in'] . ' sec.'
    . '<br />user id: ' . $access_token['user_id'] . '<br /><br />';
?>