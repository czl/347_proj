<?php
ini_set('display_errors',1);
require_once 'vendor/autoload.php';

use Neoxygen\NeoClient\ClientBuilder;

//$conn_url = parse_url('http://104.131.68.36:7474/db/data/');
$user = 'neo4j';
$pass = 'clemens';

$client = ClientBuilder::create()
  ->addConnection('default','http','104.131.68.36',7474,true,$user,$pass)
  ->setDefaultTimeout(10)
  ->build();

$vrsn = $client->getNeo4jVersion();
echo $vrsn;
//echo 'done';
