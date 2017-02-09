<?php
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;

class SiteTreeVersionBugTest extends SapphireTest {
	/**
	 * Mimic the logic from CMSMain::save() with writeWithoutVersion() and later write()
	 */
	public function testVersionShouldUpdate() {
		// Setup
		$page = new SiteTree();
		$page->Title = 'Initial Title';
		$page->writeWithoutVersion();
		$page->write();
		// assert
		$this->assertEquals(2, $page->Version, 'Version should be to 2');
		
		// Setup
		DataObject::flush_and_destroy_cache();
		$pageFromDB = SiteTree::get()->byId($page->ID);
		// assert fails
		$pageFromDB->Title = 'This is a new Title';
		$pageFromDB->writeWithoutVersion();
		$pageFromDB->write();
		$this->assertEquals(3, $pageFromDB->Version, 'Version should be 3');
	}
	
	/**
	 * Mimic the logic from CMSMain::save() with writeWithoutVersion and later write
	 * but adding the changed field in a form
	 */
	public function testVersionWithFormShouldUpdate() {
		// Setup
		$page = new SiteTree();
		$page->Title = 'Initial Title';
		$page->writeWithoutVersion();
		$page->write();
		// Setup
		DataObject::flush_and_destroy_cache();
		$pageFromDB = SiteTree::get()->byId($page->ID);
		$titleField = new TextField('Title', 'Title', 'This is a new Title');
		$form = new Form($this, 'TestForm', new FieldList(array($titleField)), new FieldList());
		$form->saveInto($pageFromDB);
		// Assert fails
		$pageFromDB->writeWithoutVersion();
		$pageFromDB->write();
		$this->assertEquals(3, $pageFromDB->Version, 'Version should be 3');
	}
}
