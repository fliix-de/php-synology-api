<?php      
//****** Credits to / sources
// http://www.nas-forum.com/forum/topic/46256-script-web-api-synology/
// http://www.thomastheunen.eu/2015/06/the-synology-api-not-much-documentation.html	

//Define ssl arguments
$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);
//SECURITY Logout and destroying SID
$json_logout = file_get_contents($server.'/webapi/'.$path.'?api=SYNO.API.Auth&method=logout&version='.$vAuth.'&session=FileStation&_sid='.$sid, false, stream_context_create($arrContextOptions));
$obj_logout = json_decode($json_logout);	
if($obj_logout->success == 1){
	//echo 'Logout SUCCESS : session closed';
} else {
	//echo 'Logout FAILED : please check code due to security issues!';
}	
return $json_logout;
return $obj_logout;	

?>