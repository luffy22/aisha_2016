    <?php
     try
     {
		  //$ip    = '212.58.244.20';
		print geoip_database_info(GEOIP_COUNTRY_EDITION);
		}
		catch(Exception $e)
		{
			echo "Data Not Showing: ".$e->getError();
		}

?>
