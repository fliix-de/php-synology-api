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
	
	// DEV: LINK FOR LIVE CPU MEMORY AND NETWORK DATA INFO
	//echo '<p>Login SUCCESS! : sid = '.$sid.'</p>';
	//echo '<p>success 2: api path = '.$path_core.'</p>';   		
	// LINK FOR LOGFILE aktueller USER DOWNLOAD LOG ACCESS
	// https://trionix.homeftp.net:5555/webapi/_______________________________________________________entry.cgi?api=SYNO.Core.SyslogClient.Log&method=list&version=1&logtype=ftp,filestation,webdav,cifs,tftp
	
	//json of SYNO.Core.SyslogClient.Log (data transfer logs)
	$json_logTransferData = file_get_contents($server.'/webapi/'.$path_core.'?api=SYNO.Core.SyslogClient.Log&version='.$vCore.'&method=list&logtype=ftp,filestation,webdav,cifs,tftp&_sid='.$sid, false, stream_context_create($arrContextOptions));
	$obj_logTransferData = json_decode($json_logTransferData, true)['data']['items'];

// OUTPUT START HERE
// echo the values ?>
<h1>File Transfer User Module</h1>

<?php
//Log File Transfer: file, user, size etc
function super_unique($array,$key) {
   $temp_array = array();
   foreach ($array as &$v) {
	   //CLEAR THE ARRAY FOR 0 Byte Log entries
	   if ( (!isset($temp_array[$v[$key]]) == "0 Bytes") || (!isset($temp_array[$v[$key]]) == "1 Bytes") )      
	       $temp_array[$v[$key]] =& $v;
	}
	$array = array_values($temp_array);
	return $array;
}	

$obj_logTransferDataFiltered = super_unique($obj_logTransferData,'filesize');

foreach($obj_logTransferDataFiltered as $transferDataLog){
	$transferDataLog_cmd = $transferDataLog['cmd'];
	$transferDataLog_descr = $transferDataLog['descr'];
	$transferDataLog_filesize = $transferDataLog['filesize'];
	$transferDataLog_ip = $transferDataLog['ip'];
	$transferDataLog_isdir = $transferDataLog['isdir'];
	$transferDataLog_logtype = $transferDataLog['logtype'];
	$transferDataLog_orginalLogType = $transferDataLog['orginalLogType'];
	$transferDataLog_time = $transferDataLog['time'];
	$transferDataLog_username = $transferDataLog['username']; 
?>
	<? echo $transferDataLog_cmd; ?> <br>
	<? echo $transferDataLog_descr; ?> <br>
	<? echo $transferDataLog_filesize; ?> <br>
	<? echo $transferDataLog_ip; ?> <br>
	<? echo $transferDataLog_isdir; ?> <br>
	<? echo $transferDataLog_logtype; ?> <br>
	<? echo $transferDataLog_orginalLogType; ?> <br>
	<? echo $transferDataLog_time; ?> <br>
	<? echo $transferDataLog_username; ?> <br><br>
<? } // Log File Transfer END ?>


<? require_once('request.SYNO.Logout.php');  
} ?>