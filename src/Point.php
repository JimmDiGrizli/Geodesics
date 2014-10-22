<?php
namespace GetSky\Geodesics;

class Point {

    const MAX_LONG = 180;
    const MIN_LONG = -180;
    const MIN_LAT = -90;
    const MAX_LAT = 90;

    protected $longitude;
    protected $latitude;

    public function __construct($longitude, $latitude)
    {
        if ($longitude > self::MAX_LONG || $longitude < self::MIN_LONG) {
            throw new \Exception("Incorrect value of longitude");
        }
        if ($latitude > self::MAX_LAT || $latitude < self::MIN_LAT) {
            throw new \Exception("Incorrect value of latitude");
        }

        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }
}
