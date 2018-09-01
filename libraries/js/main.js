$.extend($.fn.dialog.defaults, {
    top:100
});
var startTime = Date.now();
$.extend($.fn.datagrid.defaults, {
    onBeforeDropColumn: function(){
        $(this).datagrid('disableFilter');
    },
    onDropColumn: function(){
        $(this).datagrid('enableFilter');
        $(this).datagrid('doFilter');
    },
    onLoadSuccess: function(){
      //tooltip
      var cells = $(this).datagrid('getPanel').find('div.datagrid-body td[field=remark] div.datagrid-cell:not(:empty)');
      cells.tooltip({
           position:'top',
           content: function() {
              var tp1=$(this).context.innerHTML;
              return tp1.replace(/\n/g, "<br />");
           }
       });

    }
  });
function formatUang(nilai){
    var p=/(\d+)(\d{3})/;
    while(p.test(nilai)){
        nilai=nilai.replace(p,"$1"+","+"$2");
    }
    return nilai;
}
function nl2br(str) {
  var break_tag = '<br>';
  return (str + '').replace(/([^>rn]?)(rn|nr|r|n)/g, '' + break_tag + '');
}