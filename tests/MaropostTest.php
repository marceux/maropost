<?php

use Maropost\Maropost;

// Load .env variables
Dotenv::load(__DIR__);

class MaropostTest extends PHPUnit_Framework_TestCase {

	protected $acct;
	protected $auth;
	protected $maropost;

	protected function setUp()
	{
		$this->acct = $_ENV['MAROPOST_ACCT'];
		$this->auth = $_ENV['MAROPOST_AUTH'];
		$this->maropost = new Maropost($this->acct, $this->auth);
	}

	/**
	 * @dataProvider getProvider
	 */
	public function testGet($target, $params)
	{
		$response = $this->maropost->get($target, $params);
		$this->assertTrue(is_array($response));
	}

	/**
	 * @dataProvider postProvider
	 */
	public function testPost($target, $params)
	{
		$response = $this->maropost->post($target, $params);
		$this->assertTrue(is_array($response));
	}

	/**
	 * @dataProvider putProvider
	 */
	public function testPut($target, $params)
	{
		$response = $this->maropost->put($target, $params);
		$this->assertTrue(is_array($response));
	}

	/**
	 * @dataProvider deleteProvider
	 */
	public function testDelete($target, $params)
	{
		$response = $this->maropost->delete($target, $params);
		$this->assertTrue(is_null($response) || is_array($response));
	}

	//--- Providers ---///
	public function getProvider()
	{
		return [
			[
				'contacts/email',
				['contact' => ['email' => $_ENV['GET_EMAIL']]]
			],
			[
				'target/test',
				['name', 'value']
			],
			[
				'lists/16546/contacts',
				[]
			]
		];
	}

	public function postProvider()
	{
		return [
			[
				'contacts',
				 [
				 	'custom_field' => [
				 	    "custom_field_1" => null,
						"custom_field_2" => null,
						"custom_field_3" => null,
				 	],
				 	"contact" => [
    					"email" => $_ENV['POST_EMAIL'],
    					"first_name" => $_ENV['POST_FNAME'],
    					"last_name" => $_ENV['POST_LNAME'],
    					"phone" => null,
    					"fax" => null
  					]
				]
			],
			[
				'target/test',
				['name', 'value']
			]
		];
	}

	public function putProvider()
	{
		return [
			[
				"lists/{$_ENV['LIST_ID']}/contacts/{$_ENV['CONTACT_ID']}",
				 [
				 	'custom_field' => [
				 	    "custom_field_1" => null,
						"custom_field_2" => null
				 	],
				 	"contact" => [
    					"email" => $_ENV['PUT_EMAIL'],
    					"first_name" => $_ENV['PUT_FNAME'],
    					"last_name" => $_ENV['PUT_LNAME'],
    					"phone" => null,
    					"fax" => null
  					]
				]
			],
			[
				'target/test',
				['name', 'value']
			]
		];
	}

	public function deleteProvider()
	{
		return [
			[
				"lists/{$_ENV['LIST_ID']}/contacts/{$_ENV['CONTACT_ID']}",
				 []
			],
			[
				'target/test',
				['name', 'value']
			]
		];
	}
}