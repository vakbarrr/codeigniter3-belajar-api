<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class UserApiModel extends CI_Model {

    public function usernameCheck($username){
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('username', $username);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function createData($table, $data)
    {
        $query = $this->db->insert($table, $data);
        return $query;
    }

    public function getHashPassword($username)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('username', $username);
        $query = $this->db->get()->row();
        return $query;
    }

}

/* End of file UserApiModel.php */
