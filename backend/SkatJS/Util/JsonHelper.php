<?php
/**
 * Created by PhpStorm.
 * User: bzapadlo
 * Date: 05/08/14
 * Time: 16:55
 */

namespace SkatJS\Util;


class JsonHelper {

    public static function toJsonList(array $items) {
        $returnValue = array();
        foreach($items as $item) {
            $returnValue[] = $item->toJson();
        }

        return $returnValue;
    }
} 