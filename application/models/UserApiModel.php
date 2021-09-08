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

    public function userUpdateImage($user_id, $image)
    {
        $this->db->select('image');
        $this->db->from('tbl_user');
        $this->db->where('user_id', $user_id);
        $selectOldImage = $this->db->get()->row()->image;
        $selectNewImage = ['image' => $image];
        $this->db->where('user_id', $user_id);
        $query = $this->db->update('tbl_user', $selectNewImage);
        if ($query) {
            if (!empty($selectOldImage)) {
                if (file_exists("./image/users/" . $selectOldImage)) {
                    unlink("./image/users/" . $selectOldImage);
                    return $query;
                } else {
                    return $query;
                }
            } else {
                return $query;
            }
        } else {
            return false;
        }
    }
     public function getUserById($user_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function deleteUser($user_id)
    {
        $this->db->select('image');
        $this->db->from('tbl_user');
        $this->db->where('user_id', $user_id);
        $selectOldImage = $this->db->get()->row()->image;
        $this->db->where('user_id', $user_id);
        $query = $this->db->delete('tbl_user');
        if ($query) {
            if (!empty($selectOldImage)) {
                if (file_exists("./image/users/" . $selectOldImage)) {
                    unlink("./image/users/" . $selectOldImage);
                    return $query;
                } else {
                    return $query;
                }
            } else {
                return $query;
            }
        } else {
            return false;
        }
    }

}

/* End of file UserApiModel.php */
