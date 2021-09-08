<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

    require(APPPATH . 'libraries/REST_Controller.php');

    class UserController extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('userApiModel');
    }
    
    function index_get(){
        $user_id = $this->get('user_id');
        if ($user_id == '') {
            $api = $this->db->get('tbl_user')->result();
        } else {
            $this->db->where('user_id', $user_id);
            $api = $this->db->get('tbl_user')->result();
        }
        $this->response($api, 200);
    }
    
    
    public function userLogin_post(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $getHashPassword = $this->userApiModel->getHashPassword($username);
        
        if($getHashPassword != null){
            if (password_verify($password, $getHashPassword->password)) {
                $usernameCheck = $this->userApiModel->usernameCheck($username);
                $this->response([
                    "status" => 1,
                    "message" => "Username exist, valid!",
                    "data" => $usernameCheck
                ], REST_Controller::HTTP_OK);
            }else{
                $this->response([
                    "status" => 1,
                    "message" => "Password doesn't exist!",
                ], REST_Controller::HTTP_OK);
            }
        }else {
            $this->response([
                "status" => 0,
                "message" => "Username doesn't exist!",
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    
    }
    
    function userRegister_post(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $status = 1;

        $data = [
            "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "status" => $status,
            "image" => "default.png",
        ];

        $usernameCheck = $this->userApiModel->usernameCheck($username);
        $result = $this->userApiModel->createData("tbl_user", $data);

        if(empty($usernameCheck)){
            if($result){
                $this->response([
                    "status" => 1,
                    "message" => "Succes add user!",
                ], REST_Controller::HTTP_CREATED);
            }else{
                $this->response([
                    "status" => 0,
                    "message" => "Oops, failed!"
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }else{
            $this->response([
                "status" => 0,
                "message" => "Username alreaady exist!",
            ], REST_Controller::HTTP_CONFLICT);
        }

    }

    public function userDelete_post(){
        $user_id = $this->input->post("user_id");
        $result = $this->userApiModel->deleteUser($user_id);

        if($result){
            $this->response([
                "status" => 1,
                "message" => "Delete user success"
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([
                "status" => 0,
                "message" => "Oops, delete failed"
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function userUpdateImage_post(){
        $user_id = $this->input->post("user_id");
        $config['upload_path'] = './image/users';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = '1000'; // max size in KB
        $config['overwrite'] = TRUE; // For Replace Image name
        $config['encrypt_name'] = TRUE; // For Encrypt Name
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file')) {
            $errorArray = strip_tags($this->upload->display_errors());
            $this->response(
                ["status" => 0, "message" => "Oops, failed! $errorArray"],
                REST_Controller::HTTP_BAD_REQUEST
            );
        } else {
            $info = $this->upload->data();
            $image_path = $info['raw_name'] . $info['file_ext'];
            $result = $this->userApiModel->userUpdateImage($user_id, $image_path);
            if ($result) {
                $updateUser = $this->userApiModel->getUserById($user_id);
                $this->response([
                    "status" => 1,
                    "message" => "Succes update image",
                    "data" => $updateUser
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    "status" => 0,
                    "message" => " Gagal Update Image Profil , Coba lagi nanti..."
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

}
/** End of file UserController.php **/