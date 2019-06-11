<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller
{
    /**
     * @API (account/:id)
     * 
     * get user account
     * 
     * @return void
     */
    public function index($id)
    {
        $this->rest_api->response($this->account_model->get_user($id));
    }

    /**
     * @API (account/edit)
     * 
     * edit user account (name,surname)
     * 
     * @return void
     */
    public function edit()
    {

    }

    /**
     * @API (account/upload/picture)
     * 
     * update user profile picture
     * 
     * @return void
     */
    public function upload_picture()
    {

    }

    /**
     * @API (account/change/password)
     * 
     * change user password
     * 
     * @return void
     */
    public function change_password()
    {

    }
}