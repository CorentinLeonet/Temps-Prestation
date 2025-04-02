<?php

class Timer
{

	public function start()
	{
		if(!isset($_SESSION)) 
		{ 
			session_start(); 
		} 
		if($_SESSION['pause']){
			$_SESSION['start_time'] = time() - $_SESSION['elapsed_time'];
			$_SESSION['pause'] = false;
		}
			
		else if(!(isset($_SESSION['start_time']))){
			$_SESSION['start_time'] = time();
			$_SESSION['elapsed_time'] = 0;
		}
	}
				
	public function pause()
	{
		if(!isset($_SESSION)) 
		{ 
			session_start(); 
		} 
		if (isset($_SESSION['start_time'])) {
			$_SESSION['elapsed_time'] = time() - $_SESSION['start_time'];
			unset($_SESSION['start_time']);
			$_SESSION['pause'] = true;
		}
	}
		
	public function unpause()
	{
		if(!isset($_SESSION)) 
		{ 
			session_start(); 
		} 
		$_SESSION['start_time'] = time() - $_SESSION['elapsed_time'];
		$_SESSION['pause'] = false;
	}
			
	public function stop()
	{
		if(!isset($_SESSION)) 
		{ 
			session_start(); 
		} 
		if (isset($_SESSION['start_time'])) {
			$_SESSION['elapsed_time'] = time() - $_SESSION['start_time'];
			unset($_SESSION['start_time']);
		}
		$_SESSION['pause'] = false;
		$time = $_SESSION['elapsed_time'];
		$formattedTime = gmdate("H:i:s", $time);
		echo json_encode(['time' => $formattedTime]);
	}
			
	public function update()
	{
		if(!isset($_SESSION)) 
		{ 
			session_start(); 
		} 
		if (isset($_SESSION['start_time'])) {
			$_SESSION['elapsed_time'] = time() - $_SESSION['start_time'];
		}
		$time = $_SESSION['elapsed_time'];
		$formattedTime = gmdate("H:i:s", $time);
		echo json_encode(['time' => $formattedTime]);
	}
	
}