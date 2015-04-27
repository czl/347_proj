<?php
//ini_set('display_errors',1);
require_once '../vendor/autoload.php';

//echo "in REST.php";
//exit();

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

//---- CREATING $_PUT
if($_SERVER['REQUEST_METHOD'] == 'PUT'){
  parse_str(file_get_contents("php://input"),$_PUT);
  foreach($_PUT as $key => $value){
    unset($_PUT[$key]);
    $_PUT[str_replace('amp;', '', $key)] = $value;
  }
  $_REQUEST = array_merge($_REQUEST, $_PUT);
}

///end of creating $_PUT
$call = '';
$call = $_GET['call'];
if($call == ''){
  $call = $_PUT['call'];
}
$username = $_GET['username'];
$this_username = '';
$other_username = '';
$eid = '';
if($call == 'put_follow' || $call=='put_unfollow'){
  $this_username = $_PUT['this_username'];
  $other_username = $_PUT['other_username'];
}
else if($call == 'put_attend' || $call=='put_unattend'){
  $this_username = $_PUT['this_username'];
  $eid = $_PUT['eid'];
}
$query = '';
$query_html = '';

if($call == "get_users"){
  $query = 'MATCH (n:user) RETURN n';
}
else if($call == "get_follows"){
  $query = 'MATCH (n:user{username: "'.$username.'"})-[:follow]->(m:user) RETURN m';
}
else if($call == "get_follows_html"){
  $query_html = 'MATCH (n:user{username: "'.$username.'"})-[:follow]->(m:user) RETURN m';
}
else if($call == "get_follows_events"){
  $query = 'MATCH (n:user{username: "'.$username.'"})-[:follow]->(u:user)-[:attend]->(m:event) RETURN DISTINCT(m)';//removed clause that kept events that you are going to and that your following users are going to from showing up 
//  $query = 'MATCH (n:user{username: "'.$username.'"})-[:follow]->(u:user)-[:attend]->(m:event) WHERE NOT (n)-[:attend]->(m) RETURN DISTINCT(m)'; 
}
else if($call == "get_follows_events_html"){
  $query_html = 'MATCH (n:user{username: "'.$username.'"})-[:follow]->(u:user)-[:attend]->(m:event) WHERE NOT (n)-[:attend]->(m) RETURN DISTINCT(m)'; 
}
else if($call == "get_rec_events"){
  $query = 'MATCH (n:user{username:"'.$username.'"}),(t1:tag{tag:n.like1}), (t2:tag{tag:n.like2}), (t3:tag{tag:n.like3}) OPTIONAL MATCH (m)-[:tag]->(t1) WHERE NOT (n)-[:attend]->(m) OPTIONAL MATCH (m)-[:tag]->(t2) WHERE NOT (n)-[:attend]->(m) OPTIONAL MATCH (m)-[:tag]->(t3) WHERE NOT (n)-[:attend]->(m) return DISTINCT(m)';
}
else if($call == "get_recommended_events_html"){
  $query_html = 'MATCH (n:user{username:"'.$username.'"}),(t1:tag{tag:n.like1}), (t2:tag{tag:n.like2}), (t3:tag{tag:n.like3}) OPTIONAL MATCH (m)-[:tag]->(t1) WHERE NOT (n)-[:attend]->(m) OPTIONAL MATCH (m)-[:tag]->(t2) WHERE NOT (n)-[:attend]->(m) OPTIONAL MATCH (m)-[:tag]->(t3) WHERE NOT (n)-[:attend]->(m) return DISTINCT(m)';
}
else if($call == "get_attend"){
  $query = 'MATCH (:user{username:"'.$username.'"})-[:attend]->(m:event) RETURN m';
}
else if($call == "get_attend_html"){
  $query_html ='MATCH (:user{username:"'.$username.'"})-[:attend]->(m:event) RETURN m';
}
else if($call == "put_follow"){
  $query = 'MATCH (n:user{username:"'.$this_username.'"}), (m:user{username:"'.$other_username.'"}) CREATE UNIQUE (n)-[:follow{follow:"1"}]->(m) return m';
}
else if ($call == "put_unfollow"){
  $query = 'MATCH (n:user{username:"'.$this_username.'"})-[l:follow]-> (m:user{username:"'.$other_username.'"}) DELETE l return m';
}
else if($call == "put_attend"){
  $query = 'MATCH (n:user{username:"'.$this_username.'"}), (m:event{eid:"'.$eid.'"}) CREATE UNIQUE(n)-[:attend{attend:"1"}]->(m) return m';
}
else if ($call == "put_unattend"){
  $query = 'MATCH (n:user{username:"'.$this_username.'"})-[l:attend]->(m:event{eid:"'.$eid.'"}) DELETE l return m';
}
else if($call == "get_bio"){
  $query = 'MATCH (n:user{username:"'.$username.'"}) RETURN n.userbio as m';
}
//$query = 'MATCH (n:user) RETURN n';

//$query = 'MATCH (n:user{username:"bbuilder"})-[:follow]->(m:user) RETURN n,m';
if($query !== '' && $query_html === ''){
  $response = $client->sendCypherQuery($query);
  print_r(json_encode($response->getRows()[m]));
}
else if($query_html !== ''){
  $response = $client->sendCypherQuery($query_html)->getRows()["m"];
  if(count($response) == 0){
    print_r("The users you are following are currently not planning on attending any events");
  }
  else{
    if($call == 'get_follows_events_html'){
      $html = '';
      foreach($response as $i){
        $html = $html.'<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title">'.$i["title"].'</h4></div><div class="panel-body">'.$i["description"].'</br>Time: '.$i["time"].'</div></div>';
      }
      print_r($html);
    }
    else if($call == 'get_follows_html'){
      $html = '';
      foreach($response as $i){
        $html = $html.'<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title">'.$i["first"].'&nbsp'.$i["last"].'</h4></div><div class="panel-body">Likes:<ul class="list-group"><li class="list-group-item">'.$i["like1"].'</li><li class="list-group-item">'.$i["like2"].'</li><li class="list-group-item">'.$i["like3"].'</li></ul></div></div>';
      }
      $html = $html.'~~~~~';
//start of side panel of user following
      $html = $html.'<ul class="list-group">';
      foreach($response as $i){
        $html = $html.'<li id="'.$i["username"].'" class="list-group-item hover_name" href="#" style="cursor:pointer">'.$i["first"].'&nbsp'.$i["last"].'</li>';
      }
      $html = $html.'</ul>'; //close for side panel
      print_r($html);
    }
    else if($call == 'get_recommended_events_html'){
      $html = '';
      foreach($response as $i){
        $html = $html.'<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title">'.$i[title].'</h4></div><div class="panel-body">'.$i[description].'</br>Time: '.$i[time].'</br>Location: '.$i[address].'</div></div>'; 
      }
      print_r($html);
    }
    else if($call == "get_attend_html"){
      $html = '';
      $html = $html.'<ul class="list-group">';
      foreach($response as $i){
        $html = $html.'<li id="'.$i["eid"].'" class="list-group-item hover_name" href="#" style="cursor:pointer" rel="popover" data-trigger="hover" data-content="'.$i["description"].'">'.$i["title"].'</br>--'.$i["time"].'hr</li>';
      }
      $html = $html.'</ul>';
      print_r($html);
    } 
  }
  return;
}
