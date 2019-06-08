<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth {

    /**
     * User Details
     * 
     * @var array
     */
    public $user = null;

    public function __construct()
    {
        
        $webToken = '';
        $webToken = $this->encryption->decrypt($webToken);
        $webToken = json_decode($webToken, true);

        if(is_array($webToken))
        {
            // get user details
        }

    }

    /**
     * Return Unauthorized Access Message And Headers Code
     * 
     * @return void
     */
    public function unauthorized()
    {
        http_response_code(401); // set response code to Unauthorized

        die(json_decode(['message' => 'Unauthorized Access.']));
    }

    /**
     * Get User id
     * 
     * @return int
     */
    public function user_id()
    {

    }

    /**
     * Check IF User Guest
     * 
     * @return void
     */
    public function guest()
    {
        if(
            ($this->user['role'] ?? null) === null
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
            ($this->user['role'] ?? null) === 1 || 
            ($this->user['role'] ?? null) === 2 ||
            ($this->user['role'] ?? null) === 3 
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
            ($this->user['role'] ?? null) === 2 ||
            ($this->user['role'] ?? null) === 3 
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
        if(($this->user['role'] ?? null) === 3)
        {
            return;
        }

        $this->unauthorized();
    }
}