<?php
require_once 'stimulsoft/helper.php';

error_reporting(0);

// Please configure the security level as you required.
// By default is to allow any requests from any domains.
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Engaged-Auth-Token");


$handler = new StiHandler();
$handler->registerErrorHandlers();


$handler->onBeginProcessData = function ($event) {
	// Current database type: 'XML', 'JSON', 'MySQL', 'MS SQL', 'PostgreSQL', 'Firebird', 'Oracle'
	$database = $event->database;
	// Current connection name
	$connection = $event->connection;
	// Current data source name
	$dataSource = $event->dataSource;
	// Connection string for the current data source
	$connectionString = $event->connectionString;
	// SQL query string for the current data source
	$queryString = $event->queryString;
	// You can change the connection string
	$event->connectionString = "server=localhost;database=cms3;port=3306;Convert Zero Datetime=True;uid=root;pwd=root;";

	// $event->parameters['filter']='{filter}';
	// You can change the SQL query
	//if ($dataSource == "MyDataSource")
	// $event->queryString = "select tbloffering.*,NOW() as tglcetak,UPPER(f_terbilang(offeringvalue)) as terbilang,tblparameter.parameterid from tbloffering left join tblparameter on tblparameter.parameter_key = tbloffering.offeringid order by offeringid desc limit 1";

	// You can replace the SQL query parameters with the required values
	// For example: SELECT * FROM {Variable1} WHERE Id={Variable2}
	// If the report contains a variable with this name, its value will be used instead of the specified value
	//$event->parameters["Variable1"] = "TableName";
	//$event->parameters["Variable2"] = 10;

	return StiResult::success();
	//return StiResult::error("Message for some connection error.");
};

$handler->onPrintReport = function ($event) {
	return StiResult::success();
};

$handler->onBeginExportReport = function ($event) {
	$settings = $event->settings;
	$format = $event->format;
	return StiResult::success();
};

$handler->onEndExportReport = function ($event) {
	$format = $event->format; // Export format
	$data = $event->data; // Base64 export data
	$fileName = $event->fileName; // Report file name

	file_put_contents('reports/'.$fileName.'.'.strtolower($format), base64_decode($data));

	//return StiResult::success();
	return StiResult::success("Export OK. Message from server side.");
	//return StiResult::error("Export ERROR. Message from server side.");
};

$handler->onEmailReport = function ($event) {
	$event->settings->from = "******@gmail.com";
	$event->settings->host = "smtp.gmail.com";
	$event->settings->login = "******";
	$event->settings->password = "******";
};

$handler->onDesignReport = function ($event) {
	return StiResult::success();
};

$handler->onCreateReport = function ($event) {
	$fileName = $event->fileName;
	return StiResult::success();
};

$handler->onSaveReport = function ($event) {
	$report = $event->report; // Report object
	$reportJson = $event->reportJson; // Report JSON
	$fileName = $event->fileName; // Report file name

	file_put_contents('reports/'.$fileName.".mrt", $reportJson);

	//return StiResult::success();
	return StiResult::success("Save Report OK: ".$fileName);
	//return StiResult::error("Save Report ERROR. Message from server side.");
};

$handler->onSaveAsReport = function ($event) {
	return StiResult::success();
};

$handler->process();