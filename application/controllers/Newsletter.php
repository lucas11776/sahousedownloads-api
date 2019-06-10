<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter extends CI_Controller
{
    /**
     * Email is subscribed to newsletter
     * 
     * @var boolean
     */
    private $subscribed_newsletter = false;

    /**
     * @API (newsletter/subscribe)
     * 
     * subscribe use email to newsletter
     * 
     * @return void
     */
    public function subscribe()
    {
        $this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_email_exist');

        if($this->form_validation->run() === false)
        {
            $this->rest_api->fail($this->form_validation->error_array());

            return;
        }

        $subscribed = false;

        /* 
            check if user has newsletter account.

            NOT-ACCOUNT = insert email and subscribed field
            HAS-ACCOUNT = updated subscribe field to one
        */
        if($this->subscribed_newsletter === false)
        {
            $subscribed = $this->newsletter_model->insert($this->input->post('email'));
        }
        else
        {
            $subscribed = $this->newsletter_model->subscribe($this->input->post('email'));
        }

        if($subscribed === false)
        {
            $this->rest_api->fail([
                'Something went wrong when tring to subscribed you to newsletter please try again later.'
            ]);

            return false;
        }

        $this->rest_api->success([
            'message' => 'Thank you for subscribing to are newletter you will now get weekly updateds.'
        ]);
    }

    /**
     * @API (newsletter/unsubscribe)
     * 
     * unsubscribe use email to newsletter
     * 
     * @return void
     */
    public function unsubscribe($token = null)
    {
        $this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_email_subscribed');

        if($this->form_validation->run() === false)
        {
            $this->rest_api->fail($this->form_validation->error_array());

            return;
        }

        if(!$this->newsletter_model->unsubscribe($this->input->post('email')))
        {
            $this->rest_api->fail([
                'message' => 'Something went wrong when tring to unsubscribe you to are newsletter please try again later.'
            ]);

            return;
        }

        $this->rest_api->success([
            'message' => 'Your email is unsubscribed to are newsletter you will not get updateds.'
        ]);
    }

    /**
     * validate if email exist
     * 
     * @param string email
     * @return boolean
     */
    public function email_exist($email)
    {
        $newsletter = $this->newsletter_model->get_newsletter([
            'email' => $email
        ]);

        if(count($newsletter) !== 0)
        {
            $this->subscribed_newsletter = true;
        }

        if(($newsletter['subscribed'] ?? null) == 1)
        {
            $this->form_validation->set_message('email_exist','Email already subscribed to newsletter.');

            return false;
        }

        return true;
    }

    /**
     * validate if email subscribed
     * 
     * @param string email
     * @return boolean
     */
    public function email_subscribed($email)
    {
        $newsletter = $this->newsletter_model->get_newsletter([
            'email' => $email
        ]);

        if(count($newsletter) !== 0)
        {
            $this->subscribed_newsletter = true;
        }

        if(($newsletter['subscribed'] ?? null) != 1)
        {
            $this->form_validation->set_message('email_subscribed','Email is not subscribed to our newsletter.');

            return false;
        }

        return true;
    }

}