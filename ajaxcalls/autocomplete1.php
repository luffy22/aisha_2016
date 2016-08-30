<?php
header('Content-type: application/json');
$host   = "localhost";$user = "root";
$pwd    = "desai1985";$db   = "astroisha";
$mysqli = new mysqli($host, $user, $pwd, $db);
/* check connection */
if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
}
else
{
    $search     = ucfirst($_GET['term']);
    $query	= "SELECT * FROM jv_location WHERE city LIKE '$search%'  LIMIT 5";
    $result	= mysqli_query($mysqli, $query);
    while($row  = mysqli_fetch_array($result))
    {
        $city       = $row['city'];
        $country    = $row['country'];
        $state      = $row['state'];
                
        $json[]     = array('label'=>$city, 'country'=>$country, 'state'=>$state);
   }

    $data       = json_encode($json);
    echo $data;
}
?>
