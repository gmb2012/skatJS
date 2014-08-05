<?php
/**
 * Created by PhpStorm.
 * User: bzapadlo
 * Date: 23/07/14
 * Time: 10:36
 */

namespace SkatJS\Repository;


use SkatJS\Exception\DuplicateException;

class PlayerRepository extends MongoRepository {
    const COLLECTION_NAME = 'Player';

    protected function getCollection() {
        $collection = parent::getCollection();
        $collection->createIndex(array('name' => 1), array('unique' => true));
        return $collection;
    }

    // INTERFFACE METHODS
    public function create(array $toCreate) {
        try {
            return parent::create($toCreate);
        } catch (\MongoDuplicateKeyException $e) {
            throw new DuplicateException();
        }
    }
}