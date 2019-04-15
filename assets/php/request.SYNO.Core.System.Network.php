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
	// LINK FOR System settings -> info center -> NETWORK
	// https://trionix.homeftp.net:5555/webapi/_______________________________________________________entry.cgi?api=SYNO.Core.System&method=info&version=1&type=network
	
	//json of SYNO.Core.System NETWORK
	$json_infoDataNetwork = file_get_contents($server.'/webapi/'.$path_core.'?api=SYNO.Core.System&version='.$vCore.'&method=info&type=network&_sid='.$sid, false, stream_context_create($arrContextOptions));
	$obj_infoDataNetwork = json_decode($json_infoDataNetwork);
	
	//SYSTEM: NETWORK INFOS
	$network_dns = $obj_infoDataNetwork->data->{'dns'};
	$network_enabled_domain = $obj_infoDataNetwork->data->{'enabled_domain'};
	$network_enabled_samba = $obj_infoDataNetwork->data->{'enabled_samba'};
	$network_gateway = $obj_infoDataNetwork->data->{'gateway'};
	$network_hostname = $obj_infoDataNetwork->data->{'hostname'};
	$network_interface_ip_address = $obj_infoDataNetwork->data->nif[0]->{'addr'};
	$network_interface_id = $obj_infoDataNetwork->data->nif[0]->{'id'};	
	$network_interface_mac_address = $obj_infoDataNetwork->data->nif[0]->{'mac'};
	$network_interface_subnet_mask = $obj_infoDataNetwork->data->nif[0]->{'mask'};
	$network_interface_type = $obj_infoDataNetwork->data->nif[0]->{'type'};
	$network_interface_ipv6_address = $obj_infoDataNetwork->data->nif[0]->ipv6[0]->{'addr'};
	$network_interface_ipv6_prefix_len = $obj_infoDataNetwork->data->nif[0]->ipv6[0]->{'prefix_len'};
	$network_interface_ipv6_scope = $obj_infoDataNetwork->data->nif[0]->ipv6[0]->{'scope'};

// OUTPUT START HERE
// echo the values ?>
<h1>Network Info Module</h1>

Network DNS: <? echo $network_dns; ?> <br>
Network Enabled Domain: <? echo $network_enabled_domain; ?> <br>
Network Enabled Samba: <? echo $network_enabled_samba; ?> <br>
Network Gateway: <? echo $network_gateway; ?> <br>
Network Hostname: <? echo $network_hostname; ?> <br>
Network IP Address: <? echo $network_interface_ip_address; ?> <br>
Network ID: <? echo $network_interface_id; ?> <br>
Network MAC Address: <? echo $network_interface_mac_address; ?> <br>
Network Subnet Mask: <? echo $network_interface_subnet_mask; ?> <br>
Network Interface Type: <? echo $network_interface_type; ?> <br>
Network IPv6 Address: <? echo $network_interface_ipv6_address; ?> <br>
Network IPv6 Prefix Len: <? echo $network_interface_ipv6_prefix_len; ?> <br>
Network IPv6 Scope: <? echo $network_interface_ipv6_scope; ?> <br><br>



<? require_once('request.SYNO.Logout.php');  
} ?>