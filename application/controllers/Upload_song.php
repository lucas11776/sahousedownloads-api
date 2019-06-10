<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_song extends CI_Controller
{
    /**
     * @API (upload/song)
     * 
     * upload song to database
     * 
     * @return void
     */
    public function index()
    {
        $this->auth->user();
        
        $this->form_validation->set_rules('picture', 'picture', 'callback_picture_validation');
        $this->form_validation->set_rules('audio',   'audio',   'callback_audio_validation');
        $this->form_validation->set_rules('title',   'title',   'required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('artist',  'artist',  'required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('album',   'album',   'callback_album_validation');

        if($this->form_validation->run() === false)
        {
            $form_errors = $this->form_validation->error_array();

            $this->rest_api->fail($form_errors);

            // delete file if validation was successful
            !isset($form_errors['picture']) ? unlink($this->input->post('picture')) : "";
            !isset($form_errors['audio'])   ? unlink($this->input->post('audio'))   : "";

            return;
        }

        $data = $this->input->post();
        
        // add user_id to data
        $data['user_id'] = 1;
        
        if(!$this->song_model->insert($data))
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

    /**
     * validate picture file and upload file.
     * 
     * if file was uploaded successfuly assign full
     * file (picture) path to post data array
     * 
     * @return void
     */
    public function picture_validation()
    {
        $config = $this->song_model::PICTURE_CONFIG; 
        $config['file_name'] = url_title($this->input->post('title') ?? 'TEMP_FILE');

        // initialize picture upload configurations
        $this->upload->initialize($config);

        if($this->upload->do_upload('picture') === false)
        {
            $this->form_validation->set_message('picture_validation', $this->upload->display_errors('',''));

            return false;
        }

        $_POST['picture'] = $this->song_model->picture_dir() . $this->upload->data('file_name');
    }

    /**
     * validate audio file and upload file.
     * 
     * if file was uploaded successfuly assign full
     * file (audio) path to post data array
     * 
     * @return void
     */
    public function audio_validation()
    {
        $config = $this->song_model::AUDIO_CONFIG;
        $config['file_name'] = url_title($this->input->post('title') ?? 'TEMP_FILE');

        // initialize picture upload configurations
        $this->upload->initialize($config);

        if($this->upload->do_upload('audio') === false)
        {
            $this->form_validation->set_message('audio_validation', $this->upload->display_errors('',''));

            return false;
        }

        $_POST['audio'] = $this->song_model->audio_dir() . $this->upload->data('file_name');
    }

    /**
     * validate if album exist
     * 
     * check if album exist under the current user
     * 
     * @return boolean
     */
    public function album_validation($album_id)
    {
        if(!empty($album_id))
        {
            $data = [
                'album_id' => $album_id,
                'user_id'  => 1
            ];
            $album = $this->album_model->get_albums($data)[0] ?? [];

            if(count($album) === 0){
                $this->form_validation->set_message('album_validation', 'Album does not exist.');

                return false;
            }
        }

        return true;
    }
}