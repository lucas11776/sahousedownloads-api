<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_blog extends CI_Controller
{
    /**
     * @API (upload/blog)
     * 
     * upload blog post to database
     * 
     * @return void
     */
    public function index()
    {
        $this->auth->editor();
        
        $this->form_validation->set_rules('picture', 'picture', 'callback_picture_validation');
        $this->form_validation->set_rules('title',   'title',   'required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('text',    'text',    'required|min_length[2]');

        if($this->form_validation->run() === false)
        {
            $form_error = $this->form_validation->error_array();
            $this->rest_api->fail($form_error);

            // delete uploaded picture
            !isset($form_error['picture']) ? unlink($this->input->post('picture')) : null;

            return;
        }

        $data = $this->input->post();

        // add user_id and slug to data
        $data['user_id'] = 1;
        $data['slug']    = url_title($data['title'] . ' posted at ' . date('l d M Y h i s a'));

        if(!$this->blog_model->insert($data))
        {
            $this->rest_api->fail([
                'message' => 'Something went wrong when tring to connect to database please try again later.'
            ]);

            return;
        }

        $this->rest_api->success([
            'message' => 'Blog post has been uploaded successfully.',
            'slug'    => $data['slug']
        ]);
    }

    /**
     * validate picture file and upload file.
     * 
     * if file was uploaded successfuly assign full
     * file path to post data array
     * 
     * $_POST['picture'] = 'File Path';
     * 
     * @return void
     */
    public function picture_validation()
    {
        $config = $this->blog_model::PICTURE_CONFIG; 
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

        $_POST['picture'] = $this->blog_model->picture_dir() . $this->upload->data('file_name');
    }
    
}