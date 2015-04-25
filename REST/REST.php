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
$query_html = '';

if($call == "get_users"){
  $query = 'MATCH (n:user) RETURN n';
}
else if($call == "get_follows"){
  $query_html = 'MATCH (n:user{username: "'.$username.'"})-[:follow]->(m:user) RETURN m';
}
else if($call == "get_follows_events"){
  $query = 'MATCH (n:user{username: "'.$username.'"})-[:follow]->(u:user)-[:attend]->(m:event) WHERE NOT (n)-[:attend]->(m) RETURN DISTINCT(m)'; 
}
else if($call == "get_follows_events_html"){
  $query_html = 'MATCH (n:user{username: "'.$username.'"})-[:follow]->(u:user)-[:attend]->(m:event) WHERE NOT (n)-[:attend]->(m) RETURN DISTINCT(m)'; 
}
else if($call == "get_recommended_events"){
  $query_html = 'MATCH (n:user{username:"'.$username.'"}),(t1:tag{tag:n.like1}), (t2:tag{tag:n.like2}), (t3:tag{tag:n.like3}) OPTIONAL MATCH (m)-[:tag]->(t1) WHERE NOT (n)-[:attend]->(m) OPTIONAL MATCH (m)-[:tag]->(t2) WHERE NOT (n)-[:attend]->(m) OPTIONAL MATCH (m)-[:tag]->(t3) WHERE NOT (n)-[:attend]->(m) return DISTINCT(m)';
}
else if($call == "get_attend"){
  $query = 'MATCH (:user{username:"'.$username.'"})-[:attend]->(m:event) RETURN m';
}
//$query = 'MATCH (n:user) RETURN n';

//$query = 'MATCH (n:user{username:"bbuilder"})-[:follow]->(m:user) RETURN n,m';
if($query !== '' && $query_html === ''){
  $response = $client->sendCypherQuery($query);
  print_r(json_encode($response->getRows()[m]));
}
else if($query_html !== ''){
  $response = $client->sendCypherQuery($query_html)->getRows()[m];
  if(count($response) == 0){
    print_r("The users you are following are currently not planning on attending any events");
  }
  else{
    if($call == 'get_follows_events_html'){
      $html = '';
      foreach($response as $i){
        $html = $html.'<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title">'.$i[title].'</h4></div><div class="panel-body">'.$i[description].'</br>Time: '.$i[time].'</div></div>';
      }
      print_r($html);
    }
    else if($call == 'get_follows'){
      $html = '';
      foreach($response as $i){
        $html = $html.'<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title">'.$i[first].'&nbsp'.$i[last].'</h4></div><div class="panel-body">Likes:<ul class="list-group"><li class="list-group-item">'.$i[like1].'</li><li class="list-group-item">'.$i[like2].'</li><li class="list-group-item">'.$i[like3].'</li></ul></div></div>';
      }
      $html = $html.'~~~~~';
//start of side panel
      $html = $html.'<ul class="list-group">';
      foreach($response as $i){
        $html = $html.'<li id="'.$i[username].'" class="list-group-item hover_name">'.$i[first].'&nbsp'.$i[last].'</li>';
      }
      $html = $html.'</ul>'; //close for side panel
      print_r($html);
    }
    else if($call == 'get_recommended_events'){
      $html = '';
      foreach($response as $i){
        $html = $html.'<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title">'.$i[title].'</h4></div><div class="panel-body">'.$i[description].'</br>Time: '.$i[time].'</br>Location: '.$i[address].'</div></div>'; 
      }
      print_r($html);
    }
  }
//  print_r(json_encode($response));
  return;
/*  $html_build = '';
  html_build 
  html_build += '<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title">';
    html_build += data[i].row[0].title;
    html_build += '</h4></div><div class="panel-body">';
    html_build += data[i].row[0].description;
    html_build += '</br>Time: ';
    html_build += data[i].row[0].time;
    html_build += '</div></div>';
*/
}
