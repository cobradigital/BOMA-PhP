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
        $idpek = ($this->input->post('pekerjaan'));
        $sb = $this->input->post('standar_baku');
        $jpp = $this->input->post('jenis_pekerjaan_parent');
        $whereArgs = array();
        if($name!=""){
            // $whereArgs = array('jenis_pekerjaan_parent'=>'like '.$name);
            $whereArgs = array('master_type_id'=>'38','name_category'=>'like '.$name);
        }
        $getdatacat = getData('',$whereArgs,'','','master_category','');
        
        if($getdatacat['rowCount']>0){
            $whereArgsBawah = array();
            if($name!=""){
                $arrayid = array();
                foreach($getdatacat['row'] as $gc){
                    array_push($arrayid,$gc['id']);
                }
                $idarr = implode(",",$arrayid);
                $whereArgsBawah = array('jenis_pekerjaan_parent'=> 'in '.$idarr);
            }else if($idpek!=""){
                $idnya = array();
                for($i = 0; $i <sizeof($idpek);$i++){
                    array_push($idnya, $idpek[$i]);
                }
                $newid = implode(",",$idnya);
                $whereArgsBawah = array('id'=>'in '.$newid);
            }else if($sb!="" && $jpp!=""){
                $whereArgsBawah = array(
                    'standar_baku'=>$sb,
                    'jenis_pekerjaan_parent'=>$jpp
                );
            }
            $sql = getData('',$whereArgsBawah,'','','master_template','');
            
            $ret = array();
            foreach($sql['row'] as $sq){
                $sq['jenis_pekerjaan_parent_teks'] = getData('',array('id'=>$sq['jenis_pekerjaan_parent']),'','','master_category','')['row'][0]['name_category'];
                $sq['standar_baku_teks'] = getData('',array('id'=>$sq['standar_baku']),'','','master_category','')['row'][0]['name_category'];
                $sq['grup_pekerjaan_utama_teks'] = getData('',array('id'=>$sq['grup_pekerjaan_utama']),'','','master_category','')['row'][0]['name_category'];
                $sq['grup_pelaksana_teks'] = getData('',array('id'=>$sq['grup_pelaksana']),'','','master_category','')['row'][0]['name_category'];
                $sq['list_komponen'] = getData('',array('master_template_id'=>$sq['id']),'','','master_template_child','')['row'];
                array_push($ret,$sq);
            }
            $res = array(
                'result'=>$ret,
                'success'=>true
            );
        }else{
            $res = returnResultErrorDB();
        }
        echo json_encode($res);
    }

    public function delete_template()
    {
        $id = $this->input->post('id');
        if($id==""){
            $res = returnResult(false,'ID Not Found');
        }else{
            $this->db->where('master_template_id',$id);
            $sql = $this->db->delete('master_template_child');
            if($sql){
                $this->db->where('id',$id);
                $this->db->delete('master_template');
                $res = returnResultCustom(true,"berhasil");
            }else{
                $res = returnResultErrorDB();
            }
        }
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
        if($input->id!=""){
            $this->db->where('id',$input->id);
            $inputparent = $this->db->update('master_template',$input);
        }else{
            $inputparent = $this->db->insert('master_template',$input);
        }
        if($inputparent){
            $id = $this->db->insert_id();
            if($input->id!=""){
                $id = $input->id;
                $this->db->where('master_template_id',$id);
                $this->db->delete('master_template_child');
            }
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
