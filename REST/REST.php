<?php

$call = $_POST['call'];
//$ret_arr = array('ret'=>"end");
//echo $_POST['a'];
$ret_array = array('call'=>$call);
echo json_encode($ret_array);
//echo $_POST['\a'];
