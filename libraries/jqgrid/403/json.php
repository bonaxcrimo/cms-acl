<?php

$db = mysql_connect("localhost", "root", "");
$con = mysql_select_db("cms", $db);
if (!$con) {
    echo "Something Problem";
}

$page = $_GET['page']; // get the GETed page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if (!$sidx)
    $sidx = 1;

$totalrows = isset($_GET['totalrows']) ? $_GET['totalrows'] : false;
if ($totalrows) {
    $limit = $totalrows;
}
$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : false;
$searchString = isset($_GET['searchString']) ? $_GET['searchString'] : false;


$filters = $_GET['filters'];

        $where = "";
        if (isset($filters)) {
            $filters = str_replace('\"','"' ,$filters);
            $filters = json_decode($filters);
            $where = " where ";
            $whereArray = array();
            $rules = $filters->rules;

            foreach($rules as $rule) {
                $whereArray[] = $rule->field." like '%".$rule->data."%'";
            }
            if (count($whereArray)>0) {
                $where .= join(" and ", $whereArray);
            } else {
                $where = "";
            }
        }    

//populateDBRandom();
$result = mysql_query("SELECT COUNT(*) AS count FROM tblmember");
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$count = $row['count'];
//echo $count;
if ($count > 0) {
    $total_pages = ceil($count / $limit);
    //echo $total_pages;
} else {
    $total_pages = 0;
}
if ($page > $total_pages)
    $page = $total_pages;
    //echo $page;
if ($limit < 0)
    $limit = 0;
$start = $limit * $page - $limit; // do not put $limit*($page - 1)
if ($start < 0)
    $start = 0;
$SQL = "SELECT recno, membername, phoneticname FROM tblmember " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit";
$result = mysql_query($SQL) or die("Couldnot execute query." . mysql_error());
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i = 0;
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $responce->rows[$i]['id'] = $row[recno];
    $responce->rows[$i]['cell'] = array($row[recno],$row[membername], $row[phoneticname]);
    $i++;
}
echo json_encode($responce);
mysql_close($db);
?>