<?php namespace Marceux\Maropost\Api;

use Marceux\Maropost;

class Contacts {

	public function __construct(Maropost $client)
	{
		$this->client = $client;
	}

	/**
	 * Searches for contact in Maropost based on e-mail
	 * @param  [type] $email [description]
	 * @return [type]        [description]
	 */
	public function search($email)
	{
		return $this->client->get('contacts/email', array('contact[email]', $email));
	}

	public function index($listId)
	{
		return $this->client->get("lists/$listId/contacts");
	}

	public function create($listId, $contactData)
	{
		return $this->client->post("lists/$listId/contacts", $contactData);
	}

	public function createWithoutList($contactData)
	{
		return $this->client->post('contacts', $contactData);
	}

	public function new($listId)
	{
		return $this->client->request("lists/$listId/contacts/new");
	}

	public function show($listId, $contactId)
	{
		return $this->client->request("lists/$listId/contacts/$contactId");
	}

	public function update($listId, $contactId, $contactData)
	{
		return $this->client->put("lists/$listId/contacts/$contactId", $contactData);
	}

	public function updateAllLists($email)
	{
		return $this->client->put('contacts/unsubscribe_all', array('contact[email]', $email));
	}

	public function destroy($listId, $contactId)
	{
		return $this->client->delete("lists/$listId/contacts/$contactId", array('contact[email]', $email));
	}
}