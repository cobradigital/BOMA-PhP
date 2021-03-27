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
        $build = $this->filter($this->input->get());
        $data = $this->Sitemodels->get(true, "projects_spk", "*", $build);
        $this->response( $data, 200 );
    }

    public function spk_post()
    {
        $build = $this->filter($this->input->get());
        $data = $this->Sitemodels->get(true, "projects_spk", "*", $build);
        $this->response( $data, 200 );
    }
}
