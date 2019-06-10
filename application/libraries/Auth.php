<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth {

    protected $CI;

    /**
     * User Details
     * 
     * @var array
     */
    private $user = null;

    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        
        // Requiered Reasource
        $this->CI->load->library('encryption');
        $this->CI->load->model('auth_model');
        $this->CI->load->model('account_model');

        $authorizetion = apache_request_headers()['Authorization'] ?? 
                         apache_request_headers()['authorization'] ?? 
                         null;

        $token = json_decode($this->CI->encryption->decrypt($authorizetion), true);

        if($token === false || time() > $token['expire'] ?? 0) return;

        $this->user = $this->CI->account_model->get_user($token['user_id']) ?? null;
    }

    public function user_account()
    {
        return $this->user;
    }

    /**
     * Return Unauthorized Access Message And Headers Code
     * 
     * @return void
     */
    public function unauthorized()
    {
        http_response_code(401); // set response code to Unauthorized

        die(json_encode(['message' => 'Unauthorized Access.']));
    }

    /**
     * Get User id
     * 
     * @return int
     */
    public function user_id()
    {
        return $this->user['user_id'] ?? null;
    }

    /**
     * Check IF User Guest
     * 
     * @return void
     */
    public function guest()
    {
        if(
            $this->user['role'] == null
        )
        {
            return;
        }

        $this->unauthorized();
    }

    /**
     * Check IF User User
     * 
     * @return void
     */
    public function user()
    {
        if(
            $this->user['role'] == 1 || 
            $this->user['role'] == 2 ||
            $this->user['role'] == 3 
        )
        {
            return;
        }

        $this->unauthorized();
    }

    /**
     * Check IF User Editor
     * 
     * @return void
     */
    public function editor()
    {
        if(
            $this->user['role'] == 2 ||
            $this->user['role'] == 3 
        )
        {
            return;
        }

        $this->unauthorized();
    }

    /**
     * Check IF User Admin
     * 
     * @return void
     */
    public function admin()
    {
        if(
            $this->user['role'] == 3
        )
        {
            return;
        }

        $this->unauthorized();
    }
}