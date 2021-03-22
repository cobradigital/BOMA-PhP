<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
class DocumentsAct extends CI_Controller {
    public function spk_upload()
    {
        $arr = $this->input->post();
        $file_up = uploadPhoto($arr['type_file'],'file','multiple');
        unset($arr['file']);

        $arr['file_path'] = $file_up;
        $sql = $this->db->insert('projects_spk',$arr);
        if($sql){
            $res = returnResultCustom(true,'Berhasil upload SPK');
        }else{
            $res = returnResultCustom(false,'Berhasil upload SPK');
        }
        echo json_encode($res);
        
    }
}