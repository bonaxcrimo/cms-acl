<script>
     $(document).ready(function(){
        $("#memberDiv").hide();
        $('.auto-numeric').autoNumeric('init', {
            'aSep': '.',
            'aDec': ',',
            'vMin': '0',
            'vMax': '999999999999'
        });
        $("#member_name").textbox({
            onChange: function(value){
                console.log('The value has been changed to ' + value);
              },
             icons:[{
                iconCls:'combo-arrow',
                handler:function(){
                    $("#dlgViewLookup").dialog({
                        closed:false,
                        title:"Pilih Member Data",
                        height:350,
                        resizable:true,
                        autoResize:true,
                        width:800
                    });
                }
            }]
        });
        $("#bukaRead").click(function(){
            var text= $("#bukaRead").text()=="New"?"Close":"New";
            var buka= $("#bukaRead").text()=="New"?false:true;
            $("#bukaRead").linkbutton({text:text});
            // $("#member_name").textbox({readonly:buka});
            $("#chinese_name").textbox({readonly:buka});
            $("#address").textbox({readonly:buka});
             $("#handphone").textbox({readonly:buka});
        });
    });
    function add_row(table_id) {

        var row = $('table#'+table_id+' tbody tr:last').clone();
        $("span."+table_id+"_num:first").text('1');
        var n = $("span."+table_id+"_num:last").text();
        var no = parseInt(n);
        var c = no + 1;
        $('table#'+table_id+' tbody tr:last').after(row);
        $('table#'+table_id+' tbody tr:last input').val("");
        $('table#'+table_id+' tbody tr:last input.datepicker').removeAttr('id').removeClass("hasDatepicker").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth:true});;
        $('table#'+table_id+' tbody tr:last input.datepicker_exp').removeAttr('id').removeClass("hasDatepicker").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth:true});;
        $('table#'+table_id+' tbody tr:last div').text("");
        $('table#'+table_id+' tbody tr:last span.span_clear').text("");
        $('table#'+table_id+' tbody tr:last select').prop("selectedIndex", 0);
        $("span."+table_id+"_num:last").text(c);
        $('table#'+table_id+' tbody tr:last span.city_id_box').html("<select name='city_id[]' id='city_id' class='city_id required'><option value=''>--Choose--</option></select>");
        $('.auto-numeric').autoNumeric('destroy');
        $('.auto-numeric').autoNumeric('init', {
            'aSep': '.',
            'aDec': ',',
            'vMin': '0',
            'vMax': '999999999999'
        });
    }
    function del_row(dis, conname) {
        if($('.'+conname).length > 1) {
            $.messager.confirm('Confirm','Yakin akan menghapus data ?',function(r){
                if (r){
                     $(dis).parent().parent().parent().remove();
                }
            });
        }
        else {
            $.messager.alert('Peringatan','Tidak bisa hapus','warning');
        }
    }

</script>
<div>
    <div class="row">
        <div class="col-md-8 noPadding">
<?php
    @$transdate=@$row->transdate==""?Date("d-m-Y"):$row->transdate;
    @$inputdate=@$row->inputdate==""?Date("d-m-Y"):$row->inputdate;
    @$transdate = Date("d-m-Y",strtotime($transdate));
    @$inputdate = Date("d-m-Y",strtotime($inputdate));
