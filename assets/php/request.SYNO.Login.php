<?php require_once('../../config.php');     
//****** Credits to / sources
// http://www.nas-forum.com/forum/topic/46256-script-web-api-synology/
// http://www.thomastheunen.eu/2015/06/the-synology-api-not-much-documentation.html	

// get config.php infos	
$server = $syno_server;
$login = $syno_login;
$pass = $syno_pass;
	
/* API VERSIONS */
//SYNO.API.Auth
$vAuth = 4;
//SYNO.Core
$vCore = 1;    
//SYNO.SurveillanceStation.Camera
$vCamera = 1;

//Define ssl arguments
$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);

//Get SYNO.API.Auth Path (recommended by Synology for further update)
$json = file_get_contents($server.'/webapi/query.cgi?api=SYNO.API.Info&version=1&method=query&query=SYNO.API.Auth', false, stream_context_create($arrContextOptions));
$obj = json_decode($json);
$path = $obj->data->{'SYNO.API.Auth'}->path;

//Login and creating SID
$json_login = file_get_contents($server.'/webapi/'.$path.'?api=SYNO.API.Auth&version='.$vAuth.'&method=login&account='.$login.'&passwd='.$pass.'&session=FileStation&format=sid', false, stream_context_create($arrContextOptions));
$obj_login = json_decode($json_login);
?>