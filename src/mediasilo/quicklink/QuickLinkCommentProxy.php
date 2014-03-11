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
     * Creates a new comment
     * @param Comment $comment
     */
    public function createComment(Comment $comment)
    {
        $result = json_decode($this->webClient->post(sprintf(MediaSiloResourcePaths::QUICK_LINK_COMMENTS, $comment->context), $comment->toJson()));
        $comment->id = $result->id;
    }

    /**
     * Gets an exiting comment given a comment id
     * @param $id
     * @return Comment
     */
    public function getComment($id)
    {
        return Comment::fromJson($this->webClient->get(MediaSiloResourcePaths::QUICK_LINK_COMMENTS . "/" . $id));
    }

    /**
     * Gets comments for a given asset within a specific context. In this case within the context of a quicklink.
     * @param $at The id of what was commented at
     * @param $context The scope of the comment
     * @return Comments
     */
    public function getComments($at, $context)
    {
        return Comment::fromJson($this->webClient->get(sprintf(MediaSiloResourcePaths::QUICK_LINK_COMMENTS, $context) . "?at=".$at."&context=".$context));
    }

    /**
     * Updates an existing comment. Use this if you want to change the body of the comment.
     * @param Comment $comment
     */
    public function updateComment(Comment $comment)
    {
        $this->webClient->put(MediaSiloResourcePaths::QUICK_LINK_COMMENTS, $comment->toJson());
    }

    /**
     * Sayonara comment
     * @param $id
     */
    public function deleteComment($id) {
        return $this->webClient->delete(MediaSiloResourcePaths::QUICK_LINK_COMMENTS . "/" . $id);
    }
}