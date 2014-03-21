<?php
require 'vendor/autoload.php';

use mediasilo\quicklink\QuickLinkCommentProxy;
use mediasilo\comment\Comment;
use mediasilo\comment\CommentUser;
use mediasilo\http\MediaSiloResourcePaths;

class CommentTest extends PHPUnit_Framework_TestCase
{
    protected $webClient;
    protected $quicklinkCommentProxy;

    protected function setUp()
    {
        $self = $this;

        $this->webClient = $this->getMockBuilder('mediasilo\http\WebClient')
            ->disableOriginalConstructor()
            ->getMock();

        $this->webClient->expects($this->any())
            ->method('post')
            ->will($this->returnValue(json_encode($this->createCommentHelper("123423141243214324"))));

        $this->webClient->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function($id = null) use ($self) {
                if ($id != MediaSiloResourcePaths::QUICK_LINK_COMMENTS) {
                    return json_encode($self->createCommentHelper("12342314"));
                } else {
                    $commentList = array();
                    for ($i=0; $i<4; $i++) array_push($commentList, $self->createCommentHelper("i"));
                    return json_encode($commentList);
                }
            }));

        $this->webClient->expects($this->any())
            ->method('put')
            ->will($this->returnValue(json_encode($this->createCommentHelper("123423141243214324"))));

        $this->quicklinkCommentProxy = new QuickLinkCommentProxy($this->webClient);
    }

    public function createCommentHelper($id = null)
    {
        $at = "12345-1234-1234-12345678";
        $inResponseTo = "12345-1234-1234-12345678";
        $context = "12345-1234-1234-12345678";
        $body = "TEST COMMENT";
        
        $comment = new Comment($at, $inResponseTo, $context, $body);

        $comment->id = $id;
        $comment->startTimeCode = 12345678900;
        $comment->endTimeCode = 12345678900;
        $comment->dateCreated = 12345678900;
        $comment->user = new CommentUser("12345-1234-1234-12345678", "USERNAME", "FIRSTNAME", "LASTNAME", "TEST@MEDIASILO.COM");

        return $comment;
    }

    public function testCreate()
    {
        $comment = $this->createCommentHelper();
        $this->quicklinkCommentProxy->createComment($comment);
        $this->assertAttributeNotEmpty("id", $comment);
    }

    public function testRead()
    {
        $comment = $this->quicklinkCommentProxy->getComment("1234132431243");
        $this->assertInstanceOf('mediasilo\comment\Comment', $comment);
    }

    public function testUpdate()
    {
        $comment = $this->createCommentHelper("12345");
        $this->quicklinkCommentProxy->updateComment($comment);
        $this->assertAttributeNotEmpty("id", $comment);
    }
}

