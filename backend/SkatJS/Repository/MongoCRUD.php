<?php
/**
 * Created by PhpStorm.
 * User: bzapadlo
 * Date: 23/07/14
 * Time: 10:44
 */

namespace SkatJS\Repository;


interface MongoCRUD {

    public function create(array $toCreate);

    public function update(array $toUpdate);

    public function delete(array $criteria);
}