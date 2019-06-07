<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller
{

    public function index()
    {
        $this->form_validation->set_rules('username',         'username', 'required|min_length[3]|max_length[100]|callback_username_exist');
        $this->form_validation->set_rules('email',            'email',    'required|valid_email|callback_username_exist');
        $this->form_validation->set_rules('name',             'name',     'required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('surname',          'surname',  'required|min_length[3]|max_length[100]');
        $this->form_validation->set_rules('password',         'password', 'required|min_length[6]|max_length[20]|regex_match[/[a-zA-Z]/]', [
            'regex_match' => 'Password must container a number and a charactor.'
        ]);
        $this->form_validation->set_rules('confirm_password', 'username', 'required|matches[password]');

        if($this->form_validation->run() === false)
        {
            $this->rest_api->fail($this->form_validation->error_array());

            return;
        }

        $data = $this->input->post();

        // add defualt values
        $data['role']    = 1; // set role to user
        $data['picture'] = $this->account_model->picture_dir() . $this->account_model::DEFUALT_PICTURE;

        if(!$this->account_model->insert($data))
        {
            $this->rest_api->fail([
                'message' => 'Something went wrong when tring to connect to database'
            ]);

            return;
        }

        $this->rest_api->success([
            'message' => 'Your are registerd please login to your account.'
        ]);
    }

    public function username_exist($email)
    {
        $email_exist = $this->account_model->get_user($email);

        if(count($email_exist) !== 0)
        {
            $this->form_validation->set_message('username_exist', 'The {field} your entered exist please try new one.');

            return false;
        }

        return true;
    }

}