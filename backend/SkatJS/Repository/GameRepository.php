<?php
/**
 * Created by PhpStorm.
 * User: bzapadlo
 * Date: 23/07/14
 * Time: 10:36
 */

namespace SkatJS\Repository;


use SkatJS\Exception\DuplicateException;

class GameRepository extends MongoRepository {
    const COLLECTION_NAME = 'Game';

    protected function getCollection() {
        $collection = parent::getCollection();
        $collection->createIndex(array('match' => 1));
        $collection->createIndex(array('date' => 1));
        return $collection;
    }

    public function findByMatchId($matchId) {
        var_dump($matchId);
        var_dump($this->find(array(array('match' => new \MongoId($matchId) )), array('date' => 1)));
        exit('here');
        return $this->find(array(array('match' => new \MongoId($matchId) )), array('date' => 1));
    }
}