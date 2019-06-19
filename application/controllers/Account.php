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
        $this->auth->user();

        $this->form_validation->set_rules('name', 'name', 'min_length[2]|max_length[100]');
        $this->form_validation->set_rules('surname', 'surname', 'min_length[3]|max_length[100]');

        if($this->form_validation->run() === false)
        {
            $this->rest_api->fail($this->form_validation->error_array());

            return;
        }

        $data = $this->input->post();

        $data['user_id'] = $this->auth->user_id();

        if($this->account_model->update($data) === false)
        {
            $this->rest_api->fail([
                'message' => 'Something went wrong when tring to update account details please try again later.'
            ]);

            return;
        }

        $this->rest_api->success([
            'message' => 'Account details updated.'
        ]);
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
        $this->auth->user();
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
        $this->auth->user();
    
        $this->form_validation->set_rules('old_password', 'old password', 'required|min_length[6]|max_length[20]|callback_password_match');
        $this->form_validation->set_rules('new_password', 'new password', 'required|min_length[6]|max_length[20]|regex_match[/[a-zA-Z0-9]{6,20}/]', [
            'regex_match' => 'Password must container a number and a charactor.'
        ]);
        $this->form_validation->set_rules('confirm_password', 'confirm password', 'required|matches[new_password]');

        if($this->form_validation->run() === false)
        {
            $this->rest_api->fail($this->form_validation->error_array());

            return;
        }

        $data = [
            'user_id'  => $this->auth->user_id(),
            'password' => $this->encryption->encrypt($this->input->post('new_password'))
        ];

        if($this->password_model->reset($data) === false)
        {
            $this->rest_api->fail([
                'message' => 'Something went wrong when tring to connect to database please try again later.'
            ]);

            return;
        }
        
        $this->rest_api->success([
            'message' => 'Password has been changed successfully.'
        ]);
    }

    /**
     * check if old password match with account password
     * 
     * @param string old_password
     * 
     * @return boolean
     */
    public function password_match($old_password)
    {
        $user_account = $this->account_model->get_full_user($this->auth->user_id());

        // decrypt password
        $user_account['password'] = $this->encryption->decrypt($user_account['password']);

        $old_password = $this->input->post('old_password');

        if($old_password !== $user_account['password'])
        {
            $this->form_validation->set_message('password_match', 'Old password does not match account password.');

            return false;
        }

        return true;
    }
}