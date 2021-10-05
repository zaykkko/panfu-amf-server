<?php

error_reporting(0);

$a = $_GET['flash'];
$b = $_GET['login'];
$c = $_GET['data'];

$r = new stdClass();

header("Content-Type:application/json;charset=utf8");

if($b === '5771f8999e371'){
	$r->result = true;
}else{
	$r->result = false;
}

echo json_encode($r);