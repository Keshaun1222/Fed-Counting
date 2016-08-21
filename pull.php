<?php
require_once 'config.php';

use Erpk\Harvester\Module\Politics\PoliticsModule;
use Erpk\Harvester\Module\Country\CountryModule;
use Erpk\Common\EntityManager;
use Erpk\Common\Entity\Country;

$date = date("Y-m-d");
$pm = new PoliticsModule($client);
$cm = new CountryModule($client);

$ids = array(2263, 3653, 1501, 2397, 19, 2721, 5061, 3085, 2995, 4497);

$em = EntityManager::getInstance();
$countries = $em->getRepository(Country::class);
$country = $countries->findOneByCode('US');
$society = $cm->getSociety($country);

foreach ($ids as $id) {
    $party = $pm->getParty($id);
    $num = $party['members'];
    $mysqli->query("INSERT INTO count VALUES (NULL, '$date', $id, $num)");
}

$total = $society['active_citizens'];

$mysqli->query("INSERT INTO count VALUES (NULL, '$date', 1, $total)");