<?php
  $userlist = array(
    "bbuilder" => "bob",
    "sroe" => "sally",
    "jdoe" => "john",
    "jperez" => "juan",
    "jmartinez" => "jose",
    "nmartinez" => "natalie",
    "jbond" => "james",
    "pcannata" => "phil"
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
