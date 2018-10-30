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
                    <input class="easyui-datebox" name="mulai" id="mulai" label="Tgl Awal:" labelPosition="left" required style="width:400px" data-options="formatter:formatTgl,parser:parserTgl">
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
    function formatTgl(date){
        return moment(date).format("<?= $format_tgl ?>");
    }
    function parserTgl(date){

    }
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
            var tgl=moment(date).format("<?= $format_tgl ?>");
            $("#selesai").datebox({
                validType:"md['"+tgl+"']"
            })
        }
    });
    $.extend($.fn.validatebox.defaults.rules, {
        md: {
            validator: function(value, param){
                var a=moment(param[0],"DD-MM-YYYY");
                var b =moment(value,"DD-MM-YYYY");
                return b.isSameOrAfter(a);
            },
            message: 'The date must be greater than or equals {0}.'
        }
    })
</script>