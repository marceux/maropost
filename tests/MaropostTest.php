<?php
 
use Marceux\Maropost\Maropost;

// Load .env variables
Dotenv::load(__DIR__);

class MaropostTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider credentialsProvider
	 */
	public function testConstruct($acct, $auth)
	{
		$m = new Maropost($acct, $auth);
		$this->assertEquals($m->baseUrl, "http://api.maropost.com/accounts/$acct/");
	}

	/**
	 * @depends testConstruct
	 */
	public function testParam()
	{
		$m = new Maropost('foo', 'bar');
		$queryV = urlencode('value');
		$this->assertEquals($m->param('test', 'value'), "test=$queryV");
	}

	/**
	 * @depends testParam
	 * @dataProvider parametersProvider
	 */
	public function testParams($params, $output)
	{
		$m = new Maropost('foo', 'bar');
		$this->assertEquals($m->params($params), $output); 
	}

	/**
	 * @depends testParam
	 * @depends testParams
	 * @dataProvider urlsProvider
	 */
	public function testUrl($service, $target, $format, $params, $output)
	{
		$m = new Maropost('foo', 'bar', $format);
		$this->assertEquals($m->url($service, $target, $params), $output);
	}

	public function testResponse()
	{
		$m = new Maropost( $_ENV['MAROPOST_ACCT'], $_ENV['MAROPOST_AUTH'] );
		$this->assertTrue(is_array($m->contactSearch('Test-MS-20150508-2@mailnesia.com')));
	}

	//--- Providers ---///

	public function credentialsProvider()
	{
		return array(
			array($_ENV['MAROPOST_ACCT'], $_ENV['MAROPOST_AUTH'])
		);
	}

	public function parametersProvider()
	{
		return array(
			// One Parameter
			array( 
				array( 
					array('test', 'value')
				),
				'test=value'
			),
			
			// Two Parameters
			array(
				array(
					array('test', 'value'),
					array('foo', 'bar'),
				),
				'test=value&foo=bar'
			),

			// Three Parameters
			array( 
				array(
					array('test', 'value'),
					array('foo', 'bar'),
					array('necro', 'd!a@n#c$e%r%'),
				),
				'test=value&foo=bar&necro=d%21a%40n%23c%24e%25r%25'
			),
		);
	}

	public function urlsProvider()
	{
		return array(
			array(
				// Service
				'service',

				// Target
				'target',
				
				// Type
				'json',

				// Parameters
				array(),

				// Output
				'http://api.maropost.com/accounts/foo/service/target.json?auth_token=bar',
			),
			array(
				// Service
				'service',
				
				// Target
				'target',
				
				// Type
				'json',

				// Parameters
				array(
					array('test', 'value')
				),

				// Output
				'http://api.maropost.com/accounts/foo/service/target.json?auth_token=bar&test=value',
			),
			array(
				// Service
				'service',
				
				// Target
				'target',
				
				// Type
				'xml',

				// Parameters
				array(
					array('test', 'value'),
					array('foo', 'bar')
				),

				// Output
				'http://api.maropost.com/accounts/foo/service/target.xml?auth_token=bar&test=value&foo=bar',
			),
			array(
				// Service
				'service',
				
				// Target
				'target',
				
				// Type
				'json',

				// Parameters
				array('test', 'value'),

				// Output
				'http://api.maropost.com/accounts/foo/service/target.json?auth_token=bar&test=value',
			),
		);
	}
}