<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Song_model extends CI_Model
{
    /**
     * picture upload configurations
     * 
     * @var string
     */
    public const PICTURE_CONFIG = [
        'upload_path'   => 'uploads/songs/pictures/',
        'allowed_types' => ['png','jpeg','jpg']
    ];

    /**
     * audio upload configurations
     * 
     * @var array
     */
    public const AUDIO_CONFIG = [
        'upload_path'   => 'uploads/songs/audios/',
        'allowed_types' => ['mp3']
    ];

    /**
     * get picture dir
     * 
     * @return string
     */
    public function picture_dir()
    {
        return $this::PICTURE_CONFIG['upload_path'];
    }

    /**
     * get audio dir
     * 
     * @return string
     */
    public function audio_dir()
    {
        return $this::AUDIO_CONFIG['upload_path'];
    }

    /**
     * get 50 lastest and most downloaded songs
     * 
     * @return array
     */
    public function search(string $term, int $limit = 50)
    {
        return $this->db->like('title',$term)
                        ->or_like('artist',$term)
                        ->order_by('downloads','DESC')
                        ->order_by('date','DESC')
                        ->limit($limit)
                        ->get('songs')
                        ->result_array();
    }

    /**
     * get song be selection
     * 
     * @param array where
     * @return array
     */
    public function get(array $where, int $limit = 50)
    {
        return $this->db->where($where)
                        ->limit($limit)
                        ->get('songs')
                        ->result_array();
    }

    /**
     * get 50 lastest and most downloaded songs
     * 
     * @return array
     */
    public function latest_most_downloaded(int $limit = 50)
    {
        return $this->db->order_by('downloads','DESC')
                        ->order_by('song_id','RANDOM')
                        ->limit($limit)
                        ->get('songs')
                        ->result_array();
    }

    /**
     * get 50 lastest and most downloaded songs
     * 
     * @return array
     */
    public function latest(int $limit = 50)
    {
        return $this->db->order_by('date','DESC')
                        ->order_by('song_id','RANDOM')
                        ->limit($limit)
                        ->get('songs')
                        ->result_array();
    }

    /**
     * get 50 lastest and most downloaded songs
     * 
     * @return array
     */
    public function most_downloaded(int $limit = 50)
    {
        return $this->db->order_by('downloads','DESC')
                        ->limit($limit)
                        ->get('songs')
                        ->result_array();
    }


    /**
     * insert song table
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
     * updated song details
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