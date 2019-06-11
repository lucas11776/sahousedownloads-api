<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Password extends CI_Controller
{
    /**
     * @API (password/token/:token_id)
     * 
     * check if token is valid
     * 
     * @return boolean
     */
    public function token($token_id)
    {
        $this->form_validation->set_rules('token', 'token', 'callback_token_valid');

        if($this->form_validation->run() === false)
        {
            $this->rest_api->fail($this->form_validation->error_array());

            return;
        }

        $this->rest_api->success([]);
    }

    /**
     * @API (password/recover)
     * 
     * recover account password
     * 
     * @return boolean
     */
    public function recover()
    {
        $this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_email_exist');

        if($this->form_validation->run() === false)
        {
            $this->rest_api->fail($this->form_validation->error_array());

            return;
        }

        $user_account = $this->account_model->get_user($this->input->post('email'));

        $token = [
            'expire'  => time() * (69*60*24*2),
            'user_id' => $user_account['user_id']
        ];

        $token = $this->encryption->encrypt(json_encode($token));

        $data = [
            'token'     => uniqid("ID-", true),
            'token_key' => $token,
            'user_id'   => $user_account['user_id']
        ];

        if(!$this->password_model->insert($data))
        {
            $this->rest_api->fail([
                'message' => 'Something went wrong when tring to connect to database.'
            ]);

            return;
        }

        // send token to email address (EMAIL)
        
        $this->rest_api->success([
            'message' => 'You will shorty receive email with reset password link.'
        ]);
    }

    /**
     * @API (password/reset)
     * 
     * reset account password password
     * 
     * @return void
     */
    public function reset()
    {
        $this->form_validation->set_rules('token', 'token', 'callback_token_valid');
        $this->form_validation->set_rules('password', 'password', 'required|min_length[6]|max_length[20]|regex_match[/[a-zA-Z0-9]{6,20}/]', [
            'regex_match' => 'Password must container a number and a charactor.'
        ]);
        $this->form_validation->set_rules('confirm_password', 'confirm password', 'required|matches[password]');

        if($this->form_validation->run() === false)
        {
            $this->rest_api->fail($this->form_validation->error_array());

            return;
        }

        $data = [
            'user_id'  => $this->input->post('token')['user_id'],
            'password' => $this->encryption->encrypt($this->input->post('password'))
        ];

        if($this->password_model->reset($data) === false)
        {
            $this->rest_api->fail([
                'message' => 'Something went wrong when tring to connect to database please try again later.'
            ]);

            return;
        }

        // delete reset token from database
        $this->password_model->delete($data['user_id']);

        $this->rest_api->success([
            'message' => 'Password has been resetted successfully please login to your account with your new password.'
        ]);
    }

    /**
     * check if token is valid.
     * 
     * check if token is valid if token is 
     * valid assign key to $_POST['token]
     * 
     * @param string tokens
     * @return boolean
     */
    public function token_valid()
    {
        $password_recover = $this->password_model->get_password(
            $this->input->get('token')
        );

        if(count($password_recover) === 0)
        {
            $this->form_validation->set_message('token_valid', 'Invalid token please try to recover your password again.');

            return false;
        }

        $key = json_decode($this->encryption->decrypt($password_recover['token_key']), true);

        if(date("") > $key['expire'])
        {
            $this->form_validation->set_message('token_valid', 'Password recover token has expired please recover password again');

            return false;
        }

        // assign token key to post 
        $_POST['token'] = $key;

        return true;
    }

    /**
     * check if email exist in user account
     */
    public function email_exist($email)
    {
        $user_account = $this->account_model->get_user($email);

        if(count($user_account) === 0)
        {
            $this->form_validation->set_message('email_exist', 'Sorry email deos not exist in accounts please try again later.');

            return false;
        }

        return true;
    }
}