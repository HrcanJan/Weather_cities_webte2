<?php

$servername = "localhost";
$username = "xhrcan";
$password = "SQsBCnIEq5Vnxum";
$dbname = "zad7";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT lat, lon FROM users");
    $stmt->execute();
    $x = $stmt->fetchAll();
    echo json_encode($x);

} catch (PDOException $e) {
    echo "<br>" . $e->getMessage();
} finally {
    $conn = null;
}