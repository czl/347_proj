<?php
  $userlist = array(
    "bbuilder" => "bob",
    "sroe" => "sally",
    "jdoe" => "john",
    "jperez" => "juan",
    "jmartinez" => "jose",
    "nmartinez" => "natalie",
    "jbond" => "james",
    "pcannata" => "phil",
    "sfields" => "sally",
    "tellis" => "taylor",
    "clee" => "clemens",
    "cmartin" => "chris",
    "tbedard" => "tim",
    "hsolo" => "han",
    "lskywalker" => "luke",
    "ijones" => "indiana",
    "sgreen" => "steven",
    "espeegle" => "erika",
    "pcarter" => "peggy",
    "srogers" => "steve"
  ); 
  $user = $_GET["username"];
  $pass = $_GET["pass"];
  if(!isset($userlist[$user]) or $userlist[$user] != $pass){
    echo "user/pass invalid\n";
    exit();
  }
  else{
    session_start();
    $_SESSION["user"] = $user;
    echo "good";
    exit();
  } 
