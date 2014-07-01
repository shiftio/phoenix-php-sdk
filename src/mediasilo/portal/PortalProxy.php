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
     * @param String - Additional query parameters to include
     * @returns Array[Portal]
     */
    public function getPortals($params = null) {
        $endpoint = is_null($params) ? MediaSiloResourcePaths::PORTAL : sprintf("%s?%s", MediaSiloResourcePaths::PORTAL, $params);
        $clientResponse = $this->webClient->get($endpoint);
        $results = json_decode($clientResponse->getBody());

        $portals = array();
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
