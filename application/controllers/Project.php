<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->auth();
    }

    public function spk_get()
    {
        $result = array();
        $build = $this->filter($this->input->get(), array("user_id" => $this->userid(), "publish"=> 1));
        $data = $this->Sitemodels->get(true, "projects_spk", "id , projects_id, no_surat, (SELECT project_name FROM projects WHERE id = projects_spk.projects_id) as project_name, (SELECT name_category FROM master_category WHERE id = projects_spk.type_file) as file_type, file_path", $build);

        if($data == '0'){
            $this->response( $this->Result("SUCCESS", array()), 200 );
        }

        $this->response( $this->Result("SUCCESS", $data), 200 );

    }

    public function spk_post()
    {
        $data = array();
        $strPost = $this->security->xss_clean($this->input->post());

        if ( !is_dir("media/attachment/spk/{$this->userid()}"))
            mkdir("media/attachment/spk/{$this->userid()}", 0755, true);
        
        if (isset($_FILES['doc'])) {
            for ($i=0; $i < count($_FILES['doc']['name']); $i++) { 
                $file_name = $this->GenerateID().$_FILES['doc']['name'][$i];
                $tmp_name = $_FILES['doc']['tmp_name'][$i];
                move_uploaded_file($tmp_name, "media/attachment/spk/{$this->userid()}/{$file_name}");

                $data[$i] = array(
                    "projects_id" => $strPost['project_id'],
                    "user_id" => $this->userid(),
                    "type_file" => $strPost['type'],
                    "no_surat" => $strPost['no_surat'],
                    'file_path' => $file_name,
                    'publish' => 1,
                    "created_at" => date("Y-m-d H:i:s"),
                    "created_by" => $this->userid()
                );
            }
        }

        $this->Sitemodels->insert_bulk("projects_spk", $data);

        $this->response( $this->Result("SUCCESS", TRUE), 200 );
    }

    public function spk_delete($idSpk=false)
    {
        if($idSpk){
            $build = $this->filter(false, array("user_id" => $this->userid(), "id" => $idSpk));
            $data = $this->Sitemodels->get(true, "projects_spk", "id", $build);

            if ($data == '0') {
                $this->response( $this->Result("FAILED", "Document Not Found."), 200 );
            }

            $this->Sitemodels->update("projects_spk", array("publish" => 0), array("id" => $idSpk));
            
            $this->response( $this->Result("SUCCESS", TRUE), 200 );
        }
       

        $this->response( $this->Result("FAILED", "Document Not Found."), 200 );
    }
}
