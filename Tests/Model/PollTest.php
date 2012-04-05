<?php

namespace Bait\PollBundle\Tests\Model;

class PollTest extends \PHPUnit_Framework_TestCase
{
    protected $poll;

    protected function setUp()
    {
        $this->poll = $this->getMockForAbstractClass('Bait\PollBundle\Model\Poll');
    }

    public function testTitle()
    {
        $this->assertNull($this->poll->getTitle());

        $this->poll->setTitle('Poll #1');
        $this->assertEquals('Poll #1', $this->poll->getTitle());
    }

    public function testCreatedBy()
    {
        $this->assertNull($this->poll->getCreatedBy());

        $this->poll->setCreatedBy('stewie');
        $this->assertEquals('stewie', $this->poll->getCreatedBy());
    }

    /**
     * @expectedException ErrorException
     */
    public function testCreatedAtDataType()
    {
        $this->poll->setCreatedAt('1991-08-27');
    }

    public function testCreatedAt()
    {
        $this->assertEquals(new \DateTime(), $this->poll->getCreatedAt());

        $date = new \DateTime('1991-08-27');

        $this->poll->setCreatedAt($date);
        $this->assertEquals($date, $this->poll->getCreatedAt());
    }

    public function testModifiedBy()
    {
        $this->assertNull($this->poll->getModifiedBy());

        $this->poll->setModifiedBy('stewie');
        $this->assertEquals('stewie', $this->poll->getModifiedBy());
    }

    /**
     * @expectedException ErrorException
     */
    public function testModifiedAtDataType()
    {
        $this->poll->setModifiedAt('1991-08-27');
    }

    public function testModifiedAt()
    {
        $this->assertNull($this->poll->getModifiedAt());

        $date = new \DateTime('1991-08-27');

        $this->poll->setModifiedAt($date);
        $this->assertEquals($date, $this->poll->getModifiedAt());
    }

    /**
     * @expectedException ErrorException
     */
    public function testStartAtDataType()
    {
        $this->poll->setStartAt('1991-08-27');
    }

    public function testStartAt()
    {
        $this->assertNull($this->poll->getStartAt());

        $date = new \DateTime('1991-08-27');

        $this->poll->setStartAt($date);
        $this->assertEquals($date, $this->poll->getStartAt());
    }

    /**
     * @expectedException ErrorException
     */
    public function testEndAtDataType()
    {
        $this->poll->setEndAt('1991-08-27');
    }

    public function testEndAt()
    {
        $this->assertNull($this->poll->getEndAt());

        $date = new \DateTime('1991-08-27');

        $this->poll->setEndAt($date);
        $this->assertEquals($date, $this->poll->getEndAt());
    }

    public function testIsActive()
    {
        $this->assertTrue($this->poll->isActive());

        $this->poll->setActive(false);
        $this->assertFalse($this->poll->isActive());
    }

    public function testIsVotesVisible()
    {
        $this->assertTrue($this->poll->isVotesVisible());

        $this->poll->setVotesVisible(false);
        $this->assertFalse($this->poll->isVotesVisible());
    }
}
