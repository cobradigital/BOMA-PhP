<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function authenticationuser() {
		try {
			$username=htmlentities($this->input->post('username'));
			$password=htmlentities($this->input->post('password'));

			$this->form_validation->set_rules('username', 'Username',
				'required');
			$this->form_validation->set_rules('password', 'Password',
				'required');
			$this->form_validation->set_rules('captcha', 'Access Code',
				'required');

			if($this->form_validation->run()==TRUE) {
				$captcha=$this->input->post('captcha');
				$captcha_session=$this->session->userdata('captcha');

				if ($captcha==$captcha_session) {
					$pass=md5($password);
					$entityArgs=array('username'=>$username,
						'password'=>$pass);
					$this->db->select('*') ->from('users') ->where("(email = '$username' OR username = '$username')") ->where('password', $pass);
					$query=$this->db->get();
					$getres=$query->result_array();
		
					if($query->num_rows()>0) {
						if($getres[0]['user_status']=='0') {
							$res=returnResultCustom(false, 'user_inactive');
							$return_url='login';
							$callbackType='error';
						}else {
							$res=returnData($getres);
							$mrole=getData('name', array('id'=>@$getres[0]['role_id']), '', '', 'roles', '');
							$role=$mrole['row'][0];
							$mjabatan=getData('jabatan_name as name', array('id'=>@$getres[0]['jabatan_id']), '', '', 'jabatan', '');
							$jabatan=$mjabatan['row'][0];
							$mwilayah = getData('nama as wilayah', array('id'=>$getres[0]['kota_id']), '', '', 'kota', '');
							$wilayah = $mwilayah['row'][0];
							$row=$getres[0];
							$arr=array('userid'=>$row["id"],
								'username'=>$row["username"],
								'full_name'=>$row["full_name"],
								'role'=>$role['name'],
								'jabatan'=>$jabatan['name'],
								'wilayah'=>$wilayah['wilayah']
							);
							$this->session->set_userdata($arr);
							$datenow=date('Y-m-d H:i:s');
							$iduser=$row['id'];
							$this->db->query("UPDATE users SET last_login_at = '$datenow' WHERE ID='$iduser'");
							$callbackType='success';
							redirect('dashboard');
						}
					}else {
						$res=returnResultCustom(false, 'Data yang anda masukan tidak ada atau tidak aktif.');
						$return_url='login';
						$callbackType='error';
					}
				}else {
					$res=returnResultCustom(false, 'Kode captcha yang kamu masukan salah.');
					$return_url='login';
					$callbackType='error';
				}
			}else {
				$res=returnResultCustom(false, 'Data yang anda masukan salah.');
				$return_url='login';
				$callbackType='error';
			}
		}catch (\Throwable $th) {
			$res=returnResultCustom(false, $th->getMessage());
			$return_url='login';
			$callbackType='error';
		}

		callbackFunction($callbackType, $return_url, $res);

	}

	public function logout_user() {
		$this->session->unset_userdata($_SESSION);
		$this->session->sess_destroy();

		redirect('login');
	}

}

/* End of file Auth.php */