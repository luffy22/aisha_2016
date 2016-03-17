<?php
defined('_JEXEC') or die();
function calcDetails($planet)
{
    $details        = explode(":", $planet);
    $sign_num       = intval($details[0]/30);

    switch($sign_num)
    {
        case 0:
        echo "Aries";break;
        case 1:
        echo "Taurus";break;
        case 2:
        echo "Gemini";break;
        case 3:
        echo "Cancer";break;
        case 4:
        echo "Leo";break;
        case 5:
        echo "Virgo";break;
        case 6:
        echo "Libra";break;
        case 7:
        echo "Scorpio";break;
        case 8:
        echo "Sagittarius";break;
        case 9:
        echo "Capricorn";break;
        case 10:
        echo "Aquarius";break;
        case 11:
        echo "Pisces";break;
    }
}
function calcDistance($planet)
{
    $details        = explode(":", $planet);
    $sign_num       = intval($details[0]%30);
    echo $sign_num."&deg;".$details[1]."'";
}
?>

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Horoscope</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li class="active"><a href="#">Planet Details</a></li>
              <li><a href="#">Ascendant</a></li>
              <li><a href="#">Moon</a></li>
              <li><a href="#">Navamsha</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li role="separator" class="divider"></li>
                  <li class="dropdown-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
<div class="spacer"></div>
<h3>User Details</h3>
<table class="table table-striped">
    <tr>
        <th>Name</th>
        <td><?php echo ucfirst($this->data['fname']); ?></td>
    </tr>
    <tr>
        <th>Gender</th>
        <td><?php echo ucfirst($this->data['gender']); ?></td>
    </tr>
    <tr>
        <th>Date Of Birth</th>
        <td><?php echo $this->data['dob']; ?></td>
    </tr>
    <tr>
        <th>Time Of Birth</th>
        <td><?php echo $this->data['tob']; ?></td>
    </tr>
    <tr>
        <th>Latitude</th>
        <td><?php echo $this->data['lat']; ?></td>
    </tr>
    <tr>
        <th>Longitude</th>
        <td><?php echo $this->data['lon']; ?></td>
    </tr>
    <tr>
        <th>Time Zone</th>
        <td>GMT<?php echo $this->data['tmz']; ?></td>
    </tr>
</table>
<div class="spacer"></div>
<h3>Planetary Table</h3>
<div class="table-responsive">
<table class="table table-hover">
    <tr>
        <th>Planets</th>
        <th>Sign</th>
        <th>Distance</th>
    </tr>
    <tr>
        <th>Ascendant</th>
        <td><?php calcDetails($this->data['lagna']); ?></td>
        <td><?php calcDistance($this->data['lagna']); ?></td>
    </tr>
    <tr>
        <th>Sun</th>
        <td><?php calcDetails($this->data['surya']) ?></td>
        <td><?php calcDistance($this->data['surya']); ?></td>
    </tr>
    <tr>
        <th>Moon</th>
        <td><?php calcDetails($this->data['moon']) ?></td>
        <td><?php calcDistance($this->data['moon']); ?></td>
    </tr>
    <tr>
        <th>Mars</th>
        <td><?php calcDetails($this->data['mangal']) ?></td>
        <td><?php calcDistance($this->data['mangal']); ?></td>
    </tr>
    <tr>
        <th>Mercury</th>
        <td><?php calcDetails($this->data['budh']) ?></td>
        <td><?php calcDistance($this->data['budh']); ?></td>
    </tr>
    <tr>
        <th>Jupiter</th>
        <td><?php calcDetails($this->data['guru']) ?></td>
        <td><?php calcDistance($this->data['guru']); ?></td>
    </tr>
    <tr>
        <th>Venus</th>
        <td><?php calcDetails($this->data['shukra']) ?></td>
        <td><?php calcDistance($this->data['shukra']); ?></td>
        
    </tr>
    <tr>
        <th>Saturn</th>
        <td><?php calcDetails($this->data['shani']) ?></td>
        <td><?php calcDistance($this->data['shani']); ?></td>
    </tr>
    <tr>
        <th>Rahu</th>
        <td><?php calcDetails($this->data['rahu']) ?></td>
        <td><?php calcDistance($this->data['rahu']); ?></td>
    </tr>
    <tr>
        <th>Ketu</th>
        <td><?php calcDetails($this->data['ketu']) ?></td>
        <td><?php calcDistance($this->data['ketu']); ?></td>
    </tr>
</table>
</div>
<?php
unset($this->data);
?>