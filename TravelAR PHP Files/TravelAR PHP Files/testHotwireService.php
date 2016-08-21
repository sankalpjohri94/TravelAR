<?php
$destination = 'bangalore';
$url = "http://api.hotwire.com/v1/deal/hotel?dest='.$destination.'&apikey=aw5m8w3ndp7auzy66vsyhunv&limit=5";
//$myXMLData = file_get_contents();
//print_r($myXMLData);
//$xml=simplexml_load_string($myXMLData) or die("Error: Cannot create object");

$curl_handle=curl_init();
curl_setopt($curl_handle, CURLOPT_URL,$url);
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
$query = curl_exec($curl_handle);
curl_close($curl_handle);

$xml=simplexml_load_string($query) or die("Error: Cannot create object");

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
?>