<?php
namespace SkatJS\Model;

abstract class Base {
    public function toJson() {
        $returnValue = new \stdClass();

        foreach(get_object_vars($this) as $varName => $value) {
            $returnValue->{$varName} = $value;
        }

        return $returnValue;
    }
} 