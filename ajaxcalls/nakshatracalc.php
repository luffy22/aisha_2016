<?php
$host   = "localhost";$user = "astroxou_admin";
$pwd    = "0sdGXmEtCv9q";$db   = "astroxou_jvidya";$port = "3306";
$mysqli = new mysqli($host, $user, $pwd, $db, $port);
if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
}
else
{
    if(isset($_GET['g_rashi']))
    {
        $g_rashi    = $_GET['g_rashi'];
        $query      = "SELECT DISTINCT girls_nakshatra "."FROM jv_nakshatra_compatibility"." where girls_rashi='$g_rashi'";
        $result     = mysqli_query($mysqli, $query);
        $count      = mysqli_num_rows($result);
    ?>
        <option value="" default="default">Select Nakshatra</option>
<?php
         while($row  = mysqli_fetch_array($result))
            { 
        ?>
                <option value="<?php echo $row['girls_nakshatra']; ?>"><?php echo $row['girls_nakshatra']; ?></option>
        <?php
            }
    }
    else if(isset($_GET['b_rashi']))
    {
        $b_rashi    = $_GET['b_rashi'];
        $query      = "SELECT DISTINCT boys_nakshatra "."FROM jv_nakshatra_compatibility"." where boys_rashi='$b_rashi'";
        $result     = mysqli_query($mysqli, $query);
        $count      = mysqli_num_rows($result);
    ?>
        <option value="" default="default">Select Nakshatra</option>
<?php
         while($row  = mysqli_fetch_array($result))
            { 
        ?>
                <option value="<?php echo $row['boys_nakshatra']; ?>"><?php echo $row['boys_nakshatra']; ?></option>
        <?php
            }
        
    }
    else if((isset($_GET['g_1']))&&(isset($_GET['b_1']))&&
            (isset($_GET['g_2']))&&(isset($_GET['b_2'])))
    {
        $g1             = $_GET['g_1'];
        $g2             = $_GET['g_2'];
        $b1             = $_GET['b_1'];
        $b2             = $_GET['b_2'];
        $query          = "SELECT points FROM jv_nakshatra_compatibility"." where girls_rashi='$g1' AND girls_nakshatra='$g2'
                               AND boys_rashi='$b1' AND boys_nakshatra='$b2'";
        
        $result     = mysqli_query($mysqli, $query);
        $count      = mysqli_num_rows($result);
        if($count>0)
        {
			$query1		= "UPDATE jv_hits_counter SET hits=hits+1 WHERE product='nakshatra_counter'";
            $result1     = mysqli_query($mysqli, $query1);
            while($row=mysqli_fetch_array($result))
            {
                echo $row['points'];
                
            }
        }
        else
        {
            echo "error";
        }
    }
    else
    {
        echo "error";
    }
}
?>
