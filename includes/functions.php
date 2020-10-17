<?php

function makesafe($d,$type="basic"){
	$d = str_replace("\t","~~",$d);
	$d = str_replace("\r","",$d);
	$d = str_replace("\n","  ",$d);
	$d = str_replace("|","&#124;",$d);
	$d = str_replace("\\","&#92;",$d);
	$d = str_replace("(c)","&#169;",$d);
	$d = str_replace("(r)","&#174;",$d);
	$d = str_replace("\"","&#34;",$d);
	$d = str_replace("'","&#39;",$d);
	$d = str_replace("<","&#60;",$d);
	$d = str_replace(">","&#62;",$d);
	$d = str_replace("`","&#96;",$d);
	$d = str_replace("DELETE FROM","",$d);
	return $d;
}

function demakesafe($d){

	$d = str_replace("&#34;","\"",$d);
	$d = str_replace("&#39;","'",$d);
	$d = str_replace("&#60;","<",$d);
	$d = str_replace("&#62;",">",$d);
	return $d;
}

function codegenerate($length,$type){
  if ($length<=0){
  $length=10;
  }
  if ($type=="normal"){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
  }
  if ($type=="password"){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_-_-_-_$$%%#@@!!((*&&^^%%@&^$T^(!++++';
  }
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $randomString;
}

function user_hash(){
  //Hash is generated using users useragent and cookies to build an identifier
  //This is used to validate the users session
  $hash=$_SERVER['HTTP_USER_AGENT'];
  foreach ($_COOKIE as $key=>$val){
    $keysave=true;
    if (strpos($key,'PHPSESSID')!==false){
      $keysave=false;
    }
    if ($keysave==true){
      $hash="".$hash."/".$key."-".$val."";
    }
  }
  return sha1($hash);
}

function time_elapsed_string($datetime, $full = false) {
  $now = new DateTime;
  $ago = new DateTime($datetime);
  $diff = $now->diff($ago);

  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;

  $string = array(
      'y' => 'year',
      'm' => 'month',
      'w' => 'week',
      'd' => 'day',
      'h' => 'hour',
      'i' => 'minute',
      's' => 'second',
  );
  foreach ($string as $k => &$v) {
      if ($diff->$k) {
          $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
      } else {
          unset($string[$k]);
      }
  }

  if (!$full) $string = array_slice($string, 0, 1);
  return $string ? implode(', ', $string) . ' ago' : 'just now';
}
