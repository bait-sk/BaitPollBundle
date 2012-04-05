<?php

namespace Bait\PollBundle\Tests\Model;

class PollFieldTest extends \PHPUnit_Framework_TestCase
{
    protected $pollQuestion;

    protected function setUp()
    {
        $this->pollQuestion = $this->getMockForAbstractClass('Bait\PollBundle\Model\PollField');
    }

    public function testTitle()
    {
        $this->assertNull($this->pollQuestion->getTitle());

        $this->pollQuestion->setTitle('Poll #1');
        $this->assertEquals('Poll #1', $this->pollQuestion->getTitle());
    }

    public function testCreatedAt()
    {
        $this->assertEquals(new \DateTime(), $this->pollQuestion->getCreatedAt());

        $date = new \DateTime('1991-08-27');

        $this->pollQuestion->setCreatedAt($date);
        $this->assertEquals($date, $this->pollQuestion->getCreatedAt());
    }
}
