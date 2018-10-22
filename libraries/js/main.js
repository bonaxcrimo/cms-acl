$.extend($.fn.dialog.defaults, {
    top:100
});

// $.extend($.fn.datagrid.methods, {
//    onLoadSuccess: function(){
//        $.parser.parse($(this).datagrid('getPanel'));
//        console.log("masuk");
//     }
// });
var startTime = Date.now();
$.extend($.fn.datagrid.defaults, {
    onLoadSuccess: function(){
      // var endTime = Date.now();
      // var dif = startTime - endTime;

      // var Seconds_from_T1_to_T2 = dif / 1000;
      // var Seconds_Between_Dates = Math.abs(Seconds_from_T1_to_T2);

      // $.messager.show({
      //     title:'Info',
      //     msg:'Load  successfully at <span style="color:red; font-weight:bold">' + Seconds_Between_Dates + '</span> seconds'
      // });
      // startTime =endTime;

      //tooltip
      $(this).datagrid('columnMoving');
      var cells = $(this).datagrid('getPanel').find('div.datagrid-body td[field=remark] div.datagrid-cell:not(:empty)');
      cells.tooltip({
           position:'top',
           content: function() {
              var tp1=$(this).context.innerHTML;
              return tp1.replace(/\n/g, "<br />");
           }
       });

    },
      onBeforeDropColumn: function(){
          $(this).datagrid('disableFilter');
      },
      onDropColumn: function(){
          $(this).datagrid('enableFilter');
          $(this).datagrid('doFilter');
      }
  })
// $.extend($.fn.datagrid.defaults, {
//     onSelect: function(index, row){
//       var opts = $(this).datagrid('options');
//       if (opts.onSelectRow){
//         opts.onSelectRow.call(this, index, row);
//       }
//       if (opts.selectedIndex != index){
//         opts.selectedIndex = index;
//         if (opts.onSelectChanged){
//           opts.onSelectChanged.call(this, index, row);
//         }
//       }
//     }
//   })
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