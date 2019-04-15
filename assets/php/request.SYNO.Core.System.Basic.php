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
	header ("Content-Type:text/xml");	
	
	// DEV: LINK FOR LIVE CPU MEMORY AND NETWORK DATA INFO
	//echo '<p>Login SUCCESS! : sid = '.$sid.'</p>';
	//echo '<p>success 2: api path = '.$path_core.'</p>';   	
	// LINK FOR System settings -> info center -> BASICS
	// https://trionix.homeftp.net:5555/webapi/_______________________________________________________entry.cgi?api=SYNO.Core.System&method=info&version=1
	
	//json of SYNO.Core.System BASIC INFOS
	$json_infoDataBasics = file_get_contents($server.'/webapi/'.$path_core.'?api=SYNO.Core.System&version='.$vCore.'&method=info&_sid='.$sid, false, stream_context_create($arrContextOptions));
	$obj_infoDataBasics = json_decode($json_infoDataBasics);
	
	//SYSTEM: BASIC INFOS
	$basic_cpu_clock_speed = $obj_infoDataBasics->data->{'cpu_clock_speed'};
	$basic_cpu_cores = $obj_infoDataBasics->data->{'cpu_cores'};
	$basic_cpu_family = $obj_infoDataBasics->data->{'cpu_family'};
	$basic_cpu_series = $obj_infoDataBasics->data->{'cpu_series'};
	$basic_cpu_vendor = $obj_infoDataBasics->data->{'cpu_vendor'};
	$basic_enabled_ntp = $obj_infoDataBasics->data->{'enabled_ntp'};
	$basic_firmware_date = $obj_infoDataBasics->data->{'firmware_date'};
	$basic_firmware_ver = $obj_infoDataBasics->data->{'firmware_ver'};
	$basic_model = $obj_infoDataBasics->data->{'model'};
	$basic_ntp_server = $obj_infoDataBasics->data->{'ntp_server'};
	$basic_ram_size = $obj_infoDataBasics->data->{'ram_size'};
	$basic_sata_dev = $obj_infoDataBasics->data->{'sata_dev'};
	$basic_serial = $obj_infoDataBasics->data->{'serial'};
	$basic_sys_temp = $obj_infoDataBasics->data->{'sys_temp'};
	$basic_sys_tempwarn = $obj_infoDataBasics->data->{'sys_tempwarn'};
	$basic_time = $obj_infoDataBasics->data->{'time'};
	$basic_time_zone = $obj_infoDataBasics->data->{'time_zone'};
	$basic_time_zone_description = $obj_infoDataBasics->data->{'time_zone_desc'};
	$basic_up_time = $obj_infoDataBasics->data->{'up_time'};

//echo var_dump(explode( ':', $basic_up_time ) );

$upTimeArray = explode( ':', $basic_up_time );

$upTimeTotalHour = $upTimeArray[0];
$upTimeMinute = $upTimeArray[1];
$upTimeSecond = $upTimeArray[2];
$upTimeDay = floor($upTimeTotalHour/24);
$upTimeHour = fmod($upTimeTotalHour, 24);

// OUTPUT START HERE
// echo the values ?>
<h1>Homeserver</h1>
<style>
.table-syno-basics {margin: 0;padding: 0;}	
.table-syno-basics .desc {width: 14%; /* font-weight: bold; */}
.table-syno-basics .value {font-weight: bold;}
.table-syno-basics .spacer {width: 2%}	

</style>

<table class="table table-syno-basics">
    <tr>
        <td class="desc">Model</td>
        <td class="value">Synology <? echo $basic_model; ?> (SN: <? echo $basic_serial; ?>)</td>
		<td class="spacer"></td>
        <td class="desc">Temperature</td>
        <td class="value"><? echo $basic_sys_temp; ?> °C</td>
    </tr>
    <tr>
        <td class="desc">CPU</td>
        <td class="value"><? echo $basic_cpu_vendor; ?> <? echo $basic_cpu_family; ?> <? echo $basic_cpu_series; ?> @<? echo $basic_cpu_cores; ?>x<? echo $basic_cpu_clock_speed; ?>Mhz</td>    
		<td class="spacer"></td>
        <td class="desc">Firmware</td>
        <td class="value"><? echo $basic_firmware_ver; ?></td>        
    </tr>
    <tr>
        <td class="desc">UP Time</td>
        <td class="value"><? echo $upTimeDay; ?> days, <? echo $upTimeHour; ?> hours, <? echo $upTimeMinute; ?> minutes, <? echo $upTimeSecond; ?> seconds</td>    
		<td class="spacer"></td>
        <td class="desc">Last Update</td>
        <td class="value"><? echo $basic_firmware_date; ?></td>        
    </tr>    
</table>


<!-- // saved for later usage, NOT Shown INFOS
System CPU Clock Speed: <? echo $basic_cpu_clock_speed; ?> <br>
System CPU Cores: <? echo $basic_cpu_cores; ?> <br>
System CPU Family: <? echo $basic_cpu_family; ?> <br>
System CPU Series: <? echo $basic_cpu_series; ?> <br>
System CPU Vendor: <? echo $basic_cpu_vendor; ?> <br>
System Enabled NTP: <? echo $basic_enabled_ntp; ?> <br>
System Firmware Date: <? echo $basic_firmware_date; ?> <br>
System Firmware Version: <? echo $basic_firmware_ver; ?> <br>
System Model: <? echo $basic_model; ?> <br>
System NTP Server: <? echo $basic_ntp_server; ?> <br>
System RAM Size: <? echo $basic_ram_size; ?> <br>
System Sata Devices: <? echo $basic_sata_dev; ?> <br>
System Serialnumber: <? echo $basic_serial; ?> <br>
System Temperature: <? echo $basic_sys_temp; ?> <br>
System Temperature Warning: <? echo $basic_sys_tempwarn; ?> <br>
System Time: <? echo $basic_time; ?> <br>
System Timezone: <? echo $basic_time_zone; ?> <br>
System Timezone Description: <? echo $basic_time_zone_description; ?> <br>
System UP Time: <? echo $basic_up_time; ?> <br><br>

<h3>External device list</h3>
<? //USB DEVICES: details/status LIST
foreach($obj_infoDataBasics->data->{'usb_dev'} as $usb_device){
	$usb_device_cls = $usb_device->{'cls'};
	$usb_device_producer = $usb_device->{'producer'};
	$usb_device_product = $usb_device->{'product'}; 
?>
	USB Device Type: <? echo $usb_device_cls; ?> <br>
	USB Device Producer/Brand: <? echo $usb_device_producer; ?> <br>
	USB Device Product Name: <? echo $usb_device_product; ?> <br><br>
<? } // USB DEVICES END ?>
-->
<? require_once('request.SYNO.Logout.php');  
} ?>