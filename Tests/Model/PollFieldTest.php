<?php

namespace Bait\PollBundle\Tests\Model;

class PollFieldTest extends \PHPUnit_Framework_TestCase
{
    protected $pollField;

    protected function setUp()
    {
        $this->pollField = $this->getMockForAbstractClass('Bait\PollBundle\Model\PollField');
    }

    public function testTitle()
    {
        $this->assertNull($this->pollField->getTitle());

        $this->pollField->setTitle('Poll #1');
        $this->assertEquals('Poll #1', $this->pollField->getTitle());
    }

    public function testCreatedAt()
    {
        $this->assertEquals(new \DateTime(), $this->pollField->getCreatedAt());

        $date = new \DateTime('1991-08-27');

        $this->pollField->setCreatedAt($date);
        $this->assertEquals($date, $this->pollField->getCreatedAt());
    }
}
