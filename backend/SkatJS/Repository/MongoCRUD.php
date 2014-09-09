<?php
namespace SkatJS\Repository;

interface MongoCRUD {

    public function create(array $toCreate);

    public function update(array $toUpdate);

    public function delete(array $criteria);
}