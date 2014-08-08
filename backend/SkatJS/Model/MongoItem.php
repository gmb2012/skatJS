<?php
namespace SkatJS\Model;


use SkatJS\Exception\Exception;
use SkatJS\Repository\MongoRepository;

abstract class MongoItem implements CRUDItem {
    protected $id;
    protected $repository;

    public function __construct(MongoRepository $repository) {
        $this->repository = $repository;
    }

    // ABSTRACT METHODS
    abstract public function fromJson(\stdClass $json);

    // INTERFACE METHODS
    public function save() {
        if($this->id === null) {
            $this->id = $this->repository->create($this->toMongo());
        } else {
            throw new Exception('implement me');
        }
    }

    public function delete() {
        $this->repository->delete(array('_id' => $this->id));
    }

    // HELPER METHODS
    public function toJson() {
        $returnValue = new \stdClass();

        foreach(get_object_vars($this) as $varName => $value) {
            if($value !== null && $varName !== 'repository') {
                $returnValue->{$varName} = $value;
            }
        }

        return $returnValue;
    }

    protected function setIfSet($name, \stdClass $item) {
        if(isset($item->$name)) {
            call_user_func(array($this, 'set' . ucfirst($name)), $item->$name);
        }
    }

    public function fromMongo(array $mongoData) {
        foreach($mongoData as $varName => $data) {
            if($varName === '_id') {
                $varName = 'id';
                $data = (string) $data;
            }

            call_user_func(array($this, 'set' . ucfirst($varName)), $data);
        }
    }

    public function toMongo() {
        $returnValue = array();

        foreach(get_object_vars($this) as $varName => $value) {
            if($value !== null && $varName !== 'repository') {
                if($varName === 'id') {
                    $varName = '_id';
                }

                $returnValue[$varName] = $value;
            }
        }

        return $returnValue;
    }

    // SETTER AND GETTER
    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \SkatJS\Repository\MongoRepository $repository
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \SkatJS\Repository\MongoRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }


}