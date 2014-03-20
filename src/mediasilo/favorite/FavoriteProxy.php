<?php

namespace mediasilo\favorite;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\favorite\Favorite;
use mediasilo\http\NotFoundException;

class FavoriteProxy {

    private $webClient;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }

    /**
     * Makes a given project one of your favorites
     * @param $projectId
     */
    public function favorProject($projectId)
    {
        $favorite = new Favorite("PROJECT", time(), null, null, $projectId);
        $result = json_decode($this->webClient->post(MediaSiloResourcePaths::FAVORITES, $favorite->toJson()));
        $favorite->id = $result->id;
    }

    /**
     * Makes a given project NOT one of your favorites. It's...just another project after this.
     * @param $projectId
     */
    public function unfavor($projectId)
    {
        $favorites = $this->getFavoriteProjects();

        foreach($favorites as $favorite) {
            if($favorite->targetObjectId == $projectId) {
                $this->webClient->delete(MediaSiloResourcePaths::FAVORITES . "/" . $favorite->id);
            }
        }
    }

    /**
     * Get's all of your favorite projects
     * @return array
     */
    public function getFavoriteProjects()
    {
        $favorites = array();

        try {
            $result = json_decode($this->webClient->get(MediaSiloResourcePaths::FAVORITES."?context=PROJECT"));
            $favoriteResults = $result->results;

            if(!empty($favoriteResults)) {
                foreach($favoriteResults as $favoriteResult) {
                    array_push($favorites, Favorite::fromJson($favoriteResult));
                }
            }
        }
        catch(NotFoundException $ex) {

        }

        return $favorites;
    }

    /**
     * Get's a specific favorite. You really shouldn't be using this directly unless you know what you're doing.
     * @param $id
     * @return mixed
     */
    public function getFavorite($id)
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::FAVORITES . "/" . $id));
    }

    /**
     * Get's everything you've marked as a favorite. This is another function that you probably want to avoid unless you know what you're doing.
     * @return mixed
     */
    public function getFavorites()
    {
        return json_decode($this->webClient->get(MediaSiloResourcePaths::FAVORITES));
    }
}