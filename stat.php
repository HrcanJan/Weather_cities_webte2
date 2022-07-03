<?php

$servername = "localhost";
$username = "xhrcan";
$password = "SQsBCnIEq5Vnxum";
$dbname = "zad7";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT COUNT(country) as visits, country as country FROM users GROUP BY country");
    $stmt->execute();
    $x = $stmt->fetchAll();
    echo json_encode($x);

} catch (PDOException $e) {
    echo "<br>" . $e->getMessage();
} finally {
    $conn = null;
}