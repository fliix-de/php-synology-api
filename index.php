<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="google" value="notranslate" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	
    <link rel="stylesheet" href="assets/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/jquery.circliful.css">	
    <link rel="stylesheet" href="assets/css/style.css">
    	
	<script src="assets/js/jquery-2.2.1.min.js"></script>
	<script src="assets/js/jquery.circliful.min.js"></script>	
	<script src="assets/js/amcharts.js"></script>
	<script src="assets/js/serial.js"></script>
	<script src="assets/js/highcharts.js"></script>
	<script src="assets/js/highcharts-more.js"></script>
	<script src="assets/js/solid-gauge.js"></script>
	<script src="assets/js/app.js"></script>
</head>
<body>
	<header>
		<!-- MANUAL DEBUG BUTTONS	 -->
		<input type="button" value="callSYNOCoreSystemUtilization" onclick="callSYNOCoreSystemUtilization();" />
		<input type="button" value="callSYNOCoreCurrentConnection" onclick="callSYNOCoreCurrentConnection();" />
		<input type="button" value="callSYNOCoreSystemBasic" onclick="callSYNOCoreSystemBasic();" />
		<input type="button" value="callSYNOCoreSystemNetwork" onclick="callSYNOCoreSystemNetwork();" />
		<input type="button" value="callSYNOCoreSystemStorage" onclick="callSYNOCoreSystemStorage();" />	
		<input type="button" value="callSYNOCoreSyslogClientLog" onclick="callSYNOCoreSyslogClientLog();" />
	</header>

	<section>
		<div id="synology">
			<div id="left-data-block">	
				<div id="synoDataCoreSystemBasic"></div>
				<div id="synoDataCoreSystemUtilizationLive"></div>	
			</div>
			<div id="right-data-block">
				<div id="synoDataCoreSystemStorage"></div>	
			</div>		
			<div style="clear: both"></div>
		</div>
	</section>




	
<!-- -->	
<div id="synoCoreCurrentConnection"></div>	
<div id="synoDataCoreSystemNetwork"></div>	
<div id="synoDataCoreSystemStorage"></div>			
<div id="synoDataCoreSyslogClientLog"></div>

	<footer>
		<p>Copyright 2019 Florian Müller</p>
	</footer>
</body>
</html>