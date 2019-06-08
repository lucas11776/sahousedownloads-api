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
     * @API (albums/most/downloaded)
     * 
     * get 50 of most downloaded albums from database
     * 
     * @return void
     */
    public function most_downloaded()
    {

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