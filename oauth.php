<?php
@ini_set("log_errors", "On");
@ini_set("display_errors", "On");
@ini_set("error_log", "errors.log");
?>
<html>
    <head>
        <meta name="robots" content="noindex" />
        <title>Email address list - Import Gmail or Google contacts</title>
    </head>
    <body>

        <div style="padding-left: 50px;">
<?php
$client_id = 'your_client_id';
$client_secret = 'your_client_secret';
$redirect_uri = 'path_to_oauth_dot_php';
$max_results = 1000;

//get authorization code as a response of the link we clicked in index.php
$auth_code = $_GET["code"];

$fields=array(
    'code'=>  urlencode($auth_code),
    'client_id'=>  urlencode($client_id),
    'client_secret'=>  urlencode($client_secret),
    'redirect_uri'=>  urlencode($redirect_uri),
    'grant_type'=>  urlencode('authorization_code')
);
$post = '';
foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
$post = rtrim($post,'&');

//-Exchange code for token
$curl = curl_init();
curl_setopt($curl,CURLOPT_URL,'https://accounts.google.com/o/oauth2/token');
curl_setopt($curl,CURLOPT_POST,5);
curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
$result = curl_exec($curl);
curl_close($curl);

$response =  json_decode($result);
//-Save token response
$accesstoken = $response->access_token;

//-Use token response to call Google API
$url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results='.$max_results.'&oauth_token='.$accesstoken;
$xmlresponse =  curl_file_get_contents($url);

//-The modification starts here 
$salt = md5($_SERVER['REMOTE_ADDR'].time()); //to enable multiple simultaneous imports with low probability of file conflicts
$temp_file = "temp_".$salt.".xml";
file_put_contents($temp_file, $xmlresponse);
$xml_re = simplexml_load_file($temp_file);
$name = ""; $email = "";
foreach($xml_re->children() as $child) {
 
		$child = (array)$child;
		if(isset($child['name']) && isset($child['email'])){
		   $name  = $child['name'];
		   $email = $child['email'];
		   break;	
		}
}

echo "<hr>name = ".$name." | email = ".$email."<hr>"; 
unlink($temp_file);
//-The modification ends here 

if((strlen(stristr($xmlresponse,'Authorization required'))>0) && (strlen(stristr($xmlresponse,'Error '))>0))
{
	echo "<h2>OOPS !! Something went wrong. Please try reloading the page.</h2>";
	exit();
}
 
echo "<h3>Email Addresses:</h3>";
$xml =  new SimpleXMLElement($xmlresponse);

$xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
$result = $xml->xpath('//gd:email');

foreach ($result as $title) {
  echo $title->attributes()->address . "<br>";
}


function curl_file_get_contents($url)
{
 $curl = curl_init();
 $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
 
 curl_setopt($curl,CURLOPT_URL,$url);	//The URL to fetch. This can also be set when initializing a session with curl_init().
 curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
 curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);	//The number of seconds to wait while trying to connect.	
 
 curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);	//The contents of the "User-Agent: " header to be used in a HTTP request.
 curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);	//To follow any "Location: " header that the server sends as part of the HTTP header.
 curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);	//To automatically set the Referer: field in requests where it follows a Location: redirect.
 curl_setopt($curl, CURLOPT_TIMEOUT, 10);	//The maximum number of seconds to allow cURL functions to execute.
 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);	//To stop cURL from verifying the peer's certificate.
 curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
 
 $contents = curl_exec($curl);
 curl_close($curl);
 return $contents;
}
?>
</div>
</body></html>
