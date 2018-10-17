<div class="easyui-tabs" style="height:auto">
    <div title="Report Offering" style="padding:10px">
        <div style="padding: 20px;">
            <form method="post" id="fmfilter">
                <div style="margin-bottom:10px">
                    <select name="filter"  labelPosition="left" label="Pilih field filter" class="easyui-combobox"  style="width:400px;">
                        <option value="transdate">Tanggal Transaksi</option>
                        <option value="inputdate">Tanggal Entry</option>
                    </select>
                </div>
                <div style="margin-bottom:20px">
                    <input class="easyui-datebox" name="mulai" id="mulai" label="Tgl Awal:" labelPosition="left" required style="width:400px">
                </div>
                <div style="margin-bottom:20px">
                    <input class="easyui-datebox" name="selesai" id="selesai" label="Tgl Akhir:" labelPosition="left" required  style="width:400px;">
                </div>
                 <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="prosesFilter()" style="width:90px" id="btnSave">Filter</a>
            </form>

        </div>

    </div>
</div>
<script>
    function prosesFilter(){
        $('#fmfilter').form('submit',{
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                result=$.parseHTML(result)[0].textContent;
                window.open(result,'_blank');
            },error:function(error){
                 console.log($(this).serialize());
            }
        });
    }

    $('#mulai').datebox({
        onSelect: function(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            var tgl=m+'/'+d+'/'+y;
            // var tgl=$("#mulai").datebox('getValue');
            $("#selesai").datebox({
                validType:"md['"+tgl+"']"
            })
        }
    });
    $.extend($.fn.validatebox.defaults.rules, {
        md: {
            validator: function(value, param){
                var d1 = $.fn.datebox.defaults.parser(param[0]);
                var d2 = $.fn.datebox.defaults.parser(value);
                return d2>=d1;
            },
            message: 'The date must be greater than or equals {0}.'
        }
    })
</script>