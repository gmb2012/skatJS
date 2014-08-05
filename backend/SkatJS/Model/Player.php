<?php
namespace SkatJS\Model;

class Player extends MongoItem {
    protected $name;

    public function fromJson(\stdClass $json) {
        $this->setIfSet('id', $json);
        $this->setIfSet('name', $json);
    }

    // SETTER AND GETTER
    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}