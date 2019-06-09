<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Albums extends CI_Controller
{
    /**
     * @API (albums)
     * 
     * get 50 of latest and most downloaded albums in database
     * 
     * @return void
     */
    public function index()
    {
        $this->rest_api->response(
            $this->album_model->latest_most_downloaded()
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
            $this->album_model->latest()
        );
    }

    /**
     * @API (albums/most/downloaded)
     * 
     * get 50 of most downloaded albums from database
     * 
     * @return void
     */
    public function most_downloaded()
    {
        $this->rest_api->response(
            $this->album_model->most_downloaded()
        );
    }

    /**
     * @API (albums/:id)
     * 
     * get full album with songs that are in album
     * 
     * @return void
     */
    public function single_album($id)
    {
        if(!is_numeric($id))
        {
            $this->rest_api->fail([
                'message' => 'Invalid Album ID.'
            ]);

            return;
        }

        $album = $this->album_model->get_albums([
            'album_id' => $id
        ])[0] ?? [];

        // check if album exist
        if(count($album) === 0)
        {
            $this->rest_api->response([
                'message' => 'Album is not found in database it may be delete by user.'
            ]);

            return;
        }

        $album['songs'] = $this->song_model->get_songs(['album' => $album['album_id']]);

        $this->rest_api->response($album);
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
     * @API (albums/delete/:id)
     * 
     * delete album from database and clear all 
     * song from album
     * 
     * @return void
     */
    public function delete($id)
    {

    }
    
}