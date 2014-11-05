<?php
$host   = "localhost";$user = "root";
$pwd    = "desai1985";$db   = "astroisha";$port = "3306";
$mysqli = new mysqli($host, $user, $pwd, $db, $port);
if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
}
if(isset($_GET['country']))
{
    $country = $_GET['country'];
    $query   = "SELECT latitude, longitude FROM jv_location WHERE country='$country' AND timezone='no'";
    $result     = mysqli_query($mysqli, $query);
    $count      = mysqli_num_rows($result);
    $date = date_create();
    $timestamp =  date_timestamp_get($date);
    $row        = mysqli_fetch_array($result);
    echo $row['latitude']."|".$row['longitude']."|".$timestamp;
    
}
?>
