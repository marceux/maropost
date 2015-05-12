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

	private $apis = array();

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

	/**
	 * Creates URL query string with an array of name and value pairs
	 * @param  array $params Array of arrays of name-value pairs
	 * @return string        The name-value pairs, url encoded.
	 */
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

	/**
	 * Creates the URL used to make a request to Maropost API
	 * @param  array  $args  An array containing target URI and parameter data
	 * @return string        The URL used for the request
	 */
	public function url($args)
	{
		// Set output as empty string
		$output = '';

		// Check if there is at least a target string provided
		// for the first argument
		// ... else return the empty $output
		if (isset($args[0]) && is_string($args[0]))
		{
			// Set $target and $params
			$target = $args[0];
			$params = isset($args[1]) ? $args[1] : null;

			// Start building string output
			$output .= $this->baseUrl;
			$output .= "$target." . $this->format . "?auth_token=";
			$output .= $this->auth;

			// Check for if $params is an array
			if (is_array($params) && !empty($params))
			{
				// Add ampersand for additional query vars
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
		}

		return $output;
	}

	public function get()
	{
		$url = $this->url(func_get_args());
		return "Get: $url";
		/*
		return Request::get($url)
		                ->expects($this)
		                ->send()
		                ->body;
		*/
	}

	public function post()
	{
		$url = $this->url(func_get_args());
		return "Post: $url";
		/*
		return Request::post($url)
		                ->expects($this)
		                ->send()
		                ->body;
		*/
	}

	public function put()
	{
		$url = $this->url(func_get_args());
		return "Put: $url";
		/*
		return Request::put($url)
		                ->expects($this)
		                ->send()
		                ->body;
		*/
	}

	public function delete()
	{
		$url = $this->url(func_get_args());
		return "Delete: $url";
		/*
		return Request::delete($url)
		                ->expects($this)
		                ->send()
		                ->body;
		*/
	}

	/**
	 * Returns the requested class name, optionally using a cached array so no
	 * object is instantiated more than once during a request.
	 *
	 * @param string $class
	 * @return mixed
	 */
	public function getApi($class)
	{
		$class = '\Marceux\Maropost\Api\\' . $class;

		if (!array_key_exists($class, $this->apis))
		{
			$this->apis[$class] = new $class($this);
		}

		return $this->apis[$class];
	}

	/**
	 * @return  \Marceux\Maropost\Api\Contacts
	 */
	public function contacts()
	{
		return $this->getApi('Contacts');
	}
}