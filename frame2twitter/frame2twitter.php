<?php

if(!$sock = @fsockopen('www.google.com.ar', 80)){
    echo '!!!!!! NO HAY INTERNET !!!!!!';
}else{

	function scan_dir($dir) {
	    $ignored = array('.', '..', '.svn', '.htaccess');

	    $files = array();    
	    foreach (scandir($dir) as $file) {
	        if (in_array($file, $ignored)) continue;
	        $files[$file] = filemtime($dir . '/' . $file);
	    }

	    arsort($files);
	    $files = array_keys($files);

	    return ($files) ? $files : false;
	}
//-
	$files_outputDirectory  = scan_dir('/var/www/html/PostingTwitterApp/video2frame/output/');
	//echo $files_outputDirectory[0];
	$season_ep = explode("-", $files_outputDirectory[0]);
	$seasons = explode("x", $season_ep[1]);
	$ep = $seasons[1];
	$season = $seasons[0];
	//$ep_name = $season_ep[2];
	if(isset($season_ep[3]))
		$ep_name =" - ".substr($season_ep[3], 0, -4);
	else $ep_name = "";

	ini_set('display_errors', 1);
	require_once('TwitterAPIExchange.php');
	        /** Set access tokens here - see: https://dev.twitter.com/apps/ **/ 
	$settings = array(
	            'oauth_access_token' => "oauth_access_token",
	            'oauth_access_token_secret' => "oauth_access_token_secret",
	            'consumer_key' => "API key",
	            'consumer_secret' => "API secret key"
	);
	 
	$twitter = new TwitterAPIExchange($settings);      
	// send image to Twitter first
	$url = 'https://upload.twitter.com/1.1/media/upload.json';
	$requestMethod = 'POST';

	$image = '/var/www/html/PostingTwitterApp/video2frame/output/'.$files_outputDirectory[0];

	$postfields = array(
	  'media_data' => base64_encode(file_get_contents($image))
	);

	$response = $twitter->buildOauth($url, $requestMethod)
	  ->setPostfields($postfields)
	  ->performRequest();

	// get the media_id from the API return
	$media_id = json_decode($response)->media_id;

	// then send the Tweet along with the media ID
	$url = 'https://api.twitter.com/1.1/statuses/update.json';
	$requestMethod = 'POST';

	$status = "ㅤ
	The Simpsons - Season ".$season." Ep. ".$ep.$ep_name;

	$postfields = array(
	  'status' => $status,
	  'media_ids' => $media_id,
	);

	$response = $twitter->buildOauth($url, $requestMethod)
	  ->setPostfields($postfields)
	  ->performRequest();

}

?>
