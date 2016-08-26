<?php
    // OAUTH Configuration
    $oauthClientID = '600279212436-aeqmsrgb16vhg3c7hpqm5780fs5etcis.apps.googleusercontent.com';
    $oauthClientSecret = 'eJmXzyiKxLE2qZIeNqb7jnC1';
    $baseUri = 'http://ec2-52-78-20-118.ap-northeast-2.compute.amazonaws.com/admin/uploadContent';
    $redirectUri = 'http://ec2-52-78-20-118.ap-northeast-2.compute.amazonaws.com/admin/modifyContentProc';
    
    define('OAUTH_CLIENT_ID',$oauthClientID);
    define('OAUTH_CLIENT_SECRET',$oauthClientSecret);
    define('REDIRECT_URI',$redirectUri);
    define('BASE_URI',$baseUri);
    
    // Include google client libraries
    require_once 'Google/src/Google/autoload.php'; 
    session_start();
    if(!isset($_SESSION['user'])){
    	echo "<script>alert('You're not Authorized. Please check.') \n";
    	echo "window.location = '/admin'";
    	echo "</script>";
    }
    $client = new Google_Client();
    $client->setClientId(OAUTH_CLIENT_ID);
    $client->setClientSecret(OAUTH_CLIENT_SECRET);
    $client->setScopes('https://www.googleapis.com/auth/youtube');
    $client->setRedirectUri(REDIRECT_URI);
    // Define an object that will be used to make all API requests.
    $youtube = new Google_Service_YouTube($client);
?>