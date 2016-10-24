<?php namespace Maropost;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Maropost {

	private $acct;
	private $auth;
	private $format;
	
	private $client;

	private $apis = array();

	/**
	 * Construct Maropost API object using the acct number and
	 * authentication key
	 * @param string $acct Account number
	 * @param string $auth Authentication key
	 * @param string $format The format the responses and requests will be in
	 */
	public function __construct($acct, $auth, $format = 'json')
	{
    if(!$acct)
    {
      throw new \Exception("Maropost account ID is required. Get it from https://app.maropost.com");
    }

    if(!$auth)
    {
      throw new \Exception("Maropost auth token is required. Get it from https://app.maropost.com/accounts/{$acct}/connections");
    }
    
		// Set base properties
		$this->acct = $acct;
		$this->auth = $auth;
		$this->format = $format;
		
		// Create HTTP Client with some default options
    $args = [
			'base_uri' => "http://api.maropost.com/accounts/$acct/",
			'query' => ['auth_token' => $this->auth],
    ];
		$this->client = new Client($args);
	}

	/**
	 * Appends the format of the request to the target uri
	 * @param  string $target The target URI
	 * @return string         Target URI with appended format (.json or .xml)
	 */
	private function buildTarget($target)
	{
		return $target . '.' . $this->format;
	}

	/**
	 * Function to handle exceptions caught in the request methods
	 * @param  object $exception [description]
	 * @return [type]            [description]
	 */
	private function handleException($exception)
	{			
		// Check if the Exception has a response
		if ($exception->hasResponse())
		{
			// Use the exception response to a build an array to return
			// with the status code (so at least we have something)
			$response = $exception->getResponse();
			$status = $response->getStatusCode();
			return ['status' => $status];
		}
		else
			return ['status' => 404];
	}

	/**
	 * Function to perform GET method on $target URI with $params as query parameters
	 * @param  string $target The URI target that will be added to the base url
	 * @param  array  $params The query string and values structured as an array
	 * @return array          What Maropost returns as an array structure
	 */
	public function get($target, $params = [])
	{
		$target = $this->buildTarget($target);

		// Try to get a response from Maropost API using Guzzle Client
		try
		{
			$response = $this->client->get($target, ['query' => $params]);

			// If successful, return an array that decodes the returned JSON
			return json_decode($response->getBody(), true);
		}
		catch (ClientException $e)
		{
			return $this->handleException($e);
		}
	}

	/**
	 * Function to perform POST method on $target URI with $params as JSON body
	 * @param  string $target The URI target that will be added to the base url
	 * @param  array  $params The query string and values structured as an array
	 * @return array          What Maropost returns as an array structure
	 */
	public function post($target, $params = [])
	{
		$target = $this->buildTarget($target);
		try
		{
			$response = $this->client->post($target, [
				'json' => $params,
				'headers' => [
					'Accept' => 'application/json'
				],
        'debug'=>true,
			]);
			return json_decode($response->getBody(), true);
		}
		catch (ClientException $e)
		{
			return $this->handleException($e);
		}
	}

	/**
	 * Function to perform PUT method on $target URI with $params as JSON body
	 * @param  string $target The URI target that will be added to the base url
	 * @param  array  $params The query string and values structured as an array
	 * @return array          What Maropost returns as an array structure
	 */
	public function put($target, $params = [])
	{
		$target = $this->buildTarget($target);

		try
		{
			$response = $this->client->put($target, [
				'json' => $params,
				'headers' => [
					'Accept' => 'application/json'
				],
			]);

			return json_decode($response->getBody(), true);
		}
		catch (ClientException $e)
		{
			return $this->handleException($e);
		}
	}

	/**
	 * Function to perform DELETE method on $target URI with $params as query parameters
	 * @param  string $target The URI target that will be added to the base url
	 * @param  array  $params The query string and values structured as an array
	 * @return array          What Maropost returns as an array structure
	 */
	public function delete($target, $params = [])
	{
		$target = $this->buildTarget($target);

		try
		{
			$response = $this->client->delete($target, ['query' => $params]);
			return json_decode($response->getBody(), true);
		}
		catch (ClientException $e)
		{
			return $this->handleException($e);
		}
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
		$class = '\Maropost\api\\' . $class;

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