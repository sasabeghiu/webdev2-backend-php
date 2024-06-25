<?php
$url = getenv('DATABASE_URL');
$url = parse_url($url);

$type = "pgsql";
$servername = $url["host"];
$port = $url["port"];
$username = $url["user"];
$password = $url["pass"];
$database = ltrim($url["path"], '/');

$dsn = "$type:host=$servername;port=$port;dbname=$database";

try {
    $pdo = new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}
