<?php

//##############################################################
//############################################################## - Functions
//##############################################################

include "includes/functions.php";

//##############################################################
//############################################################## - Startup
//##############################################################

ini_set('display_errors', '0');
ini_set('date.timezone', 'America/New_York');
session_start();

$nh_panel_url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$nh_panel_session=$_GET["session"];
$nh_panel_user_hash=user_hash();

//Load rules the user has set or the defaults
$nh_panel_settings=array();
$nh_panel_settings["nodehost_api_key"]="";
$nh_panel_settings["panel_password"]="";
$nh_panel_settings["session_expire"]="";

//Load Settings Files
if (is_file("/config/settings.json")){
  $mergesettings=file_get_contents("/config/settings.json");
  $mergesettings=json_decode($mergesettings,true);
  $newarray = array_merge($nh_panel_settings, $mergesettings);
  $nh_panel_settings=$newarray;
}

//##############################################################
//############################################################## - Load Session
//##############################################################

//Load rules the user has set or the defaults
$nh_panel_session=array();
$nh_panel_session["id"]="";
$nh_panel_session["container_id"]="";
$nh_panel_session["expire"]="";
$nh_panel_session["auth_hash"]="";

if (is_file("/sessions/".$nh_panel_session.".json")){
  $mergesettings=file_get_contents("/sessions/".$nh_panel_session.".json");
  $mergesettings=json_decode($mergesettings,true);
  $newarray = array_merge($nh_panel_settings, $mergesettings);
  $nh_panel_session=$newarray;
}

//If the users hash is not saved we do that now
if ($nh_panel_session["auth_hash"]==""){
  $nh_panel_session["auth_hash"]=$nh_panel_user_hash;
}

//If user hash is not correct we end
if ($nh_panel_session["auth_hash"]!=$nh_panel_user_hash){
  die("Bad session");
}

//If the expire is not saved we do that now
if ($nh_panel_session["expire"]==""){
  $nh_panel_session["expire"]=timestamp($nh_panel_settings["session_expire"]);
}

//If session is expired delete
if ($nh_panel_session["expire"]<=timestamp()){
  if (is_file("/sessions/".$nh_panel_session.".json")){
    unlink("/sessions/".$nh_panel_session.".json");
  }
  die("Session expired");
}

//##############################################################
//############################################################## - Start Checks
//##############################################################





//##############################################################
//############################################################## - Save Users Session
//##############################################################

if (!is_file("/sessions/".$nh_panel_session.".json")){
  //Create session file, but first we must do a check for API validation before we just CREATE a session file
  //$fp = fopen("/sessions/".$nh_panel_session.".json", 'w');
  //fwrite($fp, json_encode($nh_panel_session,JSON_PRETTY_PRINT));
  //fclose($fp);
}

if (is_file("/sessions/".$nh_panel_session.".json")){
  $fp = fopen("/sessions/".$nh_panel_session.".json", 'w');
  fwrite($fp, json_encode($nh_panel_session,JSON_PRETTY_PRINT));
  fclose($fp);
}

?>
