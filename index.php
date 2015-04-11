<?php
$client_id = 'your_client_id';
$redirect_uri = 'oauth.php';
?>

<html>
    <head>
        <meta name="robots" content="noindex" />
        <title>Import Gmail or Google contacts using PHP, Google Contacts Data API 3.0 and OAuth 2.0</title>
    </head>
    <body>
        <div align="center" >
        	<a  style="font-size:25px;font-weight:bold;" href="https://accounts.google.com/o/oauth2/auth?client_id=<?php echo $client_id;?>&redirect_uri=<?php echo $redirect_uri;?>&scope=https://www.google.com/m8/feeds/&response_type=code">Click here to Import Gmail Contacts</a>
        </div>
    </body>
</html>
