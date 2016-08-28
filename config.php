<?php
require __DIR__ . '/vendor/autoload.php';

use Erpk\Harvester\Client\ClientBuilder;
use Dotenv\Dotenv;

session_start();

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = new Dotenv(__DIR__);
    $dotenv->load();
}

$builder = new ClientBuilder();
$builder->setEmail(getenv('EMAIL'));
$builder->setPassword(getenv('PASSWORD'));

$client = $builder->getClient();

$host = getenv('MYSQL_HOST');
$user = getenv('MYSQL_USER');
$pass = getenv('MYSQL_PASS');
$base = getenv('MYSQL_BASE');
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

//$mysqli = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_BASE'));
$pdo = new PDO("mysql:host=$host;dbname=$base;charset=utf8", $user, $pass, $opt);

date_default_timezone_set('America/Phoenix');