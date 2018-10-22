<script>
// moment().format("MM-YYYY");

$.extend($.fn.datebox.defaults,{
    formatter:function(date){
        return moment(date).format("<?= $format_tgl ?>");
    },
    parser:function(s){
        // if (!s) return new Date();
        // var ss = s.split('\-');
        // var d = parseInt(ss[0],10);
        // var m = parseInt(ss[1],10);
        // var y = parseInt(ss[2],10);
        // if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
        //     return new Date(y,m-1,d);
        // } else {
        //     return new Date();
        // }
    }
});
</script>
</body>
</html>