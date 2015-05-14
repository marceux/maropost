<?php
 
use Maropost\Maropost;

// Load .env variables
Dotenv::load(__DIR__);

class ContactTest extends PHPUnit_Framework_TestCase {

	protected $acct;
	protected $auth;
	protected $maropost;
	protected $contacts;

	protected function setUp()
	{
		$this->acct = $_ENV['MAROPOST_ACCT'];
		$this->auth = $_ENV['MAROPOST_AUTH'];
		$this->maropost = new Maropost($this->acct, $this->auth);
		$this->contacts = $this->maropost->contacts();
	}

	public function testSearch()
	{
		$result = $this->contacts->search($_ENV['CONTACT_SEARCH_EMAIL']);

		// Test to see if the results returned match the contact
		// whose email we are searching for
		$this->assertEquals($_ENV['CONTACT_SEARCH_ID'], $result['id']);
		$this->assertEquals($_ENV['CONTACT_SEARCH_FNAME'], $result['first_name']);
	}

	public function testIndex()
	{
		$result = $this->contacts->index($_ENV['CONTACT_INDEX_LISTID']);

		// Test to see if the results match the expected size of the list
		$this->assertEquals(count($result), $_ENV['CONTACT_INDEX_LISTSIZE']);
	}

	public function testCreate()
	{
		// Set Contact Data to Test
		$contactData = [
		 	'custom_field' => [
		 		"custom_field_1" => null,
				"custom_field_2" => null
		 	],
			"contact" => [
    			"email" => $_ENV['CONTACT_CREATE_EMAIL'],
    			"first_name" => $_ENV['CONTACT_CREATE_FNAME'],
    			"last_name" => $_ENV['CONTACT_CREATE_LNAME'],
    			"phone" => null,
    			"fax" => null
  			]
		];

		$result = $this->contacts->create($_ENV['CONTACT_CREATE_LISTID'], $contactData);

		// Test if returns result with the right key and value
		$this->assertEquals(strtolower($_ENV['CONTACT_CREATE_EMAIL']), $result['email']);
	}

	public function testCreateWithoutList()
	{
		// Set Contact Data to Test
		$contactData = [
		 	'custom_field' => [
		 		"custom_field_1" => null,
				"custom_field_2" => null
		 	],
			"contact" => [
    			"email" => $_ENV['CONTACT_CREATEWO_EMAIL'],
    			"first_name" => $_ENV['CONTACT_CREATEWO_FNAME'],
    			"last_name" => $_ENV['CONTACT_CREATEWO_LNAME'],
    			"phone" => null,
    			"fax" => null
  			]
		];

		$result = $this->contacts->createWithoutList($contactData);

		// Test if returns result with the right key and value
		$this->assertEquals(strtolower($_ENV['CONTACT_CREATEWO_EMAIL']), $result['email']);
	}

	public function testBlank()
	{
		$result = $this->contacts->blank($_ENV['CONTACT_BLANK_LISTID']);
		$this->assertTrue(is_array($result));
	}

	public function testShow()
	{
		$result = $this->contacts->show($_ENV['CONTACT_SHOW_LISTID'], $_ENV['CONTACT_SHOW_CONTACTID']);

		// Test to see if results pull the correct user
		$this->assertEquals($_ENV['CONTACT_SHOW_CONTACTID'], $result['id']);
	}

	public function testUpdate()
	{
		// Set Contact Data to Test
		$contactData = [
		 	'custom_field' => [
		 		"custom_field_1" => null,
				"custom_field_2" => null
		 	],
			"contact" => [
    			"email" => $_ENV['CONTACT_UPDATE_EMAIL'],
    			"first_name" => $_ENV['CONTACT_UPDATE_FNAME'],
    			"last_name" => $_ENV['CONTACT_UPDATE_LNAME'],
    			"phone" => null,
    			"fax" => null
  			]
		];

		$result = $this->contacts->update($_ENV['CONTACT_UPDATE_LISTID'], $_ENV['CONTACT_UPDATE_CONTACTID'], $contactData);

		$this->assertEquals(strtolower($_ENV['CONTACT_UPDATE_EMAIL']), $result['email']);
	}

	public function testUpdateAllLists()
	{
		$result = $this->contacts->updateAllLists($_ENV['CONTACT_UPDATEALL_EMAIL']);

		$this->assertTrue(is_null($result));
	}

	public function testDestroy()
	{
		$result = $this->contacts->destroy($_ENV['CONTACT_DESTROY_LISTID'], $_ENV['CONTACT_DESTROY_CONTACTID'], $_ENV['CONTACT_DESTROY_EMAIL']);

		$this->assertTrue(is_null($result));
	}

	//--- Providers ---///

	public function credentialsProvider()
	{
		return array(
			array($_ENV['MAROPOST_ACCT'], $_ENV['MAROPOST_AUTH'])
		);
	}
}