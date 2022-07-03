<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="./fei.png">
    <link rel="stylesheet" href="./css/style.css">
    <title>Weather</title>
</head>
<body>
<navbar class="navbar">
    <div class="page-title">
        <span class="title-first">weather</span>
        <span class="title-second">cat</span></div>
</navbar>

<div class="main-content">
    <form action="index_page.php" method="post" class="weather-input">
        <label for="city">Enter city:</label>
        <input id="city" name="city" type="text">
        <input class="submit-weather" type="submit" value="Submit">
    </form>
</div>

</body>
</html>