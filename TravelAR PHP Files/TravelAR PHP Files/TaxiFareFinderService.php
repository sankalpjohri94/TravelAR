<?php

class TaxiFareFinderService {
    
    private $origin_lat = 0.0;
    private $origin_long = 0.0;
    private $dest_lat = 0.0;
    private $dest_long = 0.0;
    private $city = 'xxx';
    
    public function findOriginCoordinates($address){
        $prepAddr = str_replace(' ','+',$address);
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,'http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'TravelAR');
        $query = curl_exec($curl_handle);
        curl_close($curl_handle);
        $output = json_decode($query);
        
        $this->origin_lat = $output->results[0]->geometry->location->lat;
        $this->origin_long = $output->results[0]->geometry->location->lng;
    }
    
    public function findDestCoordinates($address){
        $prepAddr = str_replace(' ','+',$address);
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,'http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'TravelAR');
        $query = curl_exec($curl_handle);
        curl_close($curl_handle);
        $output = json_decode($query);
        $this->dest_lat = $output->results[0]->geometry->location->lat;
        $this->dest_long = $output->results[0]->geometry->location->lng;
    }
    
    public function setCity($handle){
        if($handle==='BLR')
            $this->city = 'Bangalore-India';
        else if($handle==='MAA')
            $this->city = 'Chennai-India';
        else if($handle==='HYD')
            $this->city = 'Hyderabad-India';
        else if($handle==='CCU')
            $this->city = 'Kolkata-West-Bengal-India';
        else if($handle==='BOM')
            $this->city = 'Mumbai-India';
        else if($handle==='DEL')
            $this->city = 'New-Delhi-India';
        else
            $this->city = -1;
    }
    
    public function findCabFares(){
        if($this->city===-1)
            return -1;
        $url = 'http://api.taxifarefinder.com/fare?key=NanuthUHa7he&entity_handle='.$this->city.'&origin='.$this->origin_lat.','.$this->origin_long.'&destination='.$this->dest_lat.','.$this->dest_long;
        $url = str_replace(' ','',$url);
        
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,$url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'TravelAR_cab');
        $query = curl_exec($curl_handle);
        curl_close($curl_handle);
        $json = json_decode($query, true);
        if($json['status'] === 'OK')
            return $json;
        return -1;
    }
    
    public function findCabCompanies(){
        if($this->city===-1)
            return -1;
        $url = 'http://api.taxifarefinder.com/businesses?key=NanuthUHa7he&entity_handle='.$this->city;
        $url = str_replace(' ','',$url);
//        var_dump($url);
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,$url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'TravelAR_cab');
        $query = curl_exec($curl_handle);
        curl_close($curl_handle);
        $json = json_decode($query, true);
        if($json['status'] === 'OK')
            return $json;
        return -1;
    }
}
?>