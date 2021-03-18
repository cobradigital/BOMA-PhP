<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This software framework is the proprietary and confidential to Sony and Company and its suppliers, if any. The use of it is governed by the terms of the applicable license agreement and/or the consulting services agreement between your organisation
 * and Sony and Company. No part of it may be copied, distributed or shown via any medium outside your organisation, including with external technology or consulting firms, without express prior written consent of the Sony and Company.
 *
 * Copyright (c) 2021 - present Sony and Company. All Rights Reserved.
 */
if (!function_exists('importPost'))
{
    function importPost($csvfile='')
    {
        $CI =& get_instance();
        if($csvfile==''){
            throw new Exception("Error Processing Request", 1);
        }
        $row     = 1;
        if (($handle  = fopen($csvfile, "r")) !== FALSE) {
            ini_set('max_execution_time', 300);
            $csv = array_map('str_getcsv', file($csvfile));
            for ($x = 0; $x < count($csv); $x++) {
                $explode = explode(";", $csv[$x][0]);
                $k       = $explode;
                if ($k != null) {
                    $namecek = substr($k[1], 3);
                } else {
                    $namcek = "";
                }
                $cekin = json_decode(insertData($table,$array));
                if ($cekin->success == true) {
                    echo "<div style='color:green;'>" . $k[0] . ' - ' . $cekin->nama . '</div><br>';
                } else {
                    echo "<div style='color:red;'>" . $k[0] . '-' . $cekin->nama . '</div><br>';
                }
            }

            // fclose($handle);
            return;
            
        }
    }
}


// Boilerplate core
if (!function_exists('getDataCountAll'))
{
    function getDataCountAll($select = '*',$where = array(),$table)
    {
        $CI =& get_instance();

        $CI->db->select($select);
        if($where!=''){
            foreach($where as $k => $v){
                $splitval = explode(" ",$v);
                if(sizeof($splitval)>1){
                    $argskey = $k;
                    $args = $splitval[0];
                    $val = $splitval[1];
                    if($args=='like'){
                        $val = str_replace("_"," ", $val);
                        $CI->db->like($argskey,$val,'both');
                    }
                    if($args=='or'){
                        $CI->db->or_where($argskey,$val);
                    }
                    if($args=='in'){
                        $whereArgs = explode(",",$val);
                        $CI->db->where_in($argskey,$whereArgs);
                    }
                }else{
                    $CI->db->where($k,$v);
                }
            }
            
        }

        $q = $CI->db->get($table);
        if($q){
            $res = $q->num_rows();
        }else{
            $res = returnResultErrorDB();
        }
        return $res;
    }
}
if (!function_exists('getData'))
{
    /**
     * @param(select) string
     * @param(where) array
     * @param(limit) int
     * @param(offset) int
     * @param(table_name) string
     * @param(order_by) string
     */
    function getData($select = '*', $where = array() , $limit = '10', $offset = '0', $table,$order='')
    {
        $CI =& get_instance();

        $CI->db->select($select);

        if($where!=''){
            foreach($where as $k => $v){
                $splitval = explode(" ",$v);
                if(sizeof($splitval)>1){
                    $argskey = $k;
                    $args = $splitval[0];
                    $val = $splitval[1];
                    if($args=='like'){
                        $val = str_replace("_"," ", $val);
                        $CI->db->like($argskey,$val,'both');
                    }
                    if($args=='or'){
                        $CI->db->or_where($argskey,$val);
                    }
                    if($args=='in'){
                        $whereArgs = explode(",",$val);
                        $CI->db->where_in($argskey,$whereArgs);
                    }
                }else{
                    $CI->db->where($k,$v);
                }
            }
            
        }
        if($order!=''){
            $CI->db->order_by($order);
        }
        if($offset>'0'){
            $CI->db->limit($limit,$offset);
        }else if($limit!=""){
            $CI->db->limit($limit);
        }
        
        $q = $CI->db->get($table);
        if($q){
            $res = returnResult($q);
        }else{
            $res = returnResultErrorDB();
        }
        return $res;

    }
}



if (!function_exists('insertData'))
{
    function insertData($table,$array = array())
    {
        $CI =& get_instance();
        $q = $CI->db->insert($table,$array);
        if($q){
            returnResult($q);
        }else{
            returnResultErrorDB();
        }
    }
}


if ( ! function_exists('returnResult'))
{
    function returnResult($data = '',$entity = '')
    {
        $result = array(
            'success'=>true,
            'rowCount'=>$data->num_rows(),
            'row'=>$data->result_array(),
            // $entity
        );
        return $result;
    }
}


if ( ! function_exists('returnData'))
{
    function returnData($data = '',$entity = '')
    {
        $result = array(
            'success'=>true,
            'row'=>$data,
        );
        return $result;
    }
}


