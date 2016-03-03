<?php

	error_reporting(E_ALL);
	ini_set('display_errors',1);
	require_once 'goutte.phar';
	use Goutte\Client;
	
	$mobile_number = $_GET['q'];
	$client = new Client();
	$crawler = $client->request("GET","http://bmobile.in/".$mobile_number);
	$data = array();
	$crawler->filter('td.dtls')->each(function($node){
		array_push($GLOBALS['data'],trim($node->text()));
	});
	//print_r($data);
	echo json_encode($data);