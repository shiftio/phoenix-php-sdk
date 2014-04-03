<?php

namespace mediasilo\favorite;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\favorite\Favorite;
use mediasilo\http\exception\NotFoundException;

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
            $clientResponse = $this->webClient->get(MediaSiloResourcePaths::FAVORITES."?context=PROJECT");
            $result = json_decode($clientResponse->getBody());
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
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::FAVORITES . "/" . $id);
        return json_decode($clientResponse->getBody());
    }

    /**
     * Get's everything you've marked as a favorite. This is another function that you probably want to avoid unless you know what you're doing.
     * @return mixed
     */
    public function getFavorites()
    {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::FAVORITES);
        return json_decode($clientResponse->getBody());
    }
}