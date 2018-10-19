<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Extension extends MY_Controller {
    public function __construct(){
        parent::__construct();
        session_start();
        $this->load->model([
            'Macos'
        ]);
    }
    public function download($filename){
        $this->load->helper('download');
        $data = file_get_contents('uploads/'.$filename);
        force_download($filename,$data);
    }
    public function lookup_aco(){
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'acosid';
        $order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';
        $filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';
        $cond = '';
        if (!empty($filterRules)){
            $cond = ' where 1=1 ';
            $filterRules = json_decode($filterRules);
            foreach($filterRules as $rule){
                $rule = get_object_vars($rule);
                $field = $rule['field'];
                $op = $rule['op'];
                $value = $rule['value'];
                if (!empty($value)){
                    if ($op == 'contains'){
                        $cond .= " and ($field like '%$value%')";
                    }
                }
            }
        }
        $sql = $this->Macos->count($cond);
        $total = $sql->num_rows();
        $offset = ($page - 1) * $rows;
        $data = $this->Macos->get($cond,$sort,$order,$rows,$offset)->result();

        $response = new stdClass;
        $response->total=$total;
        $response->rows = $data;
        $response->allData = $this->Macos->get($cond,$sort,$order,$total,$offset)->result();
        echo json_encode($response);
    }
    public function set(){
        $_SESSION['member_key'] = $_GET['member_key'];
    }
    public function image($image){
        $data["image"] = $image;
        $this->load->view('jemaat/image',$data);
    }
    public function printupdate(){
        $no = $_POST['noOffering'];
        $sql="update tbloffering set printedon = '".date("Y-m-d H:i:s")."',printedby='".$_SESSION['username']."' where offering_key= ".$no;
        $check = $this->db->query($sql);
        $gagal=0;
        if(!$check){
            $gagal=1;
        }
        $hasil = array(
            'status' => $gagal==0?"Sukses":"Gagal"
        );
        echo json_encode($hasil);
    }
    public function uploadWA($namephotofile){
        $filename = $_FILES['photofile']['name'];
        if($filename){
            $temp = $_FILES['photofile']['tmp_name'];
            $type = $_FILES['photofile']['type'];
            $size = $_FILES['photofile']['size'];
            $newfilename = $namephotofile;
            @$vdir_upload = "uploads/";
            @$directory     = "uploads/$newfilename";
            if (MOVE_UPLOADED_FILE($temp,$directory)){
                $im_src = imagecreatefromjpeg($directory);
                $src_width = imagesx($im_src);
                $src_height = imagesy($im_src);
                $dst_width = 30;
                $dst_height = ($dst_width/$src_width)*$src_height;
                $im = imagecreatetruecolor($dst_width,$dst_height);
                imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
                imagejpeg($im,$vdir_upload."small_".$newfilename);
                imagedestroy($im_src);
                imagedestroy($im);

                $im_src2 = imagecreatefromjpeg($directory);
                $src_width2 = imagesx($im_src2);
                $src_height2 = imagesy($im_src2);
                // start
                $actualHeight  =  imagesy($im_src2);
                $actualWidth   = imagesx($im_src2);
                $maxHeight = 1280;
                $maxWidth = 1280;
                $imgRatio = $actualWidth / $actualHeight;
                $maxRatio = $maxWidth/$maxHeight;
                if($actualHeight>$maxHeight || $actualWidth > $maxWidth){
                    if($imgRatio < $maxRatio){
                        //menyesuaikan lebar menurut maxHeight
                        $imgRatio = $maxHeight / $actualHeight;
                        $actualWidth = $imgRatio * $actualWidth;
                        $actualHeight = $maxHeight;
                    }else if($imgRatio > $maxRatio){
                        //menyesuaikan tinggi menurut maxWidth
                        $imgRatio = $maxWidth / $actualWidth;
                        $actualHeight = $imgRatio * $actualHeight;
                        $actualWidth = $maxWidth;
                    }else{
                        $actualHeight = $maxHeight;
                        $actualWidth = $maxWidth;
                    }
                }
                //end
                $im2 = imagecreatetruecolor($actualWidth,$actualHeight);
                imagecopyresampled($im2, $im_src2, 0, 0, 0, 0, $actualWidth, $actualHeight, $src_width2, $src_height2);
                imagejpeg($im2,$vdir_upload."medium_".$newfilename);
                imagedestroy($im_src2);
                imagedestroy($im2);
                unlink("uploads/$newfilename");
                $status = 1;
                $msg ="Upload Success";
            }
            else{
                $status = 2;
                $msg ="Upload Error";
            }
        }
        else{
            $status = 2;
            $msg ="Upload Null";
        }

        $hasil = array(
            'status' => $status,
            'msg' => $msg
        );
        echo json_encode($hasil);
    }
}