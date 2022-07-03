<?php

$servername = "localhost";
$username = "xhrcan";
$password = "SQsBCnIEq5Vnxum";
$dbname = "zad7";

try {
    $arr = [];

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT COUNT(*) as visits FROM users WHERE HOUR(date) >= 6 AND HOUR(date) < 15;");
    $stmt->execute();
    $x = $stmt->fetchAll();
    $arr[] = $x;

    $stmt = $conn->prepare("SELECT COUNT(*) as visits FROM users WHERE HOUR(date) >= 15 AND HOUR(date) < 21;");
    $stmt->execute();
    $x = $stmt->fetchAll();
    $arr[] = $x;

    $stmt = $conn->prepare("SELECT COUNT(*) as visits FROM users WHERE HOUR(date) >= 21;");
    $stmt->execute();
    $x = $stmt->fetchAll();
    $arr[] = $x;

    $stmt = $conn->prepare("SELECT COUNT(*) as visits FROM users WHERE HOUR(date) <= 6;");
    $stmt->execute();
    $x = $stmt->fetchAll();
    $arr[] = $x;

    echo json_encode($arr);

} catch (PDOException $e) {
    echo "<br>" . $e->getMessage();
} finally {
    $conn = null;
}