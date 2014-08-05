<?php
namespace SkatJS\Model;


interface CRUDItem {

    public function save();

    public function delete();
}