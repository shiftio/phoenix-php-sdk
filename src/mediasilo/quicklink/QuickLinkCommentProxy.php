<?php

namespace mediasilo\quicklink;

use mediasilo\http\WebClient;
use mediasilo\http\MediaSiloResourcePaths;
use mediasilo\comment\Comment;

class QuickLinkCommentProxy {

    private $webClient;

    public function __construct(WebClient $webClient) {
        $this->webClient = $webClient;
    }

    /**
     * Creates a brand spankin new project
     * @param Project $project
     */
    public function createComment(Comment $comment)
    {
        $result = json_decode($this->webClient->post(sprintf(MediaSiloResourcePaths::QUICK_LINK_COMMENTS, $comment->context), $comment->toJson()));
        $comment->id = $result->id;
    }

    /**
     * Gets an exiting project given a project Id
     * @param $id
     * @return Project
     */
    public function getComment($id)
    {
        return Comment::fromJson($this->webClient->get(MediaSiloResourcePaths::QUICK_LINK_COMMENTS . "/" . $id));
    }

    /**
     * Gets an exiting project given a project Id
     * @param $id
     * @return Project
     */
    public function getComments($at, $context)
    {
        return Comment::fromJson($this->webClient->get(sprintf(MediaSiloResourcePaths::QUICK_LINK_COMMENTS, $context) . "?at=".$at."&context=".$context));
    }

    /**
     * Updates an existing project. Use this if you want to change the name or description of a project.
     * You won't be able to change the project owner here, though.
     * @param Project $project
     */
    public function updateComment(Comment $comment)
    {
        $this->webClient->put(MediaSiloResourcePaths::QUICK_LINK_COMMENTS, $comment->toJson());
    }

    /**
     * Sayonara project
     * @param $id
     * @return mixed
     */
    public function deleteComment($id) {
        return $this->webClient->delete(MediaSiloResourcePaths::QUICK_LINK_COMMENTS . "/" . $id);
    }
}