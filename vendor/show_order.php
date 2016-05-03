<?php
include_once('bootstrap.php');
use PayPal\Api\RedirectUrls;
$baseUrl = getBaseUrl();

if ((isset($_POST['submit']))&&($_POST['submit']=='yes'))
{
    $username = trim($_POST['user']);
    $passwd = sha1(trim($_POST['pwd']));
    $time   = date('Y-m-d H:i:s');
    $date   = new DateTime($time);
    $date   ->add(new DateInterval('PT0H30M0S'));
    $valid  = $date->format('Y-m-d H:i:s');
    $secure_id      = uniqid('user_');
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
        $sql = "UPDATE jv_paypal_auth SET session_id='".$secure_id."', valid_until='".$valid."' 
                where user='".$username."' AND password='".$passwd."'";
        $result	= mysqli_query($mysqli, $sql);
        if($result)
        {

            $query  = "SELECT session_id, valid_until FROM jv_paypal_auth where user='luffy22'";
            $data       = mysqli_query($mysqli,$query);
            $assoc      = mysqli_fetch_assoc($data);
            $session_id = $assoc['session_id'];
            $valid      = strtotime($assoc['valid_until']);
            $current    = strtotime(date('Y-m-d H:i:s'));
            if(isset($session_id) && ($valid > $current))
            {
               
               $query1   = "SELECT jv_questions.UniqueID as uniq_id, jv_questions.name as name, jv_questions.email as email, 
                            jv_paypal_info.paypal_id as pay_id, jv_paypal_info.status as status,jv_paypal_info.authorize_id as auth_id FROM jv_questions
                            INNER JOIN jv_paypal_info ON jv_questions.UniqueID = jv_paypal_info.UniqueID WHERE jv_questions.payment_type='paypal'";
               $data1    = mysqli_query($mysqli,$query1);
               $rows     = mysqli_num_rows($data1);
?>
<div class="container">
            <div class="table-responsive">
        <table class="table table-striped">
            <tr>
                <th>Unique ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Paypal ID</th>
                <th>Status</th>
                <th>Authorize</th>
                <th>Capture Order</th>
                <th>Cancel Order</th>
            </tr>
<?php
               while($assoc1    = mysqli_fetch_array($data1))
               {
?>
            <tr>
                <td><?php echo $assoc1['uniq_id'] ?></td>
                <td><?php echo $assoc1['name'] ?></td>
                <td><?php echo $assoc1['email'] ?></td>
                <td><?php echo $assoc1['pay_id'] ?></td>
                <td><?php echo $assoc1['status'] ?></td>
                <td><?php echo $assoc1['auth_id'] ?></td>
                <td><button class="btn btn-success" onclick="javascript:capture(<?php echo $assoc1['auth_id'] ?>);">Capture</button></td>
                <td><button class="btn btn-danger" onclick="javascript:cancel(<?php echo $assoc1['pay_id'] ?>);">Cancel</button></td>
            </tr>
<?php
               }
 ?>
                    </table>
    </div>
</div>
 <?php
            }
        }
        else
        {
            echo "Update failed";
        }
    }
}
else
{
    header('Location: '.$baseUrl.'/check_cred.php');
}
?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../templates/astroisha2.0/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" type="text/css" href="../templates/astroisha2.0/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../templates/astroisha2.0/css/style.css" />
    <link rel="stylesheet" type="text/css" href="../templates/astroisha2.0/css/template.css" />
    <script type="text/javascript">
        function capture()
        {
            alert("capture");
        }
        function cancel()
        {
            alert("cancel");
        }
    </script>
</head>
<body>
    <form id="capture_form" method="post" action="<?php echo $baseUrl ?>/auth_capture.php"
</body>
</html>