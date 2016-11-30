   <?php
   include_once "/home/astroxou/php/Net/GeoIP.php";

		$geoip = Net_GeoIP::getInstance("/home/astroxou/php/Net/GeoLiteCity.dat");

		try {
			$ip			= $_SERVER['REMOTE_ADDR'];
			
			$location 		= $geoip->lookupLocation($ip);
			print_r($location);exit;
			echo "Country: ".$location->countryName."<br/>";
			echo "City: ".$location->city."<br/>";
			echo "Latitude: ".$location->latitude."<br/>";
			echo "Longitude: ".$location->longitude."<br/>";
		} catch (Exception $e) {
			echo "Data Not Showing Up: ".$e->getMessage();
		}


?>
