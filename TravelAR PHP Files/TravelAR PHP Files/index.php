<?php

if($_GET['type'] === 'location')
{
/***** Location Search *****/
//    echo "Location Search<br>";
    // Twitter Feed
//    echo "Getting Twitter Feed<br>";
    $user_id = $_GET['twitter_id'];
    require_once('TwitterService.php');
    $settings = array(
    'oauth_access_token' => "4624853774-SsFb0L1UDk0hsJUjDrmTmhrXcE6w7kdvjYHAhbf",
    'oauth_access_token_secret' => "tRv9NZuo3qEl029VhETfTaZYNuBHhXyQwbxd6fTa4PvXq",
    'consumer_key' => "scE83mbvbDxdxTaYKezg4hGxB",
    'consumer_secret' => "s7VHop8D7GepCcejhHBlfu9l8oQ3ySBizHvyMp2SWzoWjJboaY"
    );

    $url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
    $requestMethod = "GET";
    $count = 100;
    $getfield = 'screen_name='.$user_id .'&count='.$count;
    $twitter = new TwitterAPIExchange($settings);
    $string = json_decode($twitter->setGetfield($getfield)
                 ->buildOauth($url, $requestMethod)
                 ->performRequest(),$assoc = TRUE);
    if(isset($string["errors"])){
    if($string["errors"][0]["message"] != "") {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";exit();}}

    $tweets_feed = "";
    foreach($string as $items)
    {
        $tweets_feed = $tweets_feed.$items['text']."\n";
    }
    
    
    // Watson Personailty Insight
//    echo "Getting Watson PI<br>";
    require_once('./WatsonService.php');
    $results = WatsonService::Instance()->getInsights($tweets_feed);
    /********** Parsing JSON in Personality traits map ******************/
    $traits = array();
    $json = json_decode($results, true);
    foreach($json['tree']['children'][0]['children'][0]['children'] as  $mydata)
    { 
        foreach($mydata['children'] as $temp)
        {
            $traits[$temp['name']] = $mydata['percentage'];
        }
    }
    foreach($json['tree']['children'][1]['children'][0]['children'] as  $mydata)
    {
        $traits[$mydata['name']] = $mydata['percentage'];    
    }
    foreach($json['tree']['children'][2]['children'][0]['children'] as  $mydata)
    {
        $traits[$mydata['name']] = $mydata['percentage'];    
    }
    
    /************ Classfying theme and choosing lifestyle **************/
    
    $beach = ($traits['Adventurousness'] + 1 - $traits['Activity level'] + $traits['Excitement-seeking'] + $traits['Outgoing'] + $traits['Hedonism'])/5.0 ;
    $caribbean = ($traits['Adventurousness'] + 1 - $traits['Activity level'] + $traits['Self-enhancement'] + $traits['Outgoing'] + $traits['Curiosity'])/5.0 ;
    $disney = ($traits['Artistic interests'] + $traits['Imagination'] + $traits['Emotionality'] + $traits['Cheerfulness'] + $traits['Excitement'])/5.0;
    $gambling = ($traits['Adventurousness'] + $traits['Intellect'] + $traits['Authority-challenging'] + $traits['Achievement striving'] + $traits['Challenge'])/5.0;
    $historic = ($traits['Artistic interests'] + $traits['Intellect'] + $traits['Curiosity'] + $traits['Self-transcendence'] + $traits['Conservation'])/5.0;
    $mountains = ($traits['Adventurousness'] + $traits['Achievement striving'] + $traits['Self-discipline'] + $traits['Excitement-seeking'] + $traits['Outgoing'])/5.0;
    $national_parks = ($traits['Cautiousness'] + $traits['Conservation'] + $traits['Practicality'] + $traits['Stability'] + $traits['Structure'])/5.0;
    $outdoors =  ($traits['Outgoing'] + 1 - $traits['Activity level'] + $traits['Liberty'] + $traits['Openness to change'] + $traits['Gregariousness'])/5.0;
    $romantic = ($traits['Emotionality'] + $traits['Trust'] + $traits['Love'] + $traits['Harmony'] + $traits['Ideal'])/5.0;
    $shopping = ($traits['Emotionality'] + $traits['Uncompromising'] + $traits['Self-consciousness'] + $traits['Self-expression'] + $traits['Self-enhancement'])/5.0;
    $skiing = ($traits['Adventurousness'] + $traits['Fiery'] + $traits['Self-discipline'] + $traits['Excitement-seeking'] + $traits['Outgoing'])/5.0;
    $themePark = ($traits['Artistic interests'] + $traits['Imagination'] + $traits['Emotionality'] + $traits['Self-expression'] + $traits['Self-enhancement'])/5.0;
    $budget = ($traits['Modesty'] + $traits['Practicality'] + $traits['Openness to change'] + 1 - $traits['Uncompromising'] + 1 - $traits['Stability'])/5.0;
    
    $themes_available = array('BEACH','CARIBBEAN','DISNEY','GAMBLING','HISTORIC','MOUNTAINS','NATIONAL-PARKS','OUTDOORS','ROMANTIC','SHOPPING','SKIING','THEME-PARK');
    $themes_weights = array($beach,$caribbean,$disney,$gambling,$historic,$mountains,$national_parks,$outdoors,$romantic,$shopping,$skiing,$themePark);
    $value = max($themes_weights);
    $key = array_search($value, $themes_weights);
    
//    var_dump($themes_weights);
//    var_dump($budget);
    
    $theme = $themes_available[$key];
    $lifestyle = 'BUDGET';
    if($budget<0.7)
        $lifestyle = 'LUXURY';
    
//    echo "Theme = ".$theme."<br>";
//    echo $lifestyle."<br>";
    
    // Sabre Top Destinations
    
    ini_set('display_errors', 1);
    $url='https://api.test.sabre.com/v1/shop/flights?mode=live';
    $origin = $_GET['origin'];
    $url = 'https://api.test.sabre.com/v1/lists/top/destinations?origin='.$origin.'&destinationtype=DOMESTIC&theme='.$theme.'&topdestinations=10&destinationcountry=IN&region=ASIA+PACIFIC&lookbackweeks=8';
    $reffer="https://api.test.sabre.com";
    $agent ="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3";
    $header[]='Authorization: Bearer T1RLAQIiVmNKye0cQHlK6GXlWg5zuW8lABDI+plZmMrmGJEG/DqIQmUvAACgWMAWhRKnf1MdRoLaWLKRaK+FMmNVX6khUE+SXrvHFxxHausDDlLDqc+aHKUmFb3iKtl9EISzwTiqwUY9vbWQ0+riuuZLGVmqBEiOssykl/ag3zX6qdUU8AvmrB/fn13VtYLpMw1xYrx3xNnAwepayHpDd7+ICipYsXYCRoPlflZNYAKZwFJ02tyRTtsMnUJlXyUaHXCYjrVMZx+ezKHZMw**
X-Originating-Ip: 209.126.116.134';

    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING,'gzip');
    curl_setopt($ch, CURLOPT_REFERER, $reffer);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $content = curl_exec($ch);
    $json = json_decode($content,true);
    

    foreach($json['Destinations'] as $item)
    {
        echo $item['Destination']['CityName'].",".$item['Destination']['DestinationLocation']."#";
    }
    
}
else if($_GET['type'] === 'flight')
{
/***** Flight Search *******/
//    echo "Filght Search<br>";
    // Twitter Feed
    // Watson Personailty Insight
    $theme = 'SHOPPING';
    $style = 'BUDGET';
    // Sabre Flight Fares
    ini_set('display_errors', 1);
    
    $url='https://api.test.sabre.com/v1/shop/flights?mode=live';

    $origin = $_GET['origin'];
    $destination = $_GET['destination'];
    $day = $_GET['day'];
    $departure_date = date('Y-m-d', strtotime('next '.$day));
    $length_of_stay = '2%2C3';

    $url = 'https://api.test.sabre.com/v2/shop/flights/fares?origin='.$origin.'&destination='.$destination.'&lengthofstay='.$length_of_stay.'&departuredate='.$departure_date.'&pointofsalecountry=IN';
    
//    var_dump($url);
    
    $reffer="https://api.test.sabre.com";
    $agent ="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3";

    $header[]='Authorization: Bearer T1RLAQIiVmNKye0cQHlK6GXlWg5zuW8lABDI+plZmMrmGJEG/DqIQmUvAACgWMAWhRKnf1MdRoLaWLKRaK+FMmNVX6khUE+SXrvHFxxHausDDlLDqc+aHKUmFb3iKtl9EISzwTiqwUY9vbWQ0+riuuZLGVmqBEiOssykl/ag3zX6qdUU8AvmrB/fn13VtYLpMw1xYrx3xNnAwepayHpDd7+ICipYsXYCRoPlflZNYAKZwFJ02tyRTtsMnUJlXyUaHXCYjrVMZx+ezKHZMw**
X-Originating-Ip: 209.126.116.134';

    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING,'gzip');
    curl_setopt($ch, CURLOPT_REFERER, $reffer);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $content = curl_exec($ch);

    $json = json_decode($content,true);

//    var_dump($json);

    foreach($json['FareInfo'] as $item)
    {
        echo $item['LowestFare']['AirlineCodes'][0].",";
        echo $item['LowestFare']['Fare']." INR,";
//        echo $item['LowestNonStopFare']['AirlineCodes'][0]."<br>";
//        echo $item['LowestNonStopFare']['Fare']." INR<br>";
        echo substr($item['DepartureDateTime'],0,10).",";
        echo substr($item['ReturnDateTime'],0,10)."#";
    }
    
}
else if($_GET['type'] === 'hotel')
{
/***** Hotel Search ********/
//    echo "Hotel Search<br>".$_GET['destination'];
    // Twitter Feed
    // Watson Personailty Insight
    $theme = 'SHOPPING';
    $style = 'BUDGET';
    // Hotwire Hotel Information
    $destination = $_GET['destination'];
    $myXMLData = file_get_contents("http://api.hotwire.com/v1/deal/hotel?dest='.$destination.'&apikey=aw5m8w3ndp7auzy66vsyhunv&limit=5");
    $xml=simplexml_load_string($myXMLData) or die("Error: Cannot create object");

    $hotels = (array)$xml->Result[0][0][0];

    foreach((array)$xml->Result[0][0] as $item)
    {
        foreach((array)$item as $info)
        {
            echo $info->Headline."+";
            echo $info->Price." ".$info->CurrencyCode."+";
            echo $info->StarRating."#";
        }
    }
    
}
else if($_GET['type'] === 'cab')
{
//    echo "MeruCabs:4422 4422#EasyCabs:040 4343 4343#OlaCabs:33553355#TFS:60601010#1-866-576-1039";
/***** Cab Information *****/
    require_once('./TaxiFareFinderService.php');
    $tffInstance = new TaxiFareFinderService;
    $origin = str_replace('_',' ',$_GET['origin']);
    $destination = str_replace('_',' ',$_GET['destination']);
    $city = $_GET['city'];
    $tffInstance->findOriginCoordinates($origin);
    //'Kumarakom 3 Star Hotel bangalore'
    $tffInstance->findDestCoordinates($destination);
    $tffInstance->setCity($city);
    $json = $tffInstance->findCabFares();
    if($json==-1)
        echo "-1";
    else{
        $fare = $json['total_fare'];
        $distance = (int) ($json['distance']/1000);
        $duration = (int) ($json['duration']/60);
        echo $fare."#".$distance."#".$duration."#";
        $services = $tffInstance->findCabCompanies();
        if($services!==-1){
            foreach($services['businesses'] as $item)
                echo $item['name'].':'.$item['phone'].'#';
        }
        else
             echo "MeruCabs:4422 4422#EasyCabs:040 4343 4343#OlaCabs:33553355#TFS:60601010#1-866-576-1039";
    }
}
else
{
    echo "Wrong URL: check your type<br>";
}
?>