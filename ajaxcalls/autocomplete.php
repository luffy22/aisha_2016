<?php
header('Content-type: application/json');
$host   = "localhost";$user = "root";
$pwd    = "desai1985";$db   = "astroisha";
$mysqli = new mysqli("localhost", "root", "desai1985", "astroisha");
/* check connection */
if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
}
else
{
    $search     = ucfirst($_GET['term']);
    $query	= "SELECT * FROM jv_location WHERE city LIKE '$search%' OR state LIKE '$search%' LIMIT 5";
    $result	= mysqli_query($mysqli, $query);
    while($row  = mysqli_fetch_array($result))
    {
        if($row['state'] == 'none')
        {
            $city       = $row['city'].", ".$row['country'];
        }
        else
        {
            $city       = $row['city'].", ".$row['state'].", ".$row['country'];
        }
        $lat        = $row['latitude'];
        $lon        = $row['longitude'];
        $tmz        = $row['timezone'];
        if($tmz == 'no')
        {
            $date = new DateTime();
            $timestamp  =  $date->getTimestamp();
            $key          = AIzaSyAI5nTdtm8iZay-aXDPVanNVHl1QsyVHdA;
            $url          = "https://maps.googleapis.com/maps/api/timezone/json?location=".$lat.','.$lon.'&timestamp='.$timestamp.'&key='.$key;
            $result       = file_get_contents($url);
            
            $json[]       = array('label'=>$city, 'lat'=>$lat, 'lon'=>$lon,'tmz'=>$result);
        }
        else
        {
            $json[]     = array('label'=>$city, 'lat'=>$lat, 'lon'=>$lon,'tmz'=>$tmz);
        }
    }

    $data       = json_encode($json);
    echo $data;
}
?>
