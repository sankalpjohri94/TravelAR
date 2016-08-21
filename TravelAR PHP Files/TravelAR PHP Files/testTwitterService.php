<?php
echo "<h2>Get Recent Tweets of a user</h2>";

require_once('TwitterService.php');
 
// Get Tweets

$settings = array(
    'oauth_access_token' => "4624853774-SsFb0L1UDk0hsJUjDrmTmhrXcE6w7kdvjYHAhbf",
    'oauth_access_token_secret' => "tRv9NZuo3qEl029VhETfTaZYNuBHhXyQwbxd6fTa4PvXq",
    'consumer_key' => "scE83mbvbDxdxTaYKezg4hGxB",
    'consumer_secret' => "s7VHop8D7GepCcejhHBlfu9l8oQ3ySBizHvyMp2SWzoWjJboaY"
);

$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
$requestMethod = "GET";
$screen_name='iamsrk';
$count = 100;
$getfield = 'screen_name='.$screen_name .'&count='.$count;

$twitter = new TwitterAPIExchange($settings);
$string = json_decode($twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
             ->performRequest(),$assoc = TRUE);

if(isset($string["errors"])){
if($string["errors"][0]["message"] != "") {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";exit();}}

//var_dump($string);
$tweets_combined = "";
foreach($string as $items)
{
    $tweets_combined = $tweets_combined.$items['text']."\n";
}
echo $tweets_combined;
?>