?>
            <input type="hidden" name="offering_key" value="<?php echo @$row->offering_key ?>">
            <input type="hidden" name="row_status" value="<?= @$row->row_status ?>">
            <input type="hidden" name="offeringno" value="<?= @$row->offeringno ?>">
            <div style="margin-bottom:10px" id="memberDiv">
                <label class="textbox-label textbox-label-left">memberkey:</label>
                <input name="member_key" id="member"  class="easyui-textbox member" type="hidden"  value="<?= @$row->member_key ?>" style="width:226px">
            </div>
             <div style="margin-bottom:10px" class="inputHide">
                 <label class="textbox-label textbox-label-left">membername:</label>
                <input  id="member_name" name="membername" class="easyui-textbox"  value="<?= @$row->membername ?>"  style="width:226px">
                <a class="easyui-linkbutton" id="bukaRead" text="New"></a>
            </div>
             <div style="margin-bottom:10px" class="inputHide">
                 <label class="textbox-label textbox-label-left">chinesename:</label>
                <input  id="chinese_name" name="chinesename" class="easyui-textbox" readonly="" value="<?= @$row->chinesename ?>"   style="width:226px">
            </div>
              <div style="margin-bottom:10px" class="inputHide">
                <label class="textbox-label textbox-label-left">address:</label>
                <input  id="address" name="address" class="easyui-textbox" readonly="" value="<?= @$row->address ?>"   style="width:226px">
            </div>
             <div style="margin-bottom:10px" class="inputHide">
                <label class="textbox-label textbox-label-left">handphone:</label>
                <input  id="handphone" name="handphone" class="easyui-textbox" readonly="" value="<?= @$row->handphone ?>"   style="width:226px">
            </div>
            <div style="margin-bottom:10px" class="inputHide">
                 <label class="textbox-label textbox-label-left">aliasname:</label>
                <input  id="aliasname2" name="aliasname2" class="easyui-textbox" value="<?= @$row->aliasname2 ?>"   style="width:226px">
            </div>

            <div style="margin-bottom:10px">
                <label class="textbox-label textbox-label-left">transdate:</label>
                <input name="transdate" labelPosition="left" required="" id="transdate" class="easyui-datebox"  value="<?= @$transdate ?>"  style="width:226px;">
            </div>
            <div style="margin-bottom:10px;display: none;">
                <label class="textbox-label textbox-label-left">inputdate:</label>
                <input name="inputdate" labelPosition="left" required="" class="easyui-datebox"  value="<?= @$inputdate ?>"   style="width:226px;">
            </div>
            <div style="margin-bottom:10px">
                <label class="textbox-label textbox-label-left">Remark:</label><span class="textbox easyui-fluid textarea-custom" style="width: 226px;">
                    <textarea name="remark"   class="textbox-text  " style="width: 226px;white-space: pre-line;height: 100px;"><?=@$row->remark?></textarea>
                </span>
            </div>
            <table class="table" id="tblItem" style="width: 600px;">
                <thead>
                    <tr id="header_cart">
                        <th>No</th>
                        <th><label for="offeringid">Offering</label></th>
                        <th><label for="offeringvalue">Value</label></th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no=1;
                        if(@$row->member_key!=''){
                            if(count($row_detail)>0){
                                foreach($row_detail as $r){
                    ?>
                    <tr>
                        <td>
                            <span class="tblItem_num"><?= $no ?></span>
                        </td>
                        <td>
                            <input type="hidden" name="offeringdetail_key[]" value="<?= $r->offeringdetail_key ?>">
                            <select name="offeringid[]"   id="offeringid" >
                                <?php
                                    foreach ($sqloffering as $rowform) {
                                        ?>
                                            <option  value="<?= $rowform->parameter_key ?>" <?php if(@$r->offeringid==$rowform->parameter_key){echo "selected";} ?>><?= $rowform->parameterid ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input id="offer"  name="offeringvalue[]" required=""   class="auto-numeric" type="text" aria-describedby="amount" data-v-max="5000000000" data-v-min="0" data-a-sep="." data-a-dec=","  value="<?= @$r->offeringvalue ?>" style="text-align: right;">
                        </td>
                        <td>
                            <span class="delete_btn">
                                <a href="javascript:;" onclick="del_row(this,'tblItem_del')" class="tblItem_del">[Delete]</a>
                            </span>
                        </td>
                    </tr>
                    <?php
                                    $no++;
                                }
                    ?>
                    <?php

                            }
                        }else{
                    ?>
                     <tr>
                        <td>
                            <span class="tblItem_num">1</span>
                        </td>
                        <td>

                            <select name="offeringid[]"   id="offeringid" >
                                <?php
                                    foreach ($sqloffering as $rowform) {
                                        ?>
                                            <option  value="<?= $rowform->parameter_key ?>" <?php if(@$row->offeringid==$rowform->parameter_key){echo "selected";} ?>><?= $rowform->parameterid ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input id="offer"  name="offeringvalue[]" required=""   class="auto-numeric" type="text" aria-describedby="amount" data-v-max="5000000000" data-v-min="0" data-a-sep="." data-a-dec=","  value="<?= @$row->offeringvalue ?>" style="text-align: right;">
                        </td>
                        <td>
                            <span class="delete_btn">
                                <a href="javascript:;" onclick="del_row(this,'tblItem_del')" class="tblItem_del">[Delete]</a>
                            </span>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>


                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                        <td>
                            <a href="javascript:;" onclick="add_row('tblItem')">Tambah</a>
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div>
        <div class="col-md-4 noPadding">
             <?php
                $url = @$sql->photofile!=""?"medium_".@$sql->photofile:"medium_nofoto.jpg";
            ?>
            <img width="200" class="mediumpic" id="blah" src="<?= base_url() ?>uploads/<?= $url ?>">
        </div>
    </div>
</div>