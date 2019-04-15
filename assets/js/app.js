AmCharts.themes.none={};
//************************************************************
//*************  AJAX CALLS START  ***************************
//************************************************************
function callSYNOCoreSystemUtilization() {
	var request = $.ajax({
		url: "assets/php/request.SYNO.Core.System.Utilization.php",
		type: "GET",			
		dataType: "html"
	});
	request.done(function(synoData) {
		$("#synoDataCoreSystemUtilizationLive").html(synoData);			
	});
	request.fail(function(jqXHR, textStatus) {
		console.log( "Request failed: " + textStatus );
	});
} callSYNOCoreSystemUtilization();
// REFRESH SYNOCoreSystemUtilization DATA every 1 minute
window.setInterval(function(){	
	callSYNOCoreSystemUtilization();
}, 600000);


function callSYNOCoreCurrentConnection() {
	var request = $.ajax({
		url: "assets/php/request.SYNO.Core.CurrentConnection.php",
		type: "GET",			
		dataType: "html"
	});
	request.done(function(synoData) {
		$("#synoCoreCurrentConnection").html(synoData);			
	});
	request.fail(function(jqXHR, textStatus) {
		console.log( "Request failed: " + textStatus );
	});
} callSYNOCoreCurrentConnection();
// REFRESH SYNOCoreCurrentConnection DATA every 86400000 seconds (1 day)
window.setInterval(function(){	
	callSYNOCoreCurrentConnection();
}, 86400000);
	

function callSYNOCoreSystemBasic() {
	var request = $.ajax({
		url: "assets/php/request.SYNO.Core.System.Basic.php",
		type: "GET",			
		dataType: "html"
	});
	request.done(function(synoData) {
		$("#synoDataCoreSystemBasic").html(synoData);			
	});
	request.fail(function(jqXHR, textStatus) {
		console.log( "Request failed: " + textStatus );
	});
} callSYNOCoreSystemBasic();
// REFRESH SYNOCoreSystemBasic DATA every 30min
window.setInterval(function(){	
	callSYNOCoreSystemBasic();
}, 1800000);		

function callSYNOCoreSystemNetwork() {
	var request = $.ajax({
		url: "assets/php/request.SYNO.Core.System.Network.php",
		type: "GET",			
		dataType: "html"
	});
	request.done(function(synoData) {
		$("#synoDataCoreSystemNetwork").html(synoData);			
	});
	request.fail(function(jqXHR, textStatus) {
		console.log( "Request failed: " + textStatus );
	});
} callSYNOCoreSystemNetwork();
// REFRESH SYNOCoreSystemNetwork DATA every 1 hour
window.setInterval(function(){	
	callSYNOCoreSystemNetwork();
}, 3600000); 


function callSYNOCoreSystemStorage() {
	var request = $.ajax({
		url: "assets/php/request.SYNO.Core.System.Storage.php",
		type: "GET",			
		dataType: "html"
	});
	request.done(function(synoData) {
		$("#synoDataCoreSystemStorage").html(synoData);			
	});
	request.fail(function(jqXHR, textStatus) {
		console.log( "Request failed: " + textStatus );
	});
} callSYNOCoreSystemStorage();
// REFRESH SYNOCoreSystemStorage DATA every 25 minutes
window.setInterval(function(){	
	callSYNOCoreSystemStorage();
}, 1500000);	

function callSYNOCoreSyslogClientLog() {
	var request = $.ajax({
		url: "assets/php/request.SYNO.Core.SyslogClient.Log.php",
		type: "GET",			
		dataType: "html"
	});
	request.done(function(synoData) {
		$("#synoDataCoreSyslogClientLog").html(synoData);			
	});
	request.fail(function(jqXHR, textStatus) {
		console.log( "Request failed: " + textStatus );
	});
} callSYNOCoreSyslogClientLog();