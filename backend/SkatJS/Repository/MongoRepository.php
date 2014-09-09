<?php
namespace SkatJS\Repository;

use SkatJS\Exception\Exception;

abstract class MongoRepository implements MongoCRUD {
    const COLLECTION_NAME = null;

    protected $config;
    protected $connection;
    protected $collections = array();

    public function __construct(\stdClass $config) {
        $this->config = $config;
    }

    // DB HANDLING
    private function getConnection() {
        if($this->connection === null) {
            $this->connection = (new \MongoClient($this->config->uri))->selectDB($this->config->db);
        }

        return $this->connection;
    }

    protected function getCollectionByName($collectionName) {
        if(!isset($this->collections[$collectionName])) {
            try {
                $this->collections[$collectionName] = $this->getConnection()->selectCollection($collectionName);
            } catch (Exception $e) {
                $this->collections[$collectionName] = $this->getConnection()->createCollection($collectionName);
            }

        }

        return $this->collections[$collectionName];
    }

    protected function getCollection() {
        if(static::COLLECTION_NAME === null) {
            throw new Exception("COLLECTION_NAME has to be set");
        }

        return $this->getCollectionByName(static::COLLECTION_NAME);
    }

    // CONVERTER
    protected function mongoToModel(array $mongoElement) {
        $itemClass = 'SkatJS\\Model\\' . str_replace(array('\\', 'SkatJS', 'Repository'), '', get_class($this));

        $returnValue = new $itemClass($this);
        $returnValue->fromMongo($mongoElement);

        return $returnValue;
    }

    // QUERY METHODS
    protected function findOne(array $criteria) {
        return $this->mongoToModel($this->getCollection()->findOne($criteria));
    }

    protected function find(array $criteria, array $sort = array()) {
        $returnValue = array();

        foreach($mongoResult = $this->getCollection()->find($criteria)->sort($sort) as $element) {
            $returnValue[] = $this->mongoToModel($element);
        }

        return $returnValue;
    }

    // ACCESSOR METHODS
    public function findAll() {
        return $this->find(array());
    }

    public function findById($id) {
        return $this->findOne(array('_id' => new \MongoId($id) ));
    }

    // INTERFACE METHODS
    public function create(array $toCreate) {
        $this->getCollection()->insert($toCreate);
        return $toCreate['_id']->{'$id'};
    }

    public function update(array $toCreate) {
        throw new Exception('Implement me');
    }

    public function delete(array $criteria) {
        $this->getCollection()->remove($criteria);
    }
} 