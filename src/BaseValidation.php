<?php
namespace GetSky\Geodesics;

class BaseValidation implements Validation
{

    public function valid(array $coordinates)
    {
        if ($coordinates[0] > $this::MAX_LONG || $coordinates[0] < $this::MIN_LONG) {
            throw new \Exception("Incorrect value of longitude");
        }
        if ($coordinates[1] > $this::MAX_LAT || $coordinates[1] < $this::MIN_LAT) {
            throw new \Exception("Incorrect value of latitude");
        }

        return true;
    }
}
