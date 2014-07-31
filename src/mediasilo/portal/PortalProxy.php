<?php

namespace mediasilo\portal;

use mediasilo\http\MediaSiloResourcePaths;

class PortalProxy {

    private $webClient;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }


    /**
     * Read One
     * @param String $id
     * @returns Portal
     */
    public function getPortal($id) {
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::PORTAL."/".$id);
        return Portal::fromJson($clientResponse->getBody());
    }

    /**
     * Read Many
     * @param Array $searchParams - Array of search parameters
     * @returns Array[Portal]
     */
    public function getPortals($searchParams = array()) {
        $portals = array();
        $searchQuery = '?';

        foreach ($searchParams as $key => $value) {
            $searchQuery .= $key . '=' . $value . '&';
        }

        $searchQuery = substr($searchQuery, 0, -1);
        $clientResponse = $this->webClient->get(MediaSiloResourcePaths::PORTAL . $searchQuery);
        $results = json_decode($clientResponse->getBody());

        foreach($results as $result) {
            array_push($portals, Portal::fromStdClass($result));
        }
        return $portals;
    }

    /**
     * Create
     * @param Portal $portal
     */
    public function createPortal(Portal $portal)
    {
        $result = json_decode($this->webClient->post(MediaSiloResourcePaths::PORTAL, $portal->toJson()));
        $portal->setId($result->id);
    }

    /**
     * Update
     * @param Portal $portal
     */
    public function updatePortal(Portal $portal) {
        $this->webClient->put(MediaSiloResourcePaths::PORTAL, $portal->toJson());
    }

    /**
     * Expire
     * @param String $id
     */
    public function expirePortal($id) {
        $this->webClient->post(sprintf('%s/%s/expire', MediaSiloResourcePaths::PORTAL, $id));
    }

}
