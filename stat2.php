<?php

$servername = "localhost";
$username = "xhrcan";
$password = "SQsBCnIEq5Vnxum";
$dbname = "zad7";

//if(isset($_GET['code'])) {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT city, COUNT(city) as count FROM users WHERE country = \"" . $_GET['code'] . "\"
                                      GROUP BY city ");
        $stmt->execute();
        $x = $stmt->fetchAll();
        echo json_encode($x);

    } catch (PDOException $e) {
        echo "<br>" . $e->getMessage();
    } finally {
        $conn = null;
    }
//}