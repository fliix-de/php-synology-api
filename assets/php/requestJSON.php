<?php require_once('request.SYNO.Login.php');     

if($obj_login->success != "true"){	
	echo "Login FAILED core";
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
	// https://trionix.homeftp.net:5555/webapi/_______________________________________________________entry.cgi?api=SYNO.Core.System.Utilization&method=get&version=1&type=current&resource=cpu	
	 
	//json of SYNO.Core.System.Utilization (cpu, mem, network etc)
	$json_coreData = file_get_contents($server.'/webapi/'.$path_core.'?api=SYNO.Core.System.Utilization&version='.$vCore.'&method=get&type=current&_sid='.$sid, false, stream_context_create($arrContextOptions));
	$obj_coreData = json_decode($json_coreData);
	
	
	
//var_dump($json_coreData);

//$json_url = $server.'/webapi/'.$path_core.'?api=SYNO.Core.System.Utilization&version='.$vCore.'&method=get&type=current&_sid='.$sid;

//echo($json_url .'<br>');
//echo($obj_coreData->data->memory->{'memory_size'} .'<br>');

print(json_encode($obj_coreData));
?>

<!--
<html>
<head>
	<meta name="google" value="notranslate" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="assets/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/jquery.circliful.css">	
    <link rel="stylesheet" href="assets/css/weather-icons.css">
	<script src="../js/jquery-2.2.1.min.js"></script>
<script>
/**
var previousValue = null;
function checkForChange() {
    $.ajax({
        //url: '<?php echo $json_url; ?>',
        url: 'test.json',
		type: "GET",			
		dataType: "json",
        success: function(data) {
	        //alert('success');
            //var json = $.parseJSON(data);
            alert( data[0].id );
	        $('body').text(data);
            if (data != previousValue) { // Something have changed!
                //Call function to update div
                previousValue = data;
                
            }
        }
    });
}

setInterval("checkForChange();", 1000);
**/

$(document).ready(function()
{   
    // Einfacher AJAX-Aufruf
    $("button#ajaxCall_easy").click(function() {

        $.ajax({
            type: "POST",
            url: "test.json",
            data: {
                method: "easy_call"
            },
            success: function(content) {
	            
               var syno_id = content.data[0].id;
               
               $("#content").text(syno_id);

            
            }
        });

        return false;
    });

    // synology AJAX-Aufruf
    $("button#synologyCall_easy").click(function() {

        $.ajax({
            type: "POST",
            url: "<?php echo $json_url; ?>",
            data: {
                method: "easy_call"
            },
            success: function(content) {
	            
               var syno_memory = content.data.memory.memory_size;
               
               $("#contentSynology").text(syno_memory);

            
            }
        });

        return false;
    });
    
});

//$obj_coreData->data->memory->{'memory_size'};

</script>

</head>
<body>	
<button id="ajaxCall_easy" class="btn btn-info">einfacher AJAX-Aufruf</button>	
<div id="content"></div>
<?php echo 'Hello Welt'; ?>	

<button id="synologyCall_easy" class="btn btn-info">synology AJAX-Aufruf</button>
<div id="contentSynology"></div>
	
</body>
</html>	








<!--

<?	
	//CPU: live actual system load in Percent %
	$cpu_system_load = $obj_coreData->data->cpu->{'system_load'};
	$cpu_user_load = $obj_coreData->data->cpu->{'user_load'};
	$cpu_other_load = $obj_coreData->data->cpu->{'other_load'};
	$cpu_total_load = ($cpu_system_load + $cpu_user_load + $cpu_other_load);
	$cpu_system_1min_load = $obj_coreData->data->cpu->{'1min_load'};
	$cpu_system_5min_load = $obj_coreData->data->cpu->{'5min_load'};
	$cpu_system_15min_load = $obj_coreData->data->cpu->{'15min_load'};
	$cpu_system_device = $obj_coreData->data->cpu->{'device'};

	
	//Network: live actual upload/download transfer in kilobytes
	$network_device = $obj_coreData->data->network[0]->device;
	$network_rx = $obj_coreData->data->network[0]->rx;
	$network_tx = $obj_coreData->data->network[0]->tx;
	$ByteSendRate      = $network_tx;
	$ByteReceiveRate   = $network_rx;
	//echo "trafficout:",$ByteSendRate," trafficin:",$ByteReceiveRate,"  ";
	if (($ByteReceiveRate/1000) >= 10000 ) {
		//echo 'Actual Download Rate: '.round($ByteReceiveRate/1000000, 2) . " mbit/s       ";
		$ByteReceiveRate_kb = round($ByteReceiveRate/1000000, 2);
		$ByteReceiveRate_mb = round($ByteReceiveRate/8000000, 2);	
	} else {
		$ByteReceiveRate_kb = round($ByteReceiveRate/1000, 2);
		$ByteReceiveRate_mb = round($ByteReceiveRate/8000000, 2);
	}
	if (($ByteSendRate/1000) >= 10000 ) {
		//echo 'Actual Upload Rate: '.round($ByteSendRate/1000000, 2) . " mbit/s       ";
		$ByteSendRate_kb = round($ByteSendRate/1000000, 2);
		$ByteSendRate_mb = round($ByteSendRate/8000000, 2);	
	} else {
		$ByteSendRate_kb = round($ByteSendRate/1000, 2);
		$ByteSendRate_mb = round($ByteSendRate/8000000, 2);
	}
	
	//Memory: live actual usage/availability
	$memory_size = $obj_coreData->data->memory->{'memory_size'};
	$memory_available_real = $obj_coreData->data->memory->{'avail_real'};
	$memory_real_usage = $obj_coreData->data->memory->{'real_usage'}; //percent
	$memory_si_disk = $obj_coreData->data->memory->{'si_disk'};
	$memory_so_disk = $obj_coreData->data->memory->{'so_disk'};
	$memory_swap_usage = $obj_coreData->data->memory->{'swap_usage'}; //percent
	$memory_total_real = $obj_coreData->data->memory->{'total_real'};
	$memory_total_swap = $obj_coreData->data->memory->{'total_swap'};
	$memory_avail_swap = $obj_coreData->data->memory->{'avail_swap'};
	$memory_buffer = $obj_coreData->data->memory->{'buffer'};
	$memory_cached = $obj_coreData->data->memory->{'cached'};
	$memory_device = $obj_coreData->data->memory->{'device'};
	$memory_real_usage_mb = ($memory_real_usage / 100) * $memory_total_real;
	$memory_reserved = round(($memory_size / 1000), 1) - (round(($memory_real_usage_mb/1000),1) + round(($memory_buffer/1000),1) + round(($memory_cached/1000),1) + round(($memory_available_real / 1000), 1));
		        

//HTML OUTPUT START HERE
// echo the values ?>
<!-- 	
	<script>
	$( document ).ready(function() {
	    $("#cpu-circle").circliful({
	        animation: 2,
	        animationStep: 40,
	        //icon: "f0ae",
			//iconSize: 20,
			//iconColor: '#ccc',
			//iconPosition: 'left', //top, bottom, left, right or middle        
	        foregroundBorderWidth: 20,
	        backgroundBorderWidth: 20,
	        percent: '<? echo $cpu_total_load; ?>',
	        fontColor: "#fff",	//color of the percentage	RGB	#aaa
			percentageTextSize: 16,	//font size of the percentage text	integer	22
			//textAdditionalCss: '',	//additonal css for the percentage text	string	''	        
			//targetPercent: '',	//draws a circle around the main circle	integer	0
			//targetTextSize: '',	//font size of the target percentage	integer	17
			//targetColor: '',	//fill color of the target circle	RGB	#2980B9
			text: 'CPU',	//info text shown bellow the percentage in the circle	string	''	        
	        textSize: 30,
	        textStyle: "font-size: 16px;",
	        textColor: "#fff",
	        multiPercentage: 1,
	        percentages: [10, 20, 30],
	        foregroundColor: '#3498DB',
			backgroundColor: '#fff',
			//fillColor: '#ccc',
	    });
	    $("#mem-circle").circliful({
	        animation: 2,
	        animationStep: 40,
	        //icon: "f0ae",
			//iconSize: 30,
			//iconColor: '#ccc',
			//iconPosition: 'bottom', //top, bottom, left, right or middle  
	        foregroundBorderWidth: 20,
	        backgroundBorderWidth: 20,
	        percent: '<? echo $memory_real_usage; ?>',
	        fontColor: "#fff",
	        text: 'RAM',
	        textSize: 30,
	        textStyle: "font-size: 16px;",
	        textColor: "#fff",
	        multiPercentage: 1,
	        percentages: [10, 20, 30],
	        foregroundColor: '#3498DB',
			backgroundColor: '#fff',
			//fillColor: '#eee',
	    });	 

		var chart = AmCharts.makeChart("stackedchartdiv", {
			"borderColor": "#ffffff",
			"type": "serial",
			"theme": "none",
			"legend": {
				"useGraphSettings": true,
				"color": "#ffffff",
				"labelWidth": 113, //label breite
				"markerLabelGap": 5, //abstand label zu farbkasten
				"spacing": 10, //abstand zwischen jedem einzelnen label block
				"verticalGap": 10, //abstand zwischen den label bloecken oben und unten
				"marginTop": -50,
				"forceWidth": "false",
			},	
			"switchable": "true",
			"hideBalloonTime": 1,
		    "dataProvider": [{
		        "sprint": "",
		        //"ramtotal": <? echo round(($memory_size / 1000), 1); ?>,
		        "ramreserved": <? echo $memory_reserved; ?>,
		        "ramusage": <? echo round(($memory_real_usage_mb/1000),1); ?>,
		        "rambuffer": <? echo round(($memory_buffer/1000),1); ?>,
		        "ramcache": <? echo round(($memory_cached/1000),1); ?>,
		        "ramfree": <? echo round(($memory_available_real / 1000), 1); ?> ,
		    }],
		    "valueAxes": [{
		      "stackType": "regular",
		      "axisAlpha": 0,
		      "gridAlpha": 0,
		      "color":"#000000",		
		    }],
		    "AxisBase": [{
		      "labelsEnabled": "false",
		
		    }],		    
		   "numberFormatter": {
			  // precision:-1, 
			   decimalSeparator:',', 
			   thousandsSeparator:'.',
			}, 
			"fontSize": 14,	
		    	    
		    "graphs": [{
		        //"labelText": "[[value]] MB",
		        "legendPeriodValueText": "[[value.sum]] MB",
			    "fillAlphas": 1.0,
			    "lineAlpha": 1.0,				
		        "title": "Reserved",
		        "type": "column",
				"color": "#ffffff",
				"lineColor": "#CFCFCF",
		        "valueField": "ramreserved",
		        //"columnWidth": 1.0
		        
		    }, {
		        //"labelText": "[[value]] MB",
		        "legendPeriodValueText": "[[value.sum]] MB",
			    "fillAlphas": 1.0,
			    "lineAlpha": 1.0,				
		        "title": "In Usage",
		        "type": "column",
				"color": "#ffffff",
				"lineColor": "#FFC600",
		        "valueField": "ramusage",
		        //"columnWidth": 0.3
		    }, {
		        //"labelText": "[[value]] MB",
		        "legendPeriodValueText": "[[value.sum]] MB",
		        "fillAlphas": 1.0,
		        "lineAlpha": 1.0,
		        "title": "Buffer",
		        "type": "column",
				"color": "#000000",
				"lineColor": "#21BBEE",
		        "valueField": "rambuffer"
		    }, {
		        //"labelText": "[[value]] MB",
		        "legendPeriodValueText": "[[value.sum]] MB",
		        "fillAlphas": 1.0,
		        "lineAlpha": 1.0,
		        "title": "Cache",
		        "type": "column",
				"color": "#000000",
				"lineColor": "#18BC9A",
		        "valueField": "ramcache"
		    }, {
		        //"labelText": "[[value]] MB",
		        "legendPeriodValueText": "[[value.sum]] MB",
		        "fillAlphas": 1.0,
		        "lineAlpha": 1.0,
		        "title": "Free",
		        "type": "column",
				"color": "#000000",
				"lineColor": "#85C728",
		        "valueField": "ramfree"
		    }],
		    "rotate": true,
		    "categoryField": "sprint",
		    "categoryAxis": {
		        "gridPosition": "start",
		        "axisAlpha": 0,
		        "gridAlpha": 0,
		        "position": "left"
		    },
			"exportConfig":{
		      "menuTop":"0px",
		      "menuItems": [{
		      "icon": '/lib/3/images/export.png',
		      "format": 'png'	  
		      }]  
		    }
		});
		
//////////// GAUGE START //////////7
		$(function () {
		
		    var gaugeOptions = {
		//backgroundColor:  highcharts-background || '#000',
		        chart: {
		            type: 'solidgauge'
		        },
		
		        title: null,
		
		        pane: {
		            center: ['50%', '85%'],
		            size: '140%',
		            startAngle: -90,
		            endAngle: 90,
		            background: {
		                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#ffffff',
		                innerRadius: '90%',
		                outerRadius: '70%',
		                shape: 'arc'
		            }
		        },
		
		        tooltip: {
		            enabled: false
		        },
		
		        // the value axis
		        yAxis: {
		            stops: [
		                [0.1, '#55BF3B'], // green
		                [0.5, '#DDDF0D'], // yellow
		                [0.9, '#DF5353'] // red
		            ],
		            lineWidth: 0,
		            minorTickInterval: null,
		            tickPixelInterval: 400,
		            tickWidth: 0,
		            title: {
		                y: 58
		            },
		            labels: {
		                y: 16
		            }
		        },
		
		        plotOptions: {
		            solidgauge: {
		                dataLabels: {
		                    y: 5,
		                    borderWidth: 0,
		                    useHTML: true
		                }
		            }
		        }
		    };
		
		    // The DOWNLOAD speed gauge
		    $('#container-speed').highcharts(Highcharts.merge(gaugeOptions, {
		        yAxis: {
		            min: 0,
		            max: 6400, //my max download Speed
		            title: {
		                text: 'Download'
		            }
		        },

		        credits: {
		            enabled: false
		        },
		
		        series: [{
		            name: 'Download',
		            data: [<? echo $ByteReceiveRate_kb; ?>],
		            dataLabels: {
		                format: '<div style="text-align:center"><span style="font-size:16px;color:' +
		                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'white') + '">{y}</span><br/>' +
		                       '<span style="font-size:12px;color:white">kb/s</span></div>'
		            },
		            tooltip: {
		                valueSuffix: ' kb/s'
		            }
		        }]
		
		    }));
		
		    // The UPLOAD RPM gauge
		    $('#container-rpm').highcharts(Highcharts.merge(gaugeOptions, {
		        yAxis: {
		            min: 0,
		            max: 1100, //my max uplaod speed
		            title: {
		                text: 'Upload'
		            }
		        },
		
		        series: [{
		            name: 'Upload',
		            data: [<? echo $ByteSendRate_kb; ?>],
		            dataLabels: {
		                format: '<div style="text-align:center"><span style="font-size:16px;color:' +
		                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'white') + '">{y:.1f}</span><br/>' +
		                       '<span style="font-size:12px;color:white">kb/s</span></div>'
		            },
		            tooltip: {
		                valueSuffix: ' kb/s'
		            }
		        }]
		
		    }));
		
		
		
		});

	   

	});
	</script>


	
	<div class="row">
	    <div class="col-lg-2">
	        <div id="cpu-circle"></div>
	    </div>
	    <div class="col-lg-2">
	        <div id="mem-circle"></div>
	    </div>	    

	    <div id="container-speed" style="width: 200px; height: 150px; display: inline-block;"></div>
	    <div id="container-rpm" style="width: 200px; height: 150px; display: inline-block;"></div>
	    
	</div>
	
	<div id="stackedchartdiv"></div>


