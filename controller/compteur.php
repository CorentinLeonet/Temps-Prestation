<?php
	require_once("Timer.php");

	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	} 

	$timer = new Timer();

	if (isset($_POST['action'])) {
		switch ($_POST['action']) {
			
			case 'start':
				$timer->start();
				break;
				
			case 'pause':
				$timer->pause();
				break;
			case 'unpause':
				$timer->unpause();
				break;
				
			case 'stop':
				$timer->stop();
				break;
				
			case 'update':
				$timer->update();
				break;
				
			default:
				echo json_encode();
				break;
		}
	}