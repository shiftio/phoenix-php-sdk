<?php

namespace mediasilo\channel;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\channel\Channel;

class ChannelProxy {

    private $webClient;

    public function __construct($webClient) {
        $this->webClient = $webClient;
    }

    /**
     * Gets an exiting asset given an asset Id
     * @param $id
     * @return Asset
     */
    public function getChannel($channelId) {
        return Channel::fromJson($this->webClient->get(MediaSiloResourcePaths::CHANNELS . "/" . $channelId));
    }

    /**
     * Gets multiple assets given asset Ids
     * @param $ids
     * @return Array(Asset)
    */ 
    public function getChannels() {
        $channels = array();
        
        $result = json_decode($this->webClient->get(MediaSiloResourcePaths::CHANNELS));
        $channelResults = $result->results;

        if(!empty($channelResults)) {
            foreach($channelResults as $channelResult) {
                $channel = Channel::fromStdClass($channelResult);
                array_push($channels, $channel);
            }
        }

        return $channels;
    }

    /**
     * Creates a channel
     * @param Channel $channel
     */
    public function createChannel(Channel $channel) {
        $result = json_decode($this->webClient->post(MediaSiloResourcePaths::CHANNELS, $channel->toJson()));
        $channel->id = $result->id;
    }

    /**
     * Updates a channel with the given Id
     * @param Channel $channel
     */
    public function updateChannel(Channel $channel) {
        $result = json_decode($this->webClient->put(MediaSiloResourcePaths::CHANNELS, $channel->toJson()));
    }

    /**
     * Deletes a channel with the given Id
     * @param $channelId
     */
    public function deleteChannel($channelId) {
        $result = json_decode($this->webClient->delete(MediaSiloResourcePaths::CHANNELS . "/" . $channelId));
    }

}