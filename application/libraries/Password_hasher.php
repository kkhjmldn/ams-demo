
<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class Password_hasher {
	public function __construct(){
	    error_reporting(E_ALL);
        ini_set("display_errors", 1);
	}
	function enkrip($plain,$key)
	{
	
		$hasher = base64_encode($plain.$key);
		return $hasher;
	}
	function dekrip($hasher,$key)
	{
		 
		$dec = base64_decode($hasher);
		$string = explode($key, $dec);
		$plain =$string[0];
		return $plain;
	}
}?>