<?php

namespace Utopia\Database\Adapter;

use Exception;
use Utopia\Database\Adapter;
use MongoDB\Client;
use MongoDB\Database;

class MongoDB extends Adapter
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Database|null
     */
    protected $database;

    /**
     * Constructor.
     *
     * Set connection and settings
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create Database
     * 
     * @return bool
     */
    public function create(): bool
    {
        $namespace = $this->getNamespace();
        return (!!$this->client->$namespace);
    }

    /**
     * List Databases
     * 
     * @return array
     */
    public function list(): array
    {
        $list = [];

        foreach ($this->client->listDatabaseNames() as $key => $value) {
            $list[] = $value;
        }
        
        return $list;
    }

    /**
     * Delete Database
     * 
     * @return bool
     */
    public function delete(): bool
    {
        return (!!$this->getDatabase()->dropCollection($this->getNamespace()));
    }

    /**
     * Create Collection
     * 
     * @param string $id
     * @return bool
     */
    public function createCollection(string $id): bool
    {
        return (!!$this->getDatabase()->createCollection($id));
    }

    /**
     * List Collections
     * 
     * @return array
     */
    public function listCollections(): array
    {
        $list = [];

        foreach ($this->getDatabase()->listCollectionNames() as $key => $value) {
            $list[] = $value;
        }
        
        return $list;
    }

    /**
     * Delete Collection
     * 
     * @param string $id
     * @return bool
     */
    public function deleteCollection(string $id): bool
    {
        return (!!$this->getDatabase()->dropCollection($id));
    }

    /**
     * @return Database
     *
     * @throws Exception
     */
    protected function getDatabase()
    {
        if($this->database) {
            return $this->database;
        }

        $namespace = $this->getNamespace();
        
        return $this->client->$namespace;
    }

    /**
     * @return Client
     *
     * @throws Exception
     */
    protected function getClient()
    {
        return $this->client;
    }
}