// saved for later usage, NOT Shown INFOS	
	<h1>CPU, RAM, Network LIVE Module</h1>
	CPU System Load: <? echo $cpu_system_load; ?> % <br>
	CPU User Load:<? echo $cpu_user_load; ?> % <br>
	CPU Other Load: <? echo $cpu_other_load; ?> % <br>
	CPU Total Load: <? echo $cpu_total_load; ?> % <br>
	CPU 1min Load: <? echo $cpu_system_1min_load; ?> <br>
	CPU 5min Load: <? echo $cpu_system_5min_load; ?> <br>
	CPU 15min Load: <? echo $cpu_system_15min_load; ?> <br>
	CPU Device: <? echo $cpu_system_device; ?> <br><br>
	
	Network Device Name: <? echo $network_device; ?> <br>
	Network Download: <? echo $network_rx; ?> kbits<br>
	Network Upload: <? echo $network_tx; ?> kbits<br><br>
	
	Memory Size: <? echo round(($memory_size / 1000), 1); ?> MB<br>
	Memory Real Available: <? echo round(($memory_available_real / 1000), 1); ?> MB<br>
	Memory Real Usage (%): <? echo $memory_real_usage; ?> % <br>
	Memory Real Usage (MB): <? echo round(($memory_real_usage_mb/1000), 1); ?> MB <br>
	Memory SI Disk: <? echo $memory_si_disk; ?> <br>
	Memory SO Disk: <? echo $memory_so_disk; ?> <br>
	Memory Swap Usage (%): <? echo $memory_swap_usage; ?> % <br>
	Memory Total Real: <? echo $memory_total_real; ?> kb<br>
	Memory Swap Available: <? echo $memory_avail_swap; ?> kb<br>
	Memory Buffer: <? echo $memory_buffer; ?> kb<br>
	Memory Cache: <? echo $memory_cached; ?> kb<br>
	Memory Device: <? echo $memory_device; ?> <br><br>


<? require_once('request.SYNO.Logout.php');  
} ?>