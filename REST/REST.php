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
$call = $_GET['call'];
$username = $_GET['username'];
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
else if($call == "get_follows_events"){
  $query = 'MATCH (n:user{username: "'.$username.'"})-[:follow]->(u:user)-[:attend]->(m:event) WHERE NOT (n)-[:attend]->(m) RETURN DISTINCT(m)'; 
}
else if($call == "get_rec_events"){
  $query = 'MATCH (n:user{username:"'.$username.'"}),(t1:tag{tag:n.like1}), (t2:tag{tag:n.like2}), (t3:tag{tag:n.like3}) OPTIONAL MATCH (m)-[:tag]->(t1) WHERE NOT (n)-[:attend]->(m) OPTIONAL MATCH (m)-[:tag]->(t2) WHERE NOT (n)-[:attend]->(m) OPTIONAL MATCH (m)-[:tag]->(t3) WHERE NOT (n)-[:attend]->(m) return DISTINCT(m)';
}
else if($call == "get_attend"){
  $query = 'MATCH (:user{username:"'.$username.'"})-[:attend]->(m:event) RETURN m';
}
//$query = 'MATCH (n:user) RETURN n';

//$query = 'MATCH (n:user{username:"bbuilder"})-[:follow]->(m:user) RETURN n,m';
$response = $client->sendCypherQuery($query);
print_r(json_encode($response->getRows()[m]));

