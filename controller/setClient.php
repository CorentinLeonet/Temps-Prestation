<?php
require_once('Controller.php');

if(!isset($_SESSION)) 
{ 
    session_start(); 
}

if(isset($_SESSION['controller'])) {
	$controller = $_SESSION['controller'];
}

if (isset($_POST['action']) && isset($controller)) {
	$controller->removeClientById($_POST['action']);
}
