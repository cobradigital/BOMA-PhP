<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->auth();
    }

    public function users_get()
    {
        $build = $this->filter($this->input->get(), array("id_merk" => 30, "id_satuan" => 6));
        $data = $this->Sitemodels->get(true, "master_material", "*", $build);
        $this->response( $data, 200 );
        exit();
                
        $this->response( [
            'status' => false,
            'message' => 'No such user found'
        ], 404 );
    }

    public function test($a)
    {
        return $this->db->where($a);
    }
}
