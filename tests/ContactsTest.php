<?php
 
use Marceux\Maropost\Maropost;

// Load .env variables
Dotenv::load(__DIR__);

class ContactTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider credentialsProvider
	 */
	public function testConstruct($acct, $auth)
	{
		$m = new Maropost($acct, $auth);
		$c = $m->contacts();
		$this->assertEquals(get_class($c), 'Marceux\Maropost\Api\Contacts');
	}

	/**
	 * @depends testConstruct
	 * @dataProvider credentialsProvider
	 */
	public function testSearch($acct, $auth)
	{
		// Method Args
		$email = 'test@example.com';

		$m = new Maropost($acct, $auth);
		$output = "Get: " . $m->baseUrl . "contacts/email.json?auth_token=$auth&contact[email]=" . urlencode($email);
		$c = $m->contacts();
		$result = $c->search($email);
		$this->assertEquals($result, $output);
	}

	/**
	 * @depends testConstruct
	 * @dataProvider credentialsProvider
	 */
	public function testIndex($acct, $auth)
	{
		// Method Args
		$listId = 1;

		$m = new Maropost($acct, $auth);
		$output = "Get: " . $m->baseUrl . "lists/$listId/contacts.json?auth_token=$auth";
		$c = $m->contacts();
		$result = $c->index($listId);
		$this->assertEquals($result, $output);
	}

	/**
	 * @depends testConstruct
	 * @dataProvider credentialsProvider
	 */
	public function testCreate($acct, $auth)
	{
		// Method Args
		$listId = 1;
		$contactData = array('test', 'value');

		$m = new Maropost($acct, $auth);
		$output = "Post: " . $m->baseUrl . "lists/$listId/contacts.json?auth_token=$auth&test=value";
		$c = $m->contacts();
		$result = $c->create($listId, $contactData);
		$this->assertEquals($result, $output);
	}

	/**
	 * @depends testConstruct
	 * @dataProvider credentialsProvider
	 */
	public function testCreateWithoutList($acct, $auth)
	{
		// Method Args
		$contactData = array('test', 'value');

		$m = new Maropost($acct, $auth);
		$output = "Post: " . $m->baseUrl . "contacts.json?auth_token=$auth&test=value";
		$c = $m->contacts();
		$result = $c->createWithoutList($contactData);
		$this->assertEquals($result, $output);
	}

	/**
	 * @depends testConstruct
	 * @dataProvider credentialsProvider
	 */
	public function testBlank($acct, $auth)
	{
		// Method Args
		$listId = 1;

		$m = new Maropost($acct, $auth);
		$output = "Get: " . $m->baseUrl . "lists/$listId/contacts/new.json?auth_token=$auth";
		$c = $m->contacts();
		$result = $c->blank($listId);
		$this->assertEquals($result, $output);
	}

	/**
	 * @depends testConstruct
	 * @dataProvider credentialsProvider
	 */
	public function testShow($acct, $auth)
	{
		// Method Args
		$listId = 1;
		$contactId = 1;

		$m = new Maropost($acct, $auth);
		$output = "Get: " . $m->baseUrl . "lists/$listId/contacts/$contactId.json?auth_token=$auth";
		$c = $m->contacts();
		$result = $c->show($listId, $contactId);
		$this->assertEquals($result, $output);
	}

	/**
	 * @depends testConstruct
	 * @dataProvider credentialsProvider
	 */
	public function testUpdate($acct, $auth)
	{
		// Method Args
		$listId = 1;
		$contactId = 1;
		$contactData = array('test', 'value');		

		$m = new Maropost($acct, $auth);
		$output = "Put: " . $m->baseUrl . "lists/$listId/contacts/$contactId.json?auth_token=$auth&test=value";
		$c = $m->contacts();
		$result = $c->update($listId, $contactId, $contactData);
		$this->assertEquals($result, $output);
	}

	/**
	 * @depends testConstruct
	 * @dataProvider credentialsProvider
	 */
	public function testUpdateAllLists($acct, $auth)
	{
		// Method Args
		$email = 'test@example.com';

		$m = new Maropost($acct, $auth);
		$output = "Put: " . $m->baseUrl . "contacts/unsubscribe_all.json?auth_token=$auth&contact[email]=" . urlencode($email);
		$c = $m->contacts();
		$result = $c->updateAllLists($email);
		$this->assertEquals($result, $output);
	}

	/**
	 * @depends testConstruct
	 * @dataProvider credentialsProvider
	 */
	public function testDestroy($acct, $auth)
	{
		// Method Args
		$listId = 1;
		$contactId = 1;
		$email = 'test@example.com';

		$m = new Maropost($acct, $auth);
		$output = "Delete: " . $m->baseUrl . "lists/$listId/contacts/$contactId.json?auth_token=$auth&contact[email]=" . urlencode($email);
		$c = $m->contacts();
		$result = $c->destroy($listId, $contactId, $email);
		$this->assertEquals($result, $output);
	}

	//--- Providers ---///

	public function credentialsProvider()
	{
		return array(
			array($_ENV['MAROPOST_ACCT'], $_ENV['MAROPOST_AUTH'])
		);
	}
}