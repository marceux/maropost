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
	}

	//--- Providers ---///

	public function credentialsProvider()
	{
		return array(
			array($_ENV['MAROPOST_ACCT'], $_ENV['MAROPOST_AUTH'])
		);
	}
}