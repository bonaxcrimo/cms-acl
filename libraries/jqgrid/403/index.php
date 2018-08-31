<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Test</title>
        <!-- Mengincludekan JQueryUI CSS. Rename nama sunny dan sesuaikan Folder yg ada di dalam Folder CSS -->
        <link rel="stylesheet" type="text/css" media="screen" href="./css/sunny/jquery-ui-1.8.16.custom.css" />
        <!-- Mengincludekan CSS Jqgrid -->
        <link rel="stylesheet" type="text/css" media="screen" href="./css/ui.jqgrid.css" />

        <!-- Sedikit CSS agar lebih bagus -->
        <style>
            body{
                font-family: verdana, tahoma, arial;
                font-size: 8pt;
            }
            .button{
                font-size: 8pt;			
            }
            fieldset 
            {
                border:solid 1px #8E846B;
                padding:10px 10px 10px 20px;
                width: 240px;
                -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
            }
            legend 
            {
                padding: 3px 15px 3px 10px;
                font:bold 1em "Trebuchet MS", Verdana, Helvetica, Arial, sans-serif;
                font-weight:bold;
                color:#666;
                text-transform:uppercase;
                border:1px solid #8E846B;
                background:#f4f4f4;
                letter-spacing:2px
                    -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
            }
        </style>

        <!-- Mengincludekan Library Jquery -->
        <script src="js/jquery-1.5.2.min.js" type="text/javascript"></script>

        <!-- Kita menggunakan Library Jquery UI untuk mempercantik Button2 nya -->
        <!-- Mengincludekan Library Jquery UI-->
        <script src="js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>

        <!-- Mengincludekan Locale untuk JQGrid -->
        <script src="js/i18n/grid.locale-en.js" type="text/javascript"></script>
        <!-- Mengincludekan Library untuk JQGrid -->
        <script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(document).ready(function(){

                jQuery("#toolbar").jqGrid({
                    url:'json.php',
                    datatype: "json",
                    height: 255,
                    autowidth: true,
                    colNames:['recno','membername', 'phoneticname'],
                    colModel:[
                        {name:'recno',index:'recno', sorttype:'int'},
                        {name:'membername',index:'membername' },
                        {name:'phoneticname',index:'phoneticname'}
                    ],
                    rowNum:50,
                    rowList : [20,30,50],
                    loadonce:false,
                    mtype: "GET",
                    rownumbers: true,
                    rownumWidth: 40,
                    gridview: true,
                    pager: '#ptoolbar',
                    sortname: 'recno',
                    viewrecords: true,
                    sortorder: "asc",
                    caption: "Toolbar Searching"	
                });

                jQuery("#toolbar").jqGrid('navGrid','#ptoolbar',
                    {del:false,add:false,edit:false,search:false},
                    { multipleSearch: true, overlay: false });
                jQuery("#toolbar").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

            });
        </script>
    </head>
    <body>

        <table id="toolbar"></table>
        <div id="ptoolbar" ></div>


    </body>
</html>