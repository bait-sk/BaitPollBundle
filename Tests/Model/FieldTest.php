<?php

/*
 * This file is part of the BaitPollBundle package.
 *
 * (c) BAIT s.r.o. <http://www.bait.sk/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bait\PollBundle\Tests\Model;

use Symfony\Component\Validator\Constraints\Blank;

class FieldTest extends \PHPUnit_Framework_TestCase
{
    protected $pollField;

    protected function setUp()
    {
        $this->pollField = $this->getMockForAbstractClass('Bait\PollBundle\Model\Field');
    }

    protected function tearDown()
    {
        $this->pollField = null;
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

    public function testIsStandalone()
    {
        $this->assertTrue($this->pollField->isStandalone());

        $childField = $this->getMockForAbstractClass('Bait\PollBundle\Model\Field');
        $this->pollField->setParent($childField);
        $this->assertFalse($this->pollField->isStandalone());
    }

    public function testHasNoChildren()
    {
        $this->assertFalse($this->pollField->hasChildren());
    }

    public function testHasChildren()
    {
        $childField = $this->getMockForAbstractClass('Bait\PollBundle\Model\Field');
        $this->pollField->addChild($childField);

        $this->assertTrue($this->pollField->hasChildren());
    }

    public function testIncorrectValidationConstraintInput()
    {
        $this->setExpectedException('ErrorException');

        $this->pollField->addValidationConstraint(new \stdClass);
    }

    public function testGetValidationConstraintsInStandaloneField()
    {
        $this->assertEmpty($this->pollField->getValidationConstraints());

        $blankConstraint = new Blank();
        $this->pollField->addValidationConstraint(new Blank());

        $validationConstraints = $this->pollField->getValidationConstraints();
        $this->assertEquals(1, count($validationConstraints));
        $this->assertEquals($blankConstraint, $validationConstraints[0]);
    }

    public function testGetValidationConstrainsInChildField()
    {
        $childField = $this->getMockForAbstractClass('Bait\PollBundle\Model\Field');
        $this->pollField->setParent($childField);

        $this->assertNull($this->pollField->getValidationConstraints());
    }

    public function testIsRenderable()
    {
        $this->pollField->setActive(true)->setDeletedAt(null);
        $this->assertTrue($this->pollField->isRenderable());
    }

    public function testIsNotRenderable()
    {
        $this->pollField->setActive(true)->setDeletedAt(new \DateTime());
        $this->assertFalse($this->pollField->isRenderable());

        $this->pollField->setActive(false)->setDeletedAt(null);
        $this->assertFalse($this->pollField->isRenderable());

        $this->pollField->setActive(false)->setDeletedAt(new \DateTime());
        $this->assertFalse($this->pollField->isRenderable());
    }
}
