<?php
/*  session_unset();
  session_destroy();
echo "no session";
exit();
*/
  session_start();
  $userlist = array(
    "bbuilder" => "Bob Builder",
    "sroe" => "Sally Roe",
    "jdoe" => "John Doe",
    "jperez" => "Juan Perez",
    "jmartinez" => "Jose Martinez",
    "nmartinez" => "Natalie Martinez",
    "jbond" => "James Bond",
    "pcannata" => "Phil Cannata"
  );
  if(isset($_SESSION["user"])){
    echo "session";
    echo $userlist[$_SESSION["user"]];
  }
  else{
    echo "no session";
  }