if ( ! function_exists('callbackFunction'))
{
    function callbackFunction($type,$url,$data)
    {
        $CI =& get_instance();
        $dt = $data;
        
        if($type==='error'){
            $dt = $data['msg'];
        }else{
            $dt = $data['msg'];
        }
        $CI->session->set_flashdata($type,$dt);
        redirect(site_url($url));
    }
}


if ( ! function_exists('returnResultErrorDB'))
{
    function returnResultErrorDB()
    {
        return array(
            'success'=>false,
            'msg'=>'Failed fetch data from database'
        );
    }
}

if ( ! function_exists('returnResultCustom'))
{
    function returnResultCustom($t,$msg)
    {
        return array(
            'success'=>$t,
            'msg'=>$msg
        );
    }
}

if ( ! function_exists('returnResultCustomWa'))
{
    function returnResultCustomWa($t,$msg,$textWa)
    {
        return array(
            'success'=>$t,
            'msg'=>$msg,
            'TextWa'=>$textWa
        );
    }
}

if ( ! function_exists('helperGetStatus')){
    function helperGetStatus()
    {
        $CI =& get_instance();
        $getAktif = $CI->db->query("SELECT COUNT(*) as totalbaru FROM p_master_permohonan p_master WHERE id NOT IN(SELECT id_master_permohonan FROM p_logs_komentar WHERE id_master_permohonan=p_master.id GROUP BY id_master_permohonan) AND id NOT IN(SELECT id_master_permohonan FROM p_kep_dis WHERE id_master_permohonan=p_master.id GROUP BY id_master_permohonan) AND DATEDIFF(DATE(NOW()),DATE(`p_master`.`created_date`)) <= 5")->result();
        $data['statusBaru'] = $getAktif[0]->totalbaru;

        $getMenunggu = $CI->db->query("SELECT COUNT(*) as totalmenunggu FROM p_master_permohonan p_master WHERE id NOT IN(SELECT id_master_permohonan FROM p_logs_komentar WHERE id_master_permohonan=p_master.id GROUP BY id_master_permohonan) AND id NOT IN(SELECT id_master_permohonan FROM p_kep_dis WHERE id_master_permohonan=p_master.id GROUP BY id_master_permohonan) AND DATEDIFF(DATE(NOW()),DATE(`p_master`.`created_date`)) > 5")->result();
        $data['statusMenunggu'] = $getMenunggu[0]->totalmenunggu;

        $getProses = $CI->db->query("SELECT COUNT(*) as totalproses  FROM p_master_permohonan p_master WHERE id IN(SELECT id_master_permohonan FROM p_logs_komentar WHERE id_master_permohonan=p_master.id GROUP BY id_master_permohonan) AND id NOT IN(SELECT id_master_permohonan FROM p_kep_dis WHERE id_master_permohonan=p_master.id GROUP BY id_master_permohonan)")->result();
        $data['statusProses'] = $getProses[0]->totalproses;

        $getSelesai = $CI->db->query("SELECT COUNT(*) as totalselesai  FROM p_master_permohonan p_master WHERE id IN(SELECT id_master_permohonan FROM p_kep_dis WHERE id_master_permohonan=p_master.id AND status_persetujuan='1' GROUP BY id_master_permohonan)")->result();
        $data['statusSelesai'] = $getSelesai[0]->totalselesai;

        $getDitolak = $CI->db->query("SELECT COUNT(*) as totalditolak  FROM p_master_permohonan p_master WHERE id IN(SELECT id_master_permohonan FROM p_kep_dis WHERE id_master_permohonan=p_master.id AND status_persetujuan='0' GROUP BY id_master_permohonan)")->result();
        $data['statusDitolak'] = $getDitolak[0]->totalditolak;
        

        $data['totalAll'] = (int)$getSelesai[0]->totalselesai + (int)$getAktif[0]->totalbaru + (int)$getProses[0]->totalproses + (int)$getMenunggu[0]->totalmenunggu + (int)$getDitolak[0]->totalditolak;
        return $data;
    }
}
if ( ! function_exists('returnResultCustomTransfer'))
{
    function returnResultCustomTransfer($t,$msg,$stat)
    {
        return array(
            'success'=>$t,
            'msg'=>$msg,
            'statusOrd' =>$stat
        );
    }
}

if ( ! function_exists('sendMailRegisConfirm'))
{
    function sendMailRegisConfirm($to,$subj,$msg)
    {
        $CI =& get_instance();
        $CI->email->initialize(MAIL_CONFIG);
        $CI->email->set_newline("\r\n");
        $CI->email->from('noreply@mobilman.com', 'Konfirmasi Email - Tidak Perlu di Balas');
        $CI->email->to($to);
        $CI->email->subject($subj);
        $CI->email->message($msg);
        
        if($CI->email->send()) {
            return true;
        }else {
            show_error($CI->email->print_debugger());
        }
    }
}