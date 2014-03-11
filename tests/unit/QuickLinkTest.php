<?php
require 'vendor/autoload.php';

use mediasilo\quicklink\QuickLinkProxy;
use mediasilo\quicklink\Configuration;
use mediasilo\quicklink\Setting;
use mediasilo\quicklink\QuickLink;
use mediasilo\http\MediaSiloResourcePaths;

class QuickLinkTest extends PHPUnit_Framework_TestCase
{
    protected $webClient;
    protected $quicklinkProxy;

    protected function setUp()
    {
        $self = $this;

        $this->webClient = $this->getMockBuilder('mediasilo\http\WebClient')
            ->disableOriginalConstructor()
            ->getMock();

        $this->webClient->expects($this->any())
            ->method('post')
            ->will($this->returnValue(json_encode($this->createQuicklinkHelper("123423141243214324"))));

        $this->webClient->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function($id = null) use ($self) {
                if ($id != MediaSiloResourcePaths::QUICKLINK ) {
                    return json_encode($self->createQuicklinkHelper("12342314"));
                } else {
                    $qlList = array();
                    for ($i=0; $i<4; $i++) array_push($qlList, $self->createQuicklinkHelper("i"));
                    return json_encode($qlList);
                }
            }));

        $this->webClient->expects($this->any())
            ->method('put')
            ->will($this->returnValue(json_encode($this->createQuicklinkHelper("123423141243214324"))));

        $this->quicklinkProxy = new QuickLinkProxy($this->webClient);
    }

    public function createQuicklinkHelper($id = null)
    {
        $title = "Test Quicklink Title";
        $description = "Test Quicklink Description";
        $assets = array("12345-1234-1234-12345678");
        $settings = new Setting("visibility", "public");
        $config = new Configuration("12345", array($settings));
        $quickLink = new Quicklink($assets, $config, $description , array(), $title);
        if ($id != null) $quickLink->id = $id;
        return $quickLink;
    }

    public function testCreate()
    {
        $quickLink = $this->createQuicklinkHelper();
        $this->quicklinkProxy->createQuickLink($quickLink);
        $this->assertAttributeNotEmpty("id", $quickLink);
    }

    public function testRead()
    {
        $quicklinks = $this->quicklinkProxy->getQuickLinks();
        $this->assertNotEmpty($quicklinks);
        $this->assertEquals(4, count($quicklinks));
    }

    public function testReadOne()
    {
        $quicklink = $this->quicklinkProxy->getQuickLink("1234132431243");
        $this->assertInstanceOf('mediasilo\quicklink\QuickLink', $quicklink);
    }

    public function testUpdate()
    {
        $quickLink = $this->createQuicklinkHelper("12345");
        $this->quicklinkProxy->updateQuicklink($quickLink);
        $this->assertAttributeNotEmpty("id", $quickLink);
    }

}

