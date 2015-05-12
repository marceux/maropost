<?php namespace Marceux\Maropost;

use Httpful\Httpful;
use Httpful\Request;
use Httpful\Mime;
use Httpful\Handlers\JsonHandler;
use Httpful\Handlers\XmlHandler;

class Maropost {

	private $acct;
	private $auth;
	private $format;

	public $baseUrl = "http://api.maropost.com/accounts/";

	/**
	 * Construct Maropost API object using the acct number and
	 * authentication key
	 * @param string $acct Account number
	 * @param string $auth Authentication key
	 * @param string $format The format the responses and requests will be in
	 */
	public function __construct($acct, $auth, $format = 'json')
	{
		$this->acct = $acct;
		$this->auth = $auth;

		// Store format of requests and responses
		$this->format = $format;

		switch ($format) {
			case 'xml':
				Httpful::register(Mime::getFullMime($format), new XmlHandler());
				break;
			
			case 'json':
			default:
				Httpful::register(Mime::getFullMime($format), new JsonHandler(array('decode_as_array' => true)));
				break;
		}
		

		$this->baseUrl .= "$acct/";
	}

	/**
	 * Create URL query string with name and value pairs
	 * @param  string $name  Name of query string parameter
	 * @param  string $value Value of query string parameter
	 * @return string        The name-value pair, url encoded.
	 */
	public function param($name, $value)
	{
		// url encode the value
		$value = urlencode($value);

		return "$name=$value";
	}

	public function params($params)
	{
		$numItems = count($params); // number of items
		$i = 0;                     // array counter
		$output = '';               // output object

		// Iterate over parameters
		foreach($params as $param) {
			$output .= $this->param($param[0], $param[1]);
			
			// If counter reaches the last item...
			if(++$i < $numItems) {
				// ...append ampersand
				$output .= '&';
			}
		}

		return $output;
	}

	public function url($service, $target, $params)
	{
		$format = $this->format;

		// Start fleshing output
		$output = $this->baseUrl;
		$output .= "$service/";
		$output .= "$target.$format?auth_token=";
		$output .= $this->auth;

		// Check for Parameters array
		if (is_array($params) && !empty($params))
		{
			// Add ampersand
			$output .= "&";

			// Check if multiple parameters
			if (is_array($params[0]))
			{
				if (count($params) > 1)
					$output .= $this->params($params);
				else
					$output .= $this->param($params[0][0], $params[0][1]);
			}

			else
				$output .= $this->param($params[0], $params[1]);
		}

		return $output;
	}

	public function request($url)
	{
		return Request::get($url)->expects($this->format)->send();
	}

	public function contactSearch($email)
	{
		$url = $this->url('contacts', 'email', array('contact[email]', $email));

		// Make API Response
		$response = $this->request($url);
		return $response->body;
	}
}