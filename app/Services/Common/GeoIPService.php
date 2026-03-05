<?php

namespace App\Services\Common;

use App\Services\BaseService;
use GeoIp2\Database\Reader;

class GeoIPService extends BaseService
{

    private $reader;

    public function __construct()
    {
        $this->reader = new Reader(storage_path('app/geoip/GeoLite2-City.mmdb'));
    }

    public function getLocation($ip)
    {
        try {
            $record = $this->reader->city($ip);
            return [
                'country' => $record->country->name,
                'country_code' => $record->country->isoCode,
                'province' => $record->mostSpecificSubdivision->name,
                'city' => $record->city->name,
                'latitude' => $record->location->latitude,
                'longitude' => $record->location->longitude,
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * 通过ip直接获取所属城市
     * @param $ip
     * @return mixed|string|null
     */
    public function getLocationCity($ip)
    {
        try {
            $record = $this->reader->city($ip);
            return $record->city->name;
        } catch (\Exception $e) {
            return null;
        }
    }

}
