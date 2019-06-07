<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function index()
    {
        $this->form_validation->set_rules('username', 'username', 'required');
        $this->form_validation->set_rules('password', 'password', 'required'); 
        
        if($this->form_validation->run() === false)
        {
            $this->rest_api->fail($this->form_validation->error_array());

            return;
        }

        $data = $this->input->post();

        // get account by username/email
        $account = $this->account_model->get_user($data['username']);

        if(count($account ?? []) === 0 && ($account['password'] ?? null) !== $data['password'])
        {
            $this->rest_api->fail([
                'message' => 'Username or Password does not exist please try again.'
            ]);

            return;
        }

        $webToken = [
            'user_id' => $account['user_id'],
            'expire'  => time() * (60*60*24*7)
        ];

        $webToken = json_encode($webToken);

        $this->rest_api->success($webToken);
    }

}