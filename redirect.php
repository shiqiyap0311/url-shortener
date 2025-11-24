<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to MySQL
$mysqli = new mysqli("localhost", "root", "", "url_shortener");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_GET['c'])) {
    $code = $_GET['c'];

    $stmt = $mysqli->prepare("SELECT long_url FROM urls WHERE code = ?");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $stmt->bind_result($url);
    $stmt->fetch();

    if ($url) {
        header("Location: " . $url);
        exit();
    } else {
        echo "Invalid short link.";
    }
}
?>