<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
// require_once APPPATH . '/libraries/RestController.php';
require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
require APPPATH . 'libraries/JWT.php';
require APPPATH . 'libraries/BeforeValidException.php';
require APPPATH . 'libraries/ExpiredException.php';
require APPPATH . 'libraries/SignatureInvalidException.php';
use \Firebase\JWT\JWT;

use chriskacerguis\RestServer\RestController;


class MY_Controller extends RestController
{

    protected $user_data;

    protected  $filters = [
        'filter_id' => array("MATCH"=>"master_material.id"),
        'filter_id_in' => array("IN"=>"master_material.id"),
        'filter_name' => array("LIKE"=>"master_material.nama_material")
    ];
    

    public function auth()
    {
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

        //JWT Auth middleware
        $headers = $this->input->get_request_header('Authorization');

        $kunci = $this->config->item('thekey'); 
        $token= "token";
       	if (!empty($headers)) {
        	if (preg_match('/Bearer\s(\S+)/', $headers , $matches)) {
            $token = $matches[1];
        	}
    	}
        try {
           $decoded = JWT::decode($token, $kunci, array('HS256'));
           $this->user_data = $decoded;
           return $this->user_data;
        } catch (Exception $e) {
            $invalid = ['code' => 401, 'message' => $e->getMessage()]; //Respon if credential invalid
            $this->response($invalid, 401);//401
        }
    }

    public function userid()
    {
        return $this->user_data->sub;
    }

    public function filter($filter, $custom=false)
    {
        $q = $this->db;

        foreach ($filter as $key => $value) {
            if (array_key_exists($key, $this->filters)) {
                if (!empty($value)){

                    if (array_key_exists('MATCH', $this->filters[$key])) {
                        $q = $this->db->where($this->filters[$key]["MATCH"], $value);
                    }
        
                    if (array_key_exists('LIKE', $this->filters[$key])) {
                        $q = $this->db->like($this->filters[$key]["LIKE"], $value);
                    }

                    if (array_key_exists('IN', $this->filters[$key])) {
                        $q = $this->db->where_in($this->filters[$key]["IN"], explode(",",$value));

                    }
                }
            }

        }

        if($custom){
            $q = $this->db->where($custom);
        }

        return $q;
    }
}