<?php

class Password_model extends CI_Model
{
    /**
     * get password recover password from database
     *
     * @param string token 
     * @return boolean
     */
    public function get_password($token)
    {
        return $this->db->where('token', $token)
                        ->get('password_reset')
                        ->result_array()[0] ?? [];
    }

    /**
     * insert password reset token
     * 
     * @param array recover
     * @return boolean
     */
    public function insert(array $recover)
    {
        $data = [
            'token'     => $recover['token'],
            'token_key' => $recover['token_key'],
            'user_id'   => $recover['user_id']
        ];

        return $this->db->insert('password_reset', $data);
    }

    /**
     * reset password
     * 
     * @param array reset
     * @return boolean
     */
    public function reset(array $reset)
    {
        $data = [
            'password' => $reset['password']
        ];

        return $this->db->where('user_id', $reset['user_id'])
                        ->update('accounts', $data);
    }

    /**
     * insert password reset token
     * 
     * @param string id
     * @return boolean
     */
    public function delete($id)
    {
        return $this->db->where('user_id', $id)
                        ->or_where('token', $id)
                        ->delete('password_reset');
    }
}