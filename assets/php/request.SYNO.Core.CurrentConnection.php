<?php require_once('request.SYNO.Login.php'); 

if($obj_login->success != "true"){	
	echo "Login FAILED";
	exit();
}else{
	//authentification successful
	$sid = $obj_login->data->sid;
	$json_core = file_get_contents($server.'/webapi/query.cgi?api=SYNO.API.Info&method=Query&version=1&query=SYNO.Entry.Request', false, stream_context_create($arrContextOptions));
	$obj_core = json_decode($json_core);
	$path_core = $obj_core->data->{'SYNO.Entry.Request'}->path;	
	
	// LINK FOR Current Connections
	// https://trionix.homeftp.net:5555/webapi/_______________________________________________________entry.cgi?start=0&limit=50&sort_by=%22time%22&sort_direction=%22DESC%22&offset=0&action=%22enum%22&api=SYNO.Core.CurrentConnection&method=list&version=1&_sid='.$sid.'

	//json of SYNO.Core.CurrentConnection
	$json_infoCurrentConnection = file_get_contents($server.'/webapi/'.$path_core.'?start=0&limit=50&sort_by=%22time%22&sort_direction=%22DESC%22&offset=0&action=%22enum%22&api=SYNO.Core.CurrentConnection&method=list&version='.$vCore.'&_sid='.$sid, false, stream_context_create($arrContextOptions));
	$obj_infoCurrentConnection = json_decode($json_infoCurrentConnection);
?>
	<h1>Actual User Connected list</h1>
	
<?	$actualUserCount = 0;
	foreach($obj_infoCurrentConnection->data->items as $currentConnection){
	    $actualUserCount++;
		$currentConnection_can_be_kicked = $currentConnection->{'can_be_kicked'};
		$currentConnection_descr = $currentConnection->{'descr'};
		$currentConnection_from = $currentConnection->{'from'};
		$currentConnection_pid = $currentConnection->{'pid'};
		$currentConnection_time = $currentConnection->{'time'};
		$currentConnection_type = $currentConnection->{'type'};
		$currentConnection_user_can_be_disabled = $currentConnection->{'user_can_be_disabled'};
		$currentConnection_who = $currentConnection->{'who'};	
	?> 	
	User Connected can be kicked? (1=true/0=false): <? echo $currentConnection_can_be_kicked; ?> <br>
	User Connected on: <? echo $currentConnection_descr; ?> <br>
	User Connected IP: <? echo $currentConnection_from; ?> <br>
	User Connected PID: <? echo $currentConnection_pid; ?> <br>
	User Connected Time: <? echo $currentConnection_time; ?> <br>
	User Connected via: <? echo $currentConnection_type; ?> <br>
	User Connected can be disabled (1=true/0=false): <? echo $currentConnection_user_can_be_disabled; ?> <br>
	User Connected Name: <? echo $currentConnection_who; ?> <br><br>
 <?
}
 ?>
	Total User Connected: <? echo $actualUserCount; ?> <br><br>
<? require_once('request.SYNO.Logout.php');  
} ?>