<?php

    require_once('./TaxiFareFinderService.php');
    $tffInstance = new TaxiFareFinderService;
    $tffInstance->findOriginCoordinates('Bangalore Airport');
    $tffInstance->findDestCoordinates('Kumarakom 3 Star Hotel bangalore');
    $tffInstance->setCity('BLR');
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
    }
?>