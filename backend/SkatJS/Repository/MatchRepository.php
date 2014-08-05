<?php
/**
 * Created by PhpStorm.
 * User: bzapadlo
 * Date: 23/07/14
 * Time: 10:36
 */

namespace SkatJS\Repository;


use SkatJS\Exception\DuplicateException;

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
}