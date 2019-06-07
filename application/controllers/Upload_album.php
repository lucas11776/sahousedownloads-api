<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_album extends CI_Controller
{
    public function index()
    {
        $this->form_validation->set_rules('picture', 'picture', 'callback_picture_validation');
        $this->form_validation->set_rules('title',   'title',   'required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('artist',  'artist',  'required|min_length[2]|max_length[50]');

        if($this->form_validation->run() === false)
        {
            $form_error = $this->form_validation->error_array();
            $this->rest_api->fail($form_error);

            // delete uploaded picture
            !isset($form_error['picture']) ? unlink($this->input->post('picture')) : null;

            return;
        }

        $data = $this->input->post();

        // add user_id data
        $data['user_id'] = 1;

        if(!$this->album_model->insert($data))
        {
            $this->rest_api->fail([
                'message' => 'Something went wrong when tring to connect to database please try again later.'
            ]);

            return;
        }

        $this->rest_api->success([
            'message' => 'Song has been uploaded successfully.'
        ]);
    }

    public function picture_validation()
    {
        $config = $this->album_model::PICTURE_CONFIG; 
        $config['file_name'] = url_title(
            $this->input->post('title') == '' ? 'TEMP_FILE' : $this->input->post('title')
        );

        // initialize picture upload configurations
        $this->upload->initialize($config);

        if($this->upload->do_upload('picture') === false)
        {
            $this->form_validation->set_message('picture_validation', $this->upload->display_errors('',''));

            return false;
        }

        $_POST['picture'] = $this->album_model->picture_dir() . $this->upload->data('file_name');
    }
    
}