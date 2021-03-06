<?php
namespace SkatJS\Repository;

class GameRepository extends MongoRepository {
    const COLLECTION_NAME = 'Game';

    protected function getCollection() {
        $collection = parent::getCollection();
        $collection->createIndex(array('match' => 1));
        $collection->createIndex(array('date' => 1));
        return $collection;
    }

    public function findByMatchId($matchId) {
        return $this->find(array('match' => new \MongoId($matchId)), array('date' => 1));
    }
}