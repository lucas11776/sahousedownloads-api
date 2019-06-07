<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends CI_Model
{

    /**
     * Defualt Picture
     * 
     * @var string
     */
    public const DEFUALT_PICTURE = 'user.png';

    /**
     * Picture Upload Config
     * 
     * @var array
     */
    public const PICTURE_CONFIG = [
        'upload_path' => 'uploads/accounts/picture/',
        'allowed'     => 'png|jpg'
    ];

    /**
     * Get Picture Path
     * 
     * @return string
     */
    public function picture_dir()
    {
        return $this::PICTURE_CONFIG['upload_path'];
    }

    /**
     * Get User Account By id,email,username
     * 
     * @param any id
     * @return array
     */
    public function get_user(string $id)
    {
        return $this->db->select('user_id,date,role,picture,username,email,name,surname')
                        ->where('user_id', $id)
                        ->limit(1)
                        ->or_where('username', $id)
                        ->or_where('email', $id)
                        ->get('accounts')
                        ->result_array();
    }

    /**
     * Insert Client Account Details
     * 
     * @param array info
     * @return boolean
     */
    public function insert(array $info)
    {
        $data = [
            'picture'  => $info['picture'],
            'role'     => $info['role'],
            'username' => $info['name'],
            'email'    => $info['email'],
            'name'     => $info['name'],
            'surname'  => $info['surname'],
            'password' => $info['password']
        ];

        return $this->db->insert('accounts', $data);
    }

    /**
     * Update Client details
     * 
     * @param array details
     * @return boolean
     */
    public function update(array $details)
    {
        $data = [
            'name'    => $details,
            'surname' => $details
        ];
        return $this->db->where('user_id', $details['user_id'])
                        ->update('accounts', $data);
    }

    /**
     * Updated Account Client Picture
     * 
     * @param array info
     * @return boolean
     */
    public function update_picture(array $info)
    {
        $data = [
            'picture' => $info['picture']
        ];

        return $this->db->where('user_id', $info['user_id'])
                        ->update('accounts', $data);
    }

    /**
     * Change User Role
     * 
     * @param array info
     * @return boolean
     */
    public function change_role(array $info)
    {
        $data = [
            'role' => $info['role']
        ];

        return $this->db->where('user_id', $info['user_id'])
                        ->update('accounts', $data);
    }

    /**
     * Delete User Account
     * 
     * @param int user_id
     */
    public function delete_account(int $user_id)
    {
        return true;
    }

}