<?php
namespace App;

class ORM extends \ORM {
    public static function factory($model, $id = NULL)
    {
        return new $model($id);
    }
}