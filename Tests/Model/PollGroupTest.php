<?php

namespace Bait\PollBundle\Tests\Model;

class PollGroupTest extends \PHPUnit_Framework_TestCase
{
    protected $pollGroup;

    protected function setUp()
    {
        $this->pollGroup = $this->getMockForAbstractClass('Bait\PollBundle\Model\PollGroup');
    }

    public function testTitle()
    {
        $this->assertNull($this->pollGroup->getTitle());

        $this->pollGroup->setTitle('Poll #1');
        $this->assertEquals('Poll #1', $this->pollGroup->getTitle());
    }

    public function testCreatedBy()
    {
        $this->assertNull($this->pollGroup->getCreatedBy());

        $this->pollGroup->setCreatedBy('stewie');
        $this->assertEquals('stewie', $this->pollGroup->getCreatedBy());
    }

    public function testCreatedAt()
    {
        $this->assertEquals(new \DateTime(), $this->pollGroup->getCreatedAt());
    }

    public function testModifiedBy()
    {
        $this->assertNull($this->pollGroup->getModifiedBy());

        $this->pollGroup->setModifiedBy('stewie');
        $this->assertEquals('stewie', $this->pollGroup->getModifiedBy());
    }

    /**
     * @expectedException ErrorException
     */
    public function testModifiedAtDataType()
    {
        $this->pollGroup->setModifiedAt('1991-08-27');
    }

    public function testModifiedAt()
    {
        $this->assertNull($this->pollGroup->getModifiedAt());

        $date = new \DateTime('1991-08-27');

        $this->pollGroup->setModifiedAt($date);
        $this->assertEquals($date, $this->pollGroup->getModifiedAt());
    }
}
