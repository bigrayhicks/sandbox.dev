<?php

use SilverStripe\Control\Controller;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Security\Member;

class GridFieldMockTest extends SapphireTest {

	/**
	 *
	 * @var GridField
	 */
	protected $gridfield = null;

	/**
	 *
	 * @var Controller
	 */
	protected $mockController = null;

	// 1. Setup
	public function setUp() {
		parent::setUp();
		$this->gridfield = new GridField('Members');
		$this->gridfield->setModelClass('Member');
		$this->mockController = $this->getMock('Controller');
		new Form($this->mockController, 'MemberForm', new FieldList($this->gridfield), new FieldList());
	}

	public function testMemberInOutput() {
		// 1. setup
		$this->mockController->expects($this->at(0))
			->method('hasMethod')
			->with($this->stringContains('FormObjectLink'))
			->will($this->returnArgument(false));
		// 1. setup
		$this->mockController->expects($this->at(8))
			->method('hasMethod')
			->with($this->stringContains('FormObjectLink'))
			->will($this->returnArgument(false));


		// 2. Exercise
		$this->gridfield->setList(new ArrayList(array(
			new Member(array('FirstName' => 'Stephen'))
		)));

		// 3. Verify
		$this->assertContains('id="Form_MemberForm_Members"', $this->gridfield->Field());
	}

	// 4. teardown
	public function tearDown() {
		parent::tearDown();
		$this->gridfield = null;
	}
}
