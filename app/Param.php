<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Param extends Model
{
    public static function all($columns = ['*'])
    {
        foreach (parent::all()->toArray() as $param) {
            $params[$param['key']] = $param['value'];
        }
        return $params;
    }
}
