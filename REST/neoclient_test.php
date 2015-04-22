<?php
/*
Note to use this, need to install composer, then make composer.json file, then 'install composer' in the project directory
*/

ini_set('display_errors',1);
require_once '../vendor/autoload.php';

use Neoxygen\NeoClient\ClientBuilder;

//$conn_url = parse_url('http://104.131.68.36:7474/db/data/');
$user = 'neo4j';
$pass = 'clemens';

$client = ClientBuilder::create()
  ->addConnection('default','http','104.131.68.36',7474,true,$user,$pass)
  ->setDefaultTimeout(10)
  ->setAutoFormatResponse(true)
  ->build();

//$vrsn = $client->getNeo4jVersion();
//$root = $client->getRoot();

$query = 'MATCH (n:user{username:"bbuilder"})-[:follow]->(m:user) RETURN n,m';
$response = $client->sendCypherQuery($query);
print_r($response);
