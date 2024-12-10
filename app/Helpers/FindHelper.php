<?php

namespace App\Helpers;


class FindHelper{

    /**
     * @param $resource_class
     * @param $record_id
     * @return mixed
     */
    public static function recordInResource($resource_class, $record_id)
    {
        return call_user_func_array([$resource_class, 'findOrFail'], [$record_id]);
    }

    /**
     * @param $resource_class
     * @param $parameter
     * @param $record_id
     * @return mixed
     */
    public static function whereInResource($resource_class, $parameter, $record_id)
    {
        return call_user_func_array([$resource_class, 'where'], [$parameter, $record_id])->first();
    }
}
