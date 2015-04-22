<?php
//ini_set('display_errors',1);
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

//////////////////////////////////////////////////////////////
$call = $_POST['call'];
$username = $_POST['username'];
//$ret_arr = array('ret'=>"end");
//echo $_POST['a'];
//$ret_array = array('call'=>$call);
//echo json_encode($ret_array);
//echo $_POST['\a'];

$query = '';

if($call == "get_users"){
  $query = 'MATCH (n:user) RETURN n';
}
else if($call == "get_follows"){
  $query = 'MATCH (n:user{username: "'.$username.'"})-[:follow]->(m:user) RETURN m';
}
//$query = 'MATCH (n:user) RETURN n';

//$query = 'MATCH (n:user{username:"bbuilder"})-[:follow]->(m:user) RETURN n,m';
$response = $client->sendCypherQuery($query);
print_r($response->getRows());

