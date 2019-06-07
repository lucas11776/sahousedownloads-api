<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_model extends CI_Model
{
    /**
     * Picture Upload Configurations
     * 
     * @var string
     */
    public const PICTURE_CONFIG = [
        'upload_path'   => 'uploads/songs/pictures/',
        'allowed_types' => ['png','jpeg','jpg']
    ];

    /**
     * Audio Upload Configurations
     * 
     * @var array
     */
    public const AUDIO_CONFIG = [
        'upload_path'   => 'uploads/songs/audios/',
        'allowed_types' => ['mp3']
    ];

    /**
     * Get Picture Dir
     * 
     * @return string
     */
    public function picture_dir()
    {
        return $this::PICTURE_CONFIG['upload_path'];
    }

    /**
     * Get Audio Dir
     * 
     * @return string
     */
    public function audio_dir()
    {
        return $this::AUDIO_CONFIG['upload_path'];
    }

    /**
     * Insert Song Table
     * 
     * @param array song
     * @return boolean
     */
    public function insert(array $song)
    {
        $data = [
            'user_id' => $song['user_id'],
            'picture' => $song['picture'],
            'audio'   => $song['audio'],
            'title'   => $song['title'],
            'artist'  => $song['artist'],
            'album'   => $song['album']
        ];

        return $this->db->insert('songs', $data);
    }

    /**
     * Updated Song Details
     * 
     * @param array details
     * @return boolean
     */
    public function update(array $details)
    {
        $data = [
            'title'  => $details['title'],
            'artist' => $details['artist'],
            'album'  => $details['album']
        ];

        return $this->db->where('user_id', $details['user_id'])
                        ->update('songs', $data);
    }
    
}