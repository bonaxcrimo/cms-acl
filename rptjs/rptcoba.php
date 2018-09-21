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
		$(document).keydown(function(e) {
		    // ESCAPE key pressed
		    if (e.keyCode == 27) {
		        window.close();
		    }
		});
		var options = new Stimulsoft.Viewer.StiViewerOptions();
		options.appearance.fullScreenMode = true;
		options.toolbar.showSendEmailButton = false;
		// options.toolbar.showPrintButton = false;
		options.toolbar.showViewModeButton = false;
		var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
		//costum componen
		StiJsViewer.prototype.InitializePrintMenu = function() {

	        var A = [];
	        A.push(this.Item("PrintWitPreview", this.collections.loc.PrintWithPreview, "PrintWithPreview.png", "PrintWithPreview"));
	        var t = this.VerticalMenu("printMenu", this.controls.toolbar.controls.Print, "Down", A);
	        t.action = function(A) {
	            t.changeVisibleState(!1), t.jsObject.postPrint(A.key)
	        }
	    }
		// Process SQL data source
		viewer.onBeginProcessData = function (event, callback) {
			<?php StiHelper::createHandler(); ?>
		}
		viewer.onBeginExportReport = function (args) {
			// args.fileName = "MyReportName";
		}
		viewer.onPrintReport = function(event){

		}


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
		report.dictionary.variables.list.forEach(function(item, i, arr) {
		    if (typeof vars[item.name] != "undefined") item.valueObject = vars[item.name];
		});
		viewer.report = report;
		viewer.renderHtml("viewerContent");

		var userButton = viewer.jsObject.SmallButton("userButton", "Close", "emptyImage");
		userButton.action = function () { window.open('','_parent','');window.close() }

		var toolbarTable = viewer.jsObject.controls.toolbar.firstChild.firstChild;
		var buttonsTable = toolbarTable.rows[0].firstChild.firstChild;
		var userButtonCell = buttonsTable.rows[0].insertCell(0);
		console.log(buttonsTable.rows[0]);
		userButtonCell.className = "stiJsViewerClearAllStyles";
		userButtonCell.appendChild(userButton);
	</script>
	</head>
<body>
	<div id="viewerContent" ></div>
</body>
</html>
