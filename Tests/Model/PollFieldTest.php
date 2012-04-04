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

    public function testCreatedBy()
    {
        $this->assertNull($this->pollQuestion->getCreatedBy());

        $this->pollQuestion->setCreatedBy('stewie');
        $this->assertEquals('stewie', $this->pollQuestion->getCreatedBy());
    }

    public function testCreatedAt()
    {
        $this->assertEquals(new \DateTime(), $this->pollQuestion->getCreatedAt());
    }

    public function testModifiedBy()
    {
        $this->assertNull($this->pollQuestion->getModifiedBy());

        $this->pollQuestion->setModifiedBy('stewie');
        $this->assertEquals('stewie', $this->pollQuestion->getModifiedBy());
    }

    /**
     * @expectedException ErrorException
     */
    public function testModifiedAtDataType()
    {
        $this->pollQuestion->setModifiedAt('1991-08-27');
    }

    public function testModifiedAt()
    {
        $this->assertNull($this->pollQuestion->getModifiedAt());

        $date = new \DateTime('1991-08-27');

        $this->pollQuestion->setModifiedAt($date);
        $this->assertEquals($date, $this->pollQuestion->getModifiedAt());
    }
}
