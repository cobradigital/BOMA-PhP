<?php

class Sitemodels extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    public function get($array=false, $table, $select, $filter=false, $join=false, $order_by=false, $group_by=false, $limit=false )
    {
        $this->db->select($select);
        $this->db->from($table);

        if ( $filter )
           $filter;

        
        if ( $order_by )
            $this->db->order_by($order_by);

        if ( $group_by )
            $this->db->group_by($group_by);
        
        if ( $join ){
            foreach($join as $key => $value){
                $exp = explode(',', $value);
                $this->db->join($key, $exp[0], $exp[1]);
            }
        }
        
        if ( $limit ){
            $this->db->limit($limit);
        }
        
        $q = $this->db->get();
        if ( $q->num_rows() > 0 )
            if ($array){
                return $q->result_array();
            }else{
                return $q->result();
            }
        else
            return '0';
    }

    public function insert($table, $data)
    {
        $this->db->insert($table,$data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }

    public function insert_bulk($table, $data)
    {
        $this->db->insert_batch($table,$data);
    }

    public function update($table, $data, $where=false)
    {
        if ( $where )
            $this->db->where($where);

        $this->db->update($table, $data);
    }

    public function delete($table,$where)
    {
        $this->db->where($where);
        return $this->db->delete($table);
    }

    
    public function query($sql, $where=false){
        if ( $where )
            $q = $this->db->query($sql, $where);
        else
            $q = $this->db->query($sql);
        
        if ( $q->num_rows() > 0 )
            return $q->result();
        else
            return '0';
    }
    
}