<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
    /**
     * @API (login)
     * 
     * check if username and password exist in database
     * and if exist return web token to be submitted
     * under Authorization headers
     * 
     * @return void
     */
    public function index()
    {
        $this->auth->guest();

        $this->form_validation->set_rules('username', 'username', 'required');
        $this->form_validation->set_rules('password', 'password', 'required'); 
        
        if($this->form_validation->run() === false)
        {
            $this->rest_api->fail($this->form_validation->error_array());

            return;
        }

        $data = $this->input->post();

        // get account by username/email
        $account = $this->account_model->get_full_user($data['username']);

        // decrypt password if username is found
        if(isset($account['password'])) $account['password'] = $this->encryption->decrypt($account['password']);

        if(count($account) === 0 || ($account['password'] ?? null) !== $data['password'])
        {
            $this->rest_api->fail([
                'message' => 'Invalid username or password please try again.'
            ]);

            return;
        }

        $webToken = [
            'user_id'    => $account['user_id'],
            'expire'     => time() * (60*60*24*7),
            'ip_address' => $this->input->ip_address()
        ];

        $webToken = $this->encryption->encrypt(json_encode($webToken));

        $this->rest_api->success([
            'token' => $webToken
        ]);
    }

}