<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter_model extends CI_Model
{
    /**
     * get newsletters from database
     * 
     * @param array where
     * @return array
     */
    public function get_newsletter(array $where)
    {
        return $this->db->where($where)
                        ->get('newsletter')
                        ->result_array()[0] ?? [];
    }

    /**
     * insert email to newsletter
     * 
     * @param string email
     * @return boolean
     */
    public function insert(string $email)
    {
        $data = [
            'email'      => $email,
            'subscribed' => 1
        ];

        return $this->db->insert('newsletter', $data);
    }

    /**
     * insert email to newsletter
     * 
     * @param string email
     * @return boolean
     */
    public function subscribe(string $email)
    {
        $data = [
            'subscribed' => 1
        ];

        return $this->db->where('email', $email)
                        ->update('newsletter', $data);
    }

    /**
     * insert email to newsletter
     * 
     * @param string email
     * @param boolean
     */
    public function unsubscribe(string $email)
    {
        $data = [
            'subscribed' => 0
        ];

        return $this->db->where('email', $email)
                        ->update('newsletter', $data);
    }

}