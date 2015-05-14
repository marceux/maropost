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
		$this->acct = $acct;
		$this->auth = $auth;
		$this->format = $format;
		
		// Create HTTP Client
		$this->client = new Client([
			'base_url' => "http://api.maropost.com/accounts/$acct/",
			'defaults' => [
				'query' => ['auth_token' => $this->auth]
			]
		]);
	}

	/**
	 * @return [type] [description]
	 */
	public function get($target, $params)
	{
		$target = $target . '.' . $this->format;

		try
		{
			$response = $this->client->get($target, ['query' => $params]);

			return json_decode($response->getBody(), true);
		}
		catch (ClientException $e)
		{
			if ($e->hasResponse())
			{
				$response = $e->getResponse();
				$status = $response->getStatusCode();
				return ['status' => $status];
			}
			else
			{
				return ['status' => 404];
			}
		}
	}

	public function post($target, $params)
	{
		$target = $target . '.' . $this->format;

		try
		{
			$response = $this->client->post($target, [
				'json' => $params,
				'headers' => [
					'Accept' => 'application/json'
				]
			]);

			return json_decode($response->getBody(), true);
		}
		catch (ClientException $e)
		{
			if ($e->hasResponse())
			{
				$response = $e->getResponse();
				$status = $response->getStatusCode();
				return ['status' => $status];
			}
			else
			{
				return ['status' => 404];
			}
		}
	}

	public function put($target, $params)
	{
		$target = $target . '.' . $this->format;

		try
		{
			$response = $this->client->put($target, [
				'json' => $params,
				'headers' => [
					'Accept' => 'application/json'
				]
			]);

			return json_decode($response->getBody(), true);
		}
		catch (ClientException $e)
		{
			if ($e->hasResponse())
			{
				$response = $e->getResponse();
				$status = $response->getStatusCode();
				return ['status' => $status];
			}
			else
			{
				return ['status' => 404];
			}
		}
	}

	public function delete($target, $params)
	{
		$target = $target . '.' . $this->format;

		try
		{
			$response = $this->client->delete($target, ['query' => $params]);
			return json_decode($response->getBody(), true);
		}
		catch (ClientException $e)
		{
			if ($e->hasResponse())
			{
				$response = $e->getResponse();
				$status = $response->getStatusCode();
				return ['status' => $status];
			}
			else
			{
				return ['status' => 404];
			}
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