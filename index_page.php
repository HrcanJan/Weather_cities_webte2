<!-- https://phppot.com/php/forecast-weather-using-openweathermap-with-php/ -->
<?php

$servername = "localhost";
$username = "xhrcan";
$password = "SQsBCnIEq5Vnxum";
$dbname = "zad7";

if(!isset($_POST['city']))
    header("Location: index.php");

$apiKey = "298fb95c533124264d1662edb5a7e946";
$cityName = $_POST['city'];
$googleApiUrl = "https://api.openweathermap.org/data/2.5/weather";

$params = array(
    'q' => $cityName,
    'lang' => 'en',
    'units' => 'metric',
    'APPID' => $apiKey
);

$googleApiUrl = $googleApiUrl . '?' . http_build_query($params);

$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);

curl_close($ch);
$data = json_decode($response);
//var_dump($data);

$timezone = $data->timezone;
$currentDate = date('Y-m-d H:i:s', time() + $timezone);
$currentTime = time() + $timezone;

if ($data->name == NULL)
    header("Location: index.php");

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("INSERT INTO users (ip, date, city, country, lat, lon)
                            VALUES(\"" . $_SERVER['REMOTE_ADDR'] . "\",
                            \"".$currentDate."\", \"" . $data->name . "\", \"" . $data->sys->country . "\", 
                            ".$data->coord->lat.", ".$data->coord->lon.")");
    $stmt->execute();

} catch (PDOException $e) {
    echo "<br>" . $e->getMessage();
} finally {
    $conn = null;
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="./fei.png">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
          integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
          crossorigin=""/>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link rel="stylesheet" href="css/leaflet-routing-machine.css"/>
    <title>WEATHERcat</title>
</head>

<img>
<navbar class="navbar">
    <div class="page-title">
        <span class="title-first">weather</span>
        <span class="title-second">cat</span></div>
    <li class="navbar-urls">
        <a href="index.php">
        <ul class="navbar-element">home</ul></a>
        <div id="mainb" onclick="clickMain()">
            <ul class="navbar-element">weather</ul>
        </div>
        <div id="infob" onclick="clickInfo()">
            <ul class="navbar-element">info</ul>
        </div>
        <div id="statsb" onclick="clickStats()">
            <ul class="navbar-element">statistics</ul>
        </div>
    </li>
</navbar>

<div id="name"><?php echo $data->name?></div>
<div id="code"><?php echo $data->sys->country?></div>
<div id="lon"><?php echo $data->coord->lon?></div>
<div id="lat"><?php echo $data->coord->lat?></div>

<div id="main">
    <div class="report-container">
        <h2><?php echo $data->name ." ".$data->sys->country; ?> Weather Status</h2>
        <div class="time">
            <div><?php echo date("l g:i a", $currentTime); ?></div>
            <div><?php echo date("jS F, Y",$currentTime); ?></div>
            <div><?php echo ucwords($data->weather[0]->description); ?></div>
        </div>
        <div class="weather-forecast">
            <img
                    src="http://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png"
                    class="weather-icon" alt="img"/>Max: <?php echo $data->main->temp_max; ?>°C Min:<span
                    class="min-temperature"><?php echo $data->main->temp_min; ?>°C</span>
        </div>
        <div class="time">
            <div>Humidity: <?php echo $data->main->humidity; ?> %</div>
            <div>Wind: <?php echo $data->wind->speed; ?> km/h</div>
        </div>
    </div>
</div>

<div id="info">
    <div id="map" class="map"></div>

    <table id="table1" class="marginbottom">
        <thead>
        <tr>
            <th>City</th>
            <th>Country</th>
            <th>Capital</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<div id="stats">
    <div id="map2" class="map"></div>

    <table id="table2" class="marginbottom">
        <thead>
        <tr>
            <th>Flag</th>
            <th>Country</th>
            <th>Visits</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    <div id="tableGang">

    </div>

    <table id="table3" class="marginbottom">
        <thead>
        <tr>
            <th>Time</th>
            <th>Visits</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
<script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
<script src="js/leaflet-routing-machine.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="js/script.js"></script>
</body>
</html>