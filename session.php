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
    "pcannata" => "Phil Cannata",
    "sfields" => "Sally Fields",
    "tellis" => "Taylor Ellis",
    "clee" => "Clemens Lee",
    "cmartin" => "Chris Martin",
    "tbedard" => "Tim Bedard",
    "hsolo" => "Han Solo",
    "lskywalker" => "Luke Skywalker",
    "ijones" => "Indiana Jones",
    "sgreen" => "Steven Green",
    "espeegle" => "Erika Speegle",
    "pcarter" => "Peggy Carter",
    "srogers" => "Steve Rogers"
  );
  if(isset($_SESSION["user"])){
//    echo "session-";
    echo "session-".$_SESSION["user"]."-".$userlist[$_SESSION["user"]];
  }
  else{
    echo "no session";
  }
