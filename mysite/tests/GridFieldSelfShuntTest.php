<?php

use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Security\Member;

class GridFieldSelfShuntTest extends SapphireTest {

	/**
	 *
	 * @var GridField
	 */
	protected $gridfield = null;

	// 1. Setup
	public function setUp() {
		parent::setUp();
		$this->gridfield = new GridField('Members');
		$this->gridfield->setModelClass('Member');
		new Form($this, 'MemberForm', new FieldList($this->gridfield), new FieldList());
	}

	public function testMemberInOutput() {
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

	// Self shunted method
	public function hasMethod($method) {
		if($method == 'securityTokenEnabled') {
			return false;
		}
		if($method == 'FormObjectLink') {
			return false;
		}
		throw new PHPUnit_Framework_AssertionFailedError('Form called Controller::hasMethod with unexpected method "'.$method.'"');
	}

	// Self shunted method
	public function Link() {
		return 'link';
	}
}
