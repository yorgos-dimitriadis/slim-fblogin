<?php 

if( !session_id() ) {
    session_start();
}


/** Define ROOTPATH as this file's directory */
if ( ! defined( 'ROOTPATH' ) ) {
    define( 'ROOTPATH', 'http://127.0.0.1/slim/slim-fblogin/' ); // OnProduction change to dirname( __FILE__ ) . '/' OR  __DIR__
}

require_once 'vendor/autoload.php';

// Include required libraries
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;


try {
    (new Dotenv\Dotenv(__DIR__))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

// Configuration and setup Facebook SDK
$appId          = getenv('FB_APP_ID'); //Facebook App ID
$appSecret      = getenv('FB_APP_SECRET'); //Facebook App Secret
$redirectURL    = 'http://localhost/Slim/slim-fblogin/'; //Callback URL
$fbPermissions  = array('email');  //Optional permissions


$config['displayErrorDetails'] = getenv('APP_DEBUG');
$config['addContentLengthHeader'] = false;

$config['db']['host']   = getenv('DB_HOST');
$config['db']['user']   = getenv('DB_USERNAME');
$config['db']['pass']   = getenv('DB_PASSWORD');
$config['db']['dbname'] = getenv('DB_DATABASE');
$config['db']['charset'] = "utf8";

$app = new \Slim\App(['settings' => $config]);

// Get container
$container = $app->getContainer();

// Use Monolog In Your Application
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

// Add a Database Connection
$container['db'] = function($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], 
                    $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};
 

$fb = new Facebook(array(
    'app_id' => $appId,
    'app_secret' => $appSecret,
    'default_graph_version' => 'v2.11',
));

// Get redirect login helper
$helper = $fb->getRedirectLoginHelper();

// Try to get access token
try {
    
    if( isset($_SESSION['facebook_access_token']) ) {
        $accessToken = $_SESSION['facebook_access_token'];
    } else {
        $accessToken = $helper->getAccessToken();
    }

} catch( FacebookResponseException $e ) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch( FacebookSDKException $e ) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}



// Routes
$app->get('/api', function($request, $response) use ($helper, $redirectURL, $fbPermissions) {

    var_dump($helper->getLoginUrl($redirectURL, $fbPermissions));


});


$app->run();
