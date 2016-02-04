<?php

namespace App\Http\Controllers;

use Youtube;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class YoutubeController extends Controller
{
    public $cache_file;

    public function __construct()
    {
        $this->cache_file = getcwd().'/youtube_cache.json';
    }

    public function updateCache()
    {
        $videos1 = Youtube::searchChannelVideos('', 'UCHd7bZColl08EOLA5HKADhQ', 50);
        $videos2 = Youtube::searchChannelVideos('', 'UC3gj_W6jiGjeo3yc3ObIzpg', 50);

        $videos = array_merge($videos1, $videos2);

        usort($videos, function($a, $b) {
            if ($a->snippet->publishedAt == $b->snippet->publishedAt) return 0;

            return ($a->snippet->publishedAt > $b->snippet->publishedAt) ? -1 : 1;
        });

        $sorted = [];

        foreach($videos as $video) {
            $sorted[] = [
                'videoId' => $video->id->videoId,
                'date' => $video->snippet->publishedAt,
                'title' => $video->snippet->title,
                'description' => $video->snippet->description,
                'thumbnail' => $video->snippet->thumbnails->high->url,
            ];
        }

        $json = json_encode($sorted);

        file_put_contents($this->cache_file, $json);
    }

    public function getCache()
    {
        return json_decode(file_get_contents($this->cache_file), true);
    }

    public function getVideos()
    {
        $cache = false;

        if(\Request::get('update')) {
            $this->updateCache();
            return $this->getCache();
        }

        if(file_exists($this->cache_file)) {
            $cache = file_get_contents($this->cache_file);
            if((filemtime($this->cache_file) + 3600) < time()) {
                $this->updateCache();
            }
        } else {
            $this->updateCache();
        }

        return $this->getCache();
    }

    public function index()
    {
        $data['videos'] = $this->getVideos();

        return view('frontend.youtube')->with($data);
    }
}
