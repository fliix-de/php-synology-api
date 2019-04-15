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
	// LINK FOR System settings -> info center -> STORAGE
	// https://trionix.homeftp.net:5555/webapi/_______________________________________________________entry.cgi?api=SYNO.Core.System&method=info&version=1&type=storage
	
	//json of SYNO.Core.System STORAGE
	$json_infoDataStorage = file_get_contents($server.'/webapi/'.$path_core.'?api=SYNO.Core.System&version='.$vCore.'&method=info&type=storage&_sid='.$sid, false, stream_context_create($arrContextOptions));
	$obj_infoDataStorage = json_decode($json_infoDataStorage);
	
	//Storage: actual live volume information Direct query to volume 0
	$storage_volume_name = $obj_infoDataStorage->data->{'vol_info'}[0]->name;
	$storage_volume_status = $obj_infoDataStorage->data->{'vol_info'}[0]->status;
	$storage_volume_total_size = $obj_infoDataStorage->data->{'vol_info'}[0]->{'total_size'};
	$storage_volume_used_size = $obj_infoDataStorage->data->{'vol_info'}[0]->{'used_size'};
	$storage_volume_used_percent = round((($storage_volume_used_size * 100) / $storage_volume_total_size), 2);
	$storage_volume_free_size = $storage_volume_total_size - $storage_volume_used_size;

	if ($storage_volume_total_size >= 1000000000000) {
		$storage_volume_total_size = round(($storage_volume_total_size / 1000000000000), 2).' TB';
	} else {
		$storage_volume_total_size = round(($storage_volume_total_size / 1000000000), 2).' GB';
	};
	
	if ($storage_volume_used_size >= 1000000000000) {
		$storage_volume_used_size = round(($storage_volume_used_size / 1000000000000), 2).' TB';
	} else {
		$storage_volume_used_size = round(($storage_volume_used_size / 1000000000), 2).' GB';
	};	

	if ($storage_volume_free_size >= 1000000000000) {
		$storage_volume_free_size = round(($storage_volume_free_size / 1000000000000), 2).' TB';
	} else {
		$storage_volume_free_size = round(($storage_volume_free_size / 1000000000), 2).' GB';
	};

	if ($storage_volume_status == "normal") {
		$storage_volume_status = '<span style="color:#85C728;">'.$storage_volume_status.'</span>';
	} else {
		$storage_volume_status = '<span style="color:red;">'.$storage_volume_status.'</span>';
	};
// OUTPUT START HERE
// echo the values ?>
<script>
$( document ).ready(function() {
    $("#hdd-circle").circliful({
        animation: 2,
        animationStep: 40,
        //icon: "f0ae",
		//iconSize: 20,
		//iconColor: '#ccc',
		//iconPosition: 'left', //top, bottom, left, right or middle        
        foregroundBorderWidth: 20,
        backgroundBorderWidth: 20,
        percent: '<? echo $storage_volume_used_percent; ?>',
        fontColor: "#fff",	//color of the percentage	RGB	#aaa
		percentageTextSize: 16,	//font size of the percentage text	integer	22
		//textAdditionalCss: '',	//additonal css for the percentage text	string	''	        
		//targetPercent: '',	//draws a circle around the main circle	integer	0
		//targetTextSize: '',	//font size of the target percentage	integer	17
		//targetColor: '',	//fill color of the target circle	RGB	#2980B9
		text: 'HDD',	//info text shown bellow the percentage in the circle	string	''	        
        textSize: 30,
        textStyle: "font-size: 16px;",
        textColor: "#fff",
        multiPercentage: 1,
        percentages: [10, 20, 30],
        foregroundColor: '#FFC600',
		backgroundColor: '#85C728',
		//fillColor: '#ccc',
    });
});	    
</script>	
 
<!--h1>&nbsp;</h1-->
<style>
	#storage-left {float: left; width:200px;margin: -35px 0 0 -35px;}
	#storage-right {float: right; width:140px;margin-top: 45px;}
	.col-lg-hdd {
		width: 240px;
		height: 230px;
		display: inline-block
	}	
.table-syno-hdds tr th {
    font-size: 18px;
}	
.table-syno-hdds tr td {font-weight: bold;}
td.order{width:4%;text-align: center;}	
td.model{width:5%;}	
td.status{width:5%;}	
td.temps{width:5%;}	
td.type{width:5%;}	
td.size{width:5%;}	
	
</style>
<div id="storage-left">
	<div class="row">
	    <div class="col-lg-hdd">
	        <div id="hdd-circle"></div>
	    </div>
	</div>
</div>

<table id="storage-right" class="table table-syno-basics">
	<tr>
        <td class="desc">Free</td>
        <td class="value"><? echo $storage_volume_free_size; ?></td>
    </tr>
    <tr>
        <td class="desc">Used</td>
        <td class="value"><? echo $storage_volume_used_size; ?></td>
    </tr>
    <tr>
        <td class="desc">Total</td>
        <td class="value"><? echo $storage_volume_total_size; ?></td>    
    </tr>
    <tr>
        <td class="desc">Status</td>
        <td class="value"><? echo $storage_volume_status; ?></td>        
    </tr> 
