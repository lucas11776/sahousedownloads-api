<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Album_model extends CI_Model
{
    /**
     * picture upload configurations
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
     * search for a album
     * 
     * @param string term
     * @return array
     */
    public function search(string $term, int $limit = 50)
    {
        $sql = "SELECT DISTINCT *,
                    (SELECT COUNT(*)       FROM songs WHERE songs.album = albums.album_id) AS songs,
                    (SELECT SUM(downloads) FROM songs WHERE songs.album = albums.album_id) AS downloads
                FROM albums 
                WHERE (SELECT COUNT(*) FROM songs WHERE songs.album = albums.album_id) != 0 AND
                title LIKE '%{$term}%' OR artist LIKE '%{$term}%'
                ORDER BY date,downloads DESC LIMIT {$limit}";
        return $this->db->query($sql)->result_array();
    }


    /**
     * get latest and most downloaded albums
     * 
     * @return array
     */
    public function latest_most_downloaded(int $limit = 50)
    {
        $sql = "SELECT DISTINCT albums.*,
                    (SELECT COUNT(*)       FROM songs WHERE songs.album = albums.album_id) AS songs,
                    (SELECT SUM(downloads) FROM songs WHERE songs.album = albums.album_id) AS downloads
                FROM albums 
                WHERE (SELECT COUNT(*) FROM songs WHERE songs.album = albums.album_id) != 0
                ORDER BY albums.date, downloads DESC LIMIT {$limit}";
        return $this->db->query($sql)->result_array();
    }

    /**
     * get albums
     * 
     * @param array where
     * @return array
     */
    public function get_albums(array $where)
    {
        return $this->db->where($where)
                        ->get('albums')
                        ->result_array() ?? [];
    }

    /**
     * insert song in database
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
     * updated song details
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
     * delete album
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