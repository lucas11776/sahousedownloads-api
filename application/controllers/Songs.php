<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Songs extends CI_Controller
{
    /**
     * @API (songs)
     * 
     * get 50 of latest and most downloaded songs in database
     * 
     * @return void
     */
    public function index()
    {
        $this->rest_api->response(
            $this->song_model->latest_most_downloaded()
        );
    }

    /**
     * @API (songs/latest)
     * 
     * get 50 latest song in database
     * 
     * @return void
     */
    public function latest()
    {
        $this->rest_api->response(
            $this->song_model->latest()
        );
    }

    /**
     * @API (songs/most/downloaded)
     * 
     * get 50 of the most downloaded song in database
     * 
     * @return void
     */
    public function most_downloaded()
    {
        $this->rest_api->response(
            $this->song_model->most_downloaded()
        );
    }
    
    /**
     * @API (songs/search/:term)
     * 
     * search for a song in database
     * 
     * @return void
     */
    public function search($term)
    {

    }

    /**
     * @API (song/edit/:id)
     * 
     * edit song in database
     * 
     * @return void
     */
    public function edit($id)
    {
        $this->auth->user();
        
        $this->form_validation->set_rules('song_id', 'song id', 'required|callback_song_validation');
        $this->form_validation->set_rules('title',   'title',   'required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('artist',  'artist',  'required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('album',   'album',   'callback_album_validation');

        if($this->form_validation->run() === false)
        {
            $this->rest_api->fail($this->form_validation->error_array());
            return;
        }

        $data = $this->input->post();

        if(!$this->song_model->update($data))
        {
            $this->rest_api->fail([
                'message' => 'Something went wrong when tring to connect to database please try again later'
            ]);

            return;
        }

        $this->rest_api->success([
            'message' => 'Song successfully updated.'
        ]);
    }

    /**
     * validation song exist and belong to client
     * 
     * @param int song_id
     * @return boolean
     */
    public function song_validation($song_id)
    {
        $song = $this->song_model->get_songs([
            'song_id' => $song_id,
            'user_id' => 1
        ])[0] ?? [];

        if(count($song) === 0)
        {
            $this->form_validation->set_message('song_validation', 'Song does not exist please try again later.');

            return false;
        }

        return true;
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

    /**
     * @API (song/delete/:id)
     * 
     * delete a song in database
     * 
     * @return void
     */
    public function delete($id)
    {

    }
}