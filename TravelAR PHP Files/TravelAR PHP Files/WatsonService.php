<?php
/**
* Watson PI Service
*/
final class WatsonService {
    private static $inst = null;
    private $auth = "";
    private $wsurl = "";
    public static function Instance() {
        if (self::$inst === null) {
            self::$inst = new WatsonService();
        }
        return self::$inst;
    }
    private function __construct() {
        $parsedUrl = 'https://gateway.watsonplatform.net/personality-insights';//'https://gateway.watsonplatform.net/systemu/service';
        $username = "2921411d-45ef-4269-a63a-8f83f7c5f755";
        $authp = "thanks123";
        #local dummy test
        $password = "PRjwP3FJFeL1";
        if($vcapStr = getenv('VCAP_SERVICES')) {
            $vcap = json_decode($vcapStr, true);
            foreach ($vcap as $serviceTypes) {
                foreach ($serviceTypes as $service) {
                    if($service['name'] == 'user_modeling') {
                        $credentials = $service['credentials'];
                        $username = $credentials['username'];
                        $password = $credentials['password'];
                        $parsedUrl = parse_url($credentials['url']);
                        $host = $parsedUrl['host'];
                        $port = isset($parsedUrl['port']) ?
                        $parsedUrl['port'] : $parsedUrl['scheme'] == 'http' ?
                        '80' : '443';
                        break;
                    }
                }
            }
        }
        $auth = base64_encode($username . ":" . $password);
        $this->auth = $auth;
        $this->wsurl = $parsedUrl . "/api/v2/profile";
    }
    /**
    * Create request for Watson PI service
    */
    public function getInsights($datatext) {
        try {
            $datax = array(
                "contentItems" =>
                array(
                $datarr = array(
                'userid' => 'akshayjindal1994@gmail.com',
                'id' => 'akshayjindal1994@gmail.com',
                'sourceid' => 'freetext',
                'contenttype' => 'text/plain',
                'language' => 'en',
                'content' => $datatext)));
            $data_string = json_encode($datax);
            $curl = curl_init();
//            print "<br>ok<br>";
//            print $this->auth;
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'X-SyncTimeout: 60',
                'Authorization: Basic ' . $this->auth,
                'Content-Length: ' . strlen($data_string))
            );
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_URL, $this->wsurl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($curl);
            if(curl_errno($curl))
            {
                echo 'error:' . curl_error($curl);
            }
//            print_r($result);
            curl_close($curl);
            return $result;
        } catch(Exception $e) {
        echo '<p>There Was an Error Accessing Watson User Modelling Service!!!</p>';
        echo $e->getMessage();
        }
    }
}
?>