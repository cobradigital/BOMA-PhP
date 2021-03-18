<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
class MasterAct extends CI_Controller {
    public function getListTemplateRAP(){
        $name = $this->input->get('name');
        $whereArgs = array();
        if($name!="'"){
            $whereArgs = array('jenis_pekerjaan_parent'=>'like '.$name);
        }
        $sql = getData('',$whereArgs,'','','master_template','');
        $ret = array();
        foreach($sql['row'] as $sq){
            $sq['jenis_pekerjaan_parent'] = getData('',array('id'=>$sq['jenis_pekerjaan_parent']),'','','master_category','')['row'][0]['name_category'];
            $sq['standar_baku'] = getData('',array('id'=>$sq['standar_baku']),'','','master_category','')['row'][0]['name_category'];
            $sq['grup_pekerjaan_utama'] = getData('',array('id'=>$sq['grup_pekerjaan_utama']),'','','master_category','')['row'][0]['name_category'];
            $sq['grup_pelaksana'] = getData('',array('id'=>$sq['grup_pelaksana']),'','','master_category','')['row'][0]['name_category'];
            $sq['list_komponen'] = getData('',array('master_template_id'=>$sq['id']),'','','master_template_child','')['row'];
            array_push($ret,$sq);
        }
        $res = array(
            'result'=>$ret,
            'success'=>true
        );
        echo json_encode($res);
    }

    public function save_template()
    {
        $input = json_decode(file_get_contents("php://input"));
        $input->created_at = date('Y-m-d H:i:s');
        $input->updated_at = date('Y-m-d H:i:s');
        
        $items = $input->items;
        unset($input->items);

        $this->db->trans_start();
        $inputparent = $this->db->insert('master_template',$input);
        if($inputparent){

            $id = $this->db->insert_id();
            $arrayitems = array();
            for($i = 0; $i < sizeof($items); $i++){
                $elm = $items[$i];
                $elm->master_template_id = $id;
                array_push($arrayitems,$elm);
            }
            
            $inputitems = $this->db->insert_batch('master_template_child',$arrayitems);
            if($inputitems){
                $res = returnResultCustom(true,"Berhasil berhasil hore");
                $this->db->trans_commit();
            }else{
                $res = returnResultCustom(false,"Gagal hore");
                $this->db->trans_rollback();
            }
        }else{
            $res = returnResultErrorDB();
        }
        $this->db->trans_complete();

        echo json_encode($res);
    }
}

/* End of file MasterAct.php */
