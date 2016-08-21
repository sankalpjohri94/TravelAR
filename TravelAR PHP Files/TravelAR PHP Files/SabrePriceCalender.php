<?php
ini_set('display_errors', 1);

$url='https://api.test.sabre.com/v1/shop/flights?mode=live';

$origin = 'DEL';
$destination = 'BLR';
$departure_date = date('Y-m-d', strtotime('next friday'));;
$length_of_stay = '2%2C3';

$url = 'https://api.test.sabre.com/v2/shop/flights/fares?origin='.$origin.'&destination='.$destination.'&lengthofstay='.$length_of_stay.'&departuredate='.$departure_date.'&pointofsalecountry=IN';

$reffer="https://api.test.sabre.com";
$agent ="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3";

$header[]='Authorization: Bearer T1RLAQLdC8w9eDbwM/v3dHF33wVfBch16BCipqZs4chdchsAdqHnPQfwAACg+gpenzWin6opj8GUOxBiUrdeZA6Ohlq3s7dyhxgWSN4WIWmG4gL+PcWYu1KY4IIc/LAMCuaiAyWnhGO/w9zgqUDMOlt8JDPlvVxSEROxvacE7is9tgxLpheYzHWfK5tX/aK3IytnpqtZ+tG6OY4b6gWuVYkp8cpzgiANnWUsiPv7opjAkBuc9lcdlvr3Wz+D/GuGF9mc+IINt2Gv0RrumQ**
X-Originating-Ip: 14.139.155.24';

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

ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
var_dump($json);

foreach($json['FareInfo'] as $item)
{
    echo $item['LowestFare']['AirlineCodes'][0]."<br>";
    echo $item['LowestFare']['Fare']." INR<br>";
    echo $item['LowestNonStopFare']['AirlineCodes'][0]."<br>";
    echo $item['LowestNonStopFare']['Fare']." INR<br>";
    echo substr($item['DepartureDateTime'],0,10)."<br>";
    echo substr($item['ReturnDateTime'],0,10)."<br>";
    
}

//foreach($json['Destinations'] as $item)
//{
//    echo $item['Destination']['CityName']." ".$item['Destination']['DestinationLocation']."<br>";
//}


?>