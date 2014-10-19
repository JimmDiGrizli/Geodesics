<?php
namespace GetSky\Geodesics;

class Geodesics
{

    const MAX_LONG = 180;
    const MIN_LONG = -180;
    const MIN_LAT = -90;
    const MAX_LAT = 90;

    private $first;
    private $second;

    public function distance(array $first, array $second)
    {
        $distance = '100';
        return $distance;
    }

    public function setFirstPoint($longitude, $latitude)
    {
        $this->validation($longitude, $latitude);
        $this->first = [$longitude, $latitude];
    }

    public function getFirstPoint()
    {
        return $this->first;
    }

    public function setSecondPoint($longitude, $latitude)
    {
        $this->validation($longitude, $latitude);
        $this->second = [$longitude, $latitude];
    }

    public function getSecondPoint()
    {
        return $this->second;
    }

    protected function validation($longitude, $latitude)
    {
        if ($longitude > self::MAX_LONG || $longitude < self::MIN_LONG) {
            throw new \Exception("Incorrect value of longitude");
        }
        if ($latitude > self::MAX_LAT || $latitude < self::MIN_LAT) {
            throw new \Exception("Incorrect value of latitude");
        }
    }
}
