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
    $search     = $_GET['term'];
    $query	= "SELECT * FROM jv_location WHERE country LIKE '".$search."%' OR city LIKE '".$search."%' LIMIT 5";
    $result	= mysqli_query($mysqli, $query);
    $json       = array();
    while($row= mysqli_fetch_array($result))
    {
        $json[]= array(
        'city'      => $row['city'],
        'country'   => $row['country'],
        'latitude'  => $row['latitude'],
        'longitude' => $row['longitude']);
    }
    
    $data       = json_encode($json);
    echo $data;
}
?>