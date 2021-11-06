<?php
/**
 * Created by PhpStorm.
 * User: AHMED HASSAN
 */

namespace App\Helpers;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Utilities
{

    private static $settings = null;

    /**
     * Get Setting
     *
     * @param $key
     * @param $table
     * @return null
     */
    public static function setting($key, $table)
    {
        if (self::$settings == null) {
            self::$settings = \Illuminate\Support\Facades\DB::table($table)->get();
        }
        return _objectGet(self::$settings->where('key', $key)->first(), 'value');
    }

    /**
     * Update Setting
     *
     * @param $column
     * @param $updateValues
     * @return int
     */
    public static function updateSetting($column, $updateValues)
    {
        $exists = DB::table('options')->where('key', $column)->count();
        if ($exists) {
            return DB::table('options')->where('key', $column)->update($updateValues);
        }
        return DB::table('options')->insert(array_merge($updateValues, ['key' => $column]));
    }

    public static function distanceQuery($lat, $long, $table)
    {
        if (!$lat || !$long) return null;
        return " Round(1.609344*(6371 * acos(cos(radians($lat)) 
                     * cos(radians($table.lat)) 
                     * cos(radians($table.long) 
                     - radians($long)) 
                     + sin(radians($lat)) 
                     * sin(radians($table.lat))))) as distance";
    }

    public static function convertMilesToKm($distance)
    {
        $m_to_k = $distance * 1.609344;
        $km = floor($m_to_k);
        if ($km >= 1) {
            $d = $km . ' km';
        }else {
            $d = ceil($m_to_k * 1000) . ' m';
        }
        return $d;
    }

}
