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

$mysqli = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_BASE'));

date_default_timezone_set('America/Phoenix');

$fedsID = 2263;