</table>

<table class="table table-syno-hdds">
<tr>
	<th>Slot</th>	
	<th>Model</th>
	<th>Health</th>
	<th>°C</th>
	<th>Typ</th>
	<th>Size</th>
</tr>    
<? foreach($obj_infoDataStorage->data->{'hdd_info'} as $storage_hhd){
	$storage_hhd_capacity = $storage_hhd->{'capacity'};
	$storage_hhd_diskType = $storage_hhd->{'diskType'};
	$storage_hhd_disknumber = $storage_hhd->{'diskno'};
	$storage_hhd_ebox_order = $storage_hhd->{'ebox_order'};
	$storage_hhd_model = $storage_hhd->{'model'};
	$storage_hhd_order = $storage_hhd->{'order'};
	$storage_hhd_status = $storage_hhd->{'status'};
	$storage_hhd_temp = $storage_hhd->{'temp'};
	$storage_hhd_volumepool = $storage_hhd->{'volume'}; 
	
	$storage_hhd_model = substr($storage_hhd_model, 0, strpos($storage_hhd_model, '-'));
	if ($storage_hhd_status == "normal") {
		$storage_hhd_status = '<span style="color:#85C728;">'.$storage_hhd_status.'</span>';
	} else {
		$storage_hhd_status = '<span style="color:red;">'.$storage_hhd_status.'</span>';
	};
?>
<tr>
    <td class="order"><? echo $storage_hhd_order; ?></td>
    <td class="model"><? echo $storage_hhd_model; ?></td>    
	<td class="status"><? echo $storage_hhd_status; ?></td>
    <td class="temps"><? echo $storage_hhd_temp; ?></td>
    <td class="type"><? echo $storage_hhd_diskType; ?></td> 
    <td class="size"><? echo round(($storage_hhd_capacity/1000000000000), 1); ?>TB</td> 
</tr>    
<? } ?>
</table>




<!-- hidden output

Storage Volume Name: <? echo $storage_volume_name; ?> <br>
Storage Volume Status: <? echo $storage_volume_status; ?> <br>
Storage Volume Total Size: <? echo $storage_volume_total_size; ?> Bytes<br>
Storage Volume Used Size: <? echo $storage_volume_used_size; ?> Bytes<br><br>

<h3>Storage volumes loop</h3>
<? //Storage Volumes: Volumes details/status LIST
foreach($obj_infoDataStorage->data->{'vol_info'} as $storage_volumes){
	$storage_volumes_name = $storage_volumes->{'name'};
	$storage_volumes_status = $storage_volumes->{'status'};
	$storage_volumes_total_size = $storage_volumes->{'total_size'};
	$storage_volumes_used_size = $storage_volumes->{'used_size'};
?>
	Volume Name: <? echo $storage_volumes_name; ?> <br>
	Volume Status: <? echo $storage_volumes_status; ?> <br>
	Volume Total Size: <? echo $storage_volumes_total_size; ?> Bytes<br>
	Volume Used Size: <? echo $storage_volumes_used_size; ?> Bytes<br><br>
<? } //Storage Volumes END ?>


<h3>Storage hdds loop</h3>
<? //Storage HDD: HDD details/status LIST
foreach($obj_infoDataStorage->data->{'hdd_info'} as $storage_hhd){
	$storage_hhd_capacity = $storage_hhd->{'capacity'};
	$storage_hhd_diskType = $storage_hhd->{'diskType'};
	$storage_hhd_disknumber = $storage_hhd->{'diskno'};
	$storage_hhd_ebox_order = $storage_hhd->{'ebox_order'};
	$storage_hhd_model = $storage_hhd->{'model'};
	$storage_hhd_order = $storage_hhd->{'order'};
	$storage_hhd_status = $storage_hhd->{'status'};
	$storage_hhd_temp = $storage_hhd->{'temp'};
	$storage_hhd_volumepool = $storage_hhd->{'volume'}; 
?>
	Storage HDD Slot: <? echo $storage_hhd_order; ?> <br>
	Storage HDD Model: <? echo $storage_hhd_model; ?> <br>
	Storage HDD Status: <? echo $storage_hhd_status; ?> <br>
	Storage HDD Temp: <? echo $storage_hhd_temp; ?> Grad Celsius<br>
	Storage HDD Capacity: <? echo $storage_hhd_capacity; ?> Bytes<br>
	Storage HDD DiskType: <? echo $storage_hhd_diskType; ?> <br>
	Storage HDD Disk Number: <? echo $storage_hhd_disknumber; ?> <br>
	Storage HDD EBOX Order: <? echo $storage_hhd_ebox_order; ?> <br>
	Storage HDD volume pool: <? echo $storage_hhd_volumepool; ?> <br><br>
<? } //Storage HDD END ?>
-->

<? require_once('request.SYNO.Logout.php');  
} ?>