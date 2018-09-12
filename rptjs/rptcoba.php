<?php
require_once 'stimulsoft/helper.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Invoice</title>

	<!-- Report Office2013 style -->
	<link href="css/stimulsoft.viewer.office2013.whiteteal.css" rel="stylesheet">

	<!-- Stimusloft Reports.JS -->
	<script src="scripts/stimulsoft.reports.js" type="text/javascript"></script>
	<script src="scripts/stimulsoft.viewer.js" type="text/javascript"></script>
	<script src="scripts/jquery.min.js" type="text/javascript"></script>
	<?php StiHelper::initialize(); ?>
	<script type="text/javascript">
		var options = new Stimulsoft.Viewer.StiViewerOptions();
		options.appearance.fullScreenMode = true;
		options.toolbar.showSendEmailButton = true;

		var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);

		// Process SQL data source
		viewer.onBeginProcessData = function (event, callback) {
			<?php StiHelper::createHandler(); ?>
		}
		viewer.onBeginExportReport = function (args) {
			// args.fileName = "MyReportName";
		}
		viewer.onPrintReport = function(event){
			console.log("<?php session_start(); json_encode($_SESSION) ?>");
		}

		// var result = (sender as StiReport).PrinterSettings.PrintDialogResult;
		// if (result  == DialogResult.OK || result  == DialogResult.None)
		// {
		// 	console.log("Print Selesai");
		// }

		// Send exported report to server side
		/*viewer.onEndExportReport = function (event) {
			event.preventDefault = true; // Prevent client default event handler (save the exported report as a file)
			<?php StiHelper::createHandler(); ?>
		}*/

		// Send exported report to Email
		viewer.onEmailReport = function (event) {
			<?php StiHelper::createHandler(); ?>
		}

		// Load and show report
		var report = new Stimulsoft.Report.StiReport();

		report.loadFile("reports/rptcoba.mrt");
		function getUrlVars() {
		    var vars = {};
		    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
		        function (m, key, value) {
		            vars[key] = value;
		    });
		    return vars;
		}

		var vars = getUrlVars();
		// console.log(vars);
		report.dictionary.variables.list.forEach(function(item, i, arr) {
		    if (typeof vars[item.name] != "undefined") item.valueObject = vars[item.name];
		});

		viewer.report = report;
		viewer.renderHtml("viewerContent");

	</script>
	</head>
<body>
	<div id="viewerContent"></div>
</body>
</html>
