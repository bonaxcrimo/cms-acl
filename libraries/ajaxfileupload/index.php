<html>
<head>
<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="ajaxfileupload.js"></script>
<script type="text/javascript">
    $(function(){
        $("#frm").submit(function(){
            $("#pesan").ajaxStart(function(){
                $(this).show();
            }).ajaxComplete(function(){
                $(this).hide();
            });
            $.ajaxFileUpload({
                url: "upload.php",
                secureuri: false,
                fileElementId: "file_input",
                dataType: "json",
                success: function (json, status){
                    if(json.status==1){
                        $('td#filename').html(json.filename);
                        $('td#size').html(json.size);
                        $('td#type').html(json.type);
                    }else{
                        alert('Upload GAGAL!');
                    }
                }
            });
            return false;
        });
    });
</script>


<script type="text/javascript">
    $(function(){
        $("#frm").submit(function(){
            $("#pesan").ajaxStart(function(){
                $(this).show();
            }).ajaxComplete(function(){
                $(this).hide();
            });
            $.ajaxFileUpload({
                url: "upload.php",
                secureuri: false,
                fileElementId: "file_input",
                dataType: "json",
                success: function (json, status){
                    if(json.status==1){
                        $('td#filename').html(json.filename);
                        $('td#size').html(json.size);
                        $('td#type').html(json.type);
                    }else{
                        alert('Upload GAGAL!');
                    }
                }
            });
            return false;
        });
    });
</script>

</head>
<body>
<form id="frm" enctype="multipart/form-data" method="post">
    File <input id="file_input" type="file" name="file_input"> <input type="submit" value="Upload">
</form>

<div id="pesan" style="display: none">Silakan tunggu...</div>
        <div id="result">
            <table>
                <tr>
                    <td>Nama file</td>
                    <td id="filename"></td>
                </tr>
                <tr>
                    <td>Ukuran</td>
                    <td id="size"></td>
                </tr>
                <tr>
                    <td>Type</td>
                    <td id="type">Type</td>
                </tr>
            </table>
        </div>

       </body>
       </html>