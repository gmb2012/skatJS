<?php
namespace SkatJS\Repository;

class MatchRepository extends MongoRepository {
    const COLLECTION_NAME = 'Match';

    protected function getCollection() {
        $collection = parent::getCollection();
        $collection->createIndex(array('date' => 1));
        return $collection;
    }

    public function findAllOldestFirst() {
        return $this->find(array(), array('date' => -1));
    }

    public function findCurrentAllOldestFirst() {
        $lastWeek = new \DateTime();
        $lastWeek->sub(new \DateInterval('P7D'));

        return $this->find(array("date" => array('$gt' => new \MongoDate($lastWeek->getTimestamp()))), array('date' => -1));
    }
}