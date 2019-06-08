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