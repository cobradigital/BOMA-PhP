<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
require APPPATH . 'libraries/JWT.php';
require APPPATH . 'libraries/BeforeValidException.php';
require APPPATH . 'libraries/ExpiredException.php';
require APPPATH . 'libraries/SignatureInvalidException.php';
require APPPATH . 'libraries/snowflake/Snowflake.php';

use \Firebase\JWT\JWT;
use Godruoyi\Snowflake\Snowflake;

use chriskacerguis\RestServer\RestController;



class MY_Controller extends RestController
{

    protected $user_data;

    protected  $filters = [
        'filter_id' => array("MATCH"=>"master_material.id"),
        'filter_id_in' => array("IN"=>"master_material.id"),
        'filter_name' => array("LIKE"=>"master_material.nama_material"),
        'spk_project_id' => array("MATCH"=>"projects_spk.projects_id")
    ];
    

    public function auth()
    {
        
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

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

    public function GenerateID()
    {
        $currentTime = date("Y-m-d");
        $snowflake = new \Godruoyi\Snowflake\Snowflake;
        $snowflake->setSequenceResolver(function ($currentTime) {
            static $lastTime;
            static $sequence;

            if ($lastTime == $currentTime) {
                ++$sequence;
            } else {
                $sequence = 0;
            }

            $lastTime = $currentTime;

            return $sequence;
        })->id();

        return $snowflake->id();
    }

    public function filter($filter=false, $custom=false)
    {
        $q = $this->db;
        if($filter) {
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
        }

        if($custom){
            $q = $this->db->where($custom);
        }

        return $q;
    }


  
    public function Result($msg, $data)
    {
        $result = array(
            "message" => $msg,
            "data" => $data
        );
 
        return $result;
    }
}