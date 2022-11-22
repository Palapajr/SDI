<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct(){
        parent::__construct();
		$this->load->model('M_login');
    }

	public function index(){

        if($this->session->userdata('logged') !=TRUE){
            $this->load->view('login');
        }else{
            $url=base_url('dashboard');
            redirect($url);
        };
    }

	public function autentikasi(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
                
        $validasi_username = $this->M_login->query_validasi_username($username);
        if($validasi_username->num_rows() > 0){

            $validate_ps=$this->M_login->query_validasi_password($username,$password);
            if($validate_ps->num_rows() > 0){

                $x = $validate_ps->row_array();
                if($x['status']=='1'){
                    $this->session->set_userdata('logged',TRUE);
                    $this->session->set_userdata('user',$username);
                    $id=$x['id_user'];
                    if($x['role']=='1'){ //Administrator
                        $name = $x['fullname'];
                        $this->session->set_userdata('access','Administrator');
                        $this->session->set_userdata('id',$id);
                        $this->session->set_userdata('name',$name);
                        redirect('admin/dashboard');

                    }else if($x['role']=='2'){ //Admin
                        $name = $x['fullname'];
                        $this->session->set_userdata('access','Admin');
                        $this->session->set_userdata('id',$id);
                        $this->session->set_userdata('name',$name);
                        redirect('index');

                    }
                }else{
                    $url=base_url('login');
                    echo $this->session->set_flashdata('msg','<div class="alert alert-danger" role="alert"><i class="icon fa fa-ban"></i>Akun Tidak Ada / Dinonaktifkan </div>');
                    redirect($url);
                }
            }else{
                $url=base_url('login');
                echo $this->session->set_flashdata('msg','<div class="alert alert-danger" role="alert"><i class="icon fa fa-ban"></i> Password salah!</div>');
                redirect($url);
            }

        }else{
            $url=base_url('login');
            echo $this->session->set_flashdata('msg','<div class="alert alert-danger" role="alert"><i class="icon fa fa-ban"></i> username salah!</div>');
            redirect($url);
        }

    }

    public function logout(){
        $this->session->sess_destroy();
        $url=base_url('login');
        redirect($url);
    }
}
