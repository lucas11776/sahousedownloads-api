<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Album_model extends CI_Model
{
    /**
     * Picture Upload Configurations
     * 
     * @var string
     */
    public const PICTURE_CONFIG = [
        'upload_path'   => 'uploads/songs/albums/pictures/',
        'allowed_types' => ['png','jpeg','jpg']
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
     * Get Album
     * 
     * @param array where
     * @return array
     */
    public function get_album(array $where)
    {
        return $this->db->where($where)
                        ->get('albums')
                        ->result_array() ?? [];
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
            'title'   => $song['title'],
            'artist'  => $song['artist']
        ];

        return $this->db->insert('albums', $data);
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
            'artist' => $details['artist']
        ];

        return $this->db->where('album_id', $details['album_id'])
                        ->update('albums', $data);
    }

    /**
     * Delete Album
     * 
     * @param array details
     * @return boolean
     */
    public function delete(array $details)
    {
        $data = [
            'user_id'  => $details['user_id'],
            'album_id' => $details['album_id']
        ];
        return $this->db->where($data)
                        ->delete('albums');
    }
    
}