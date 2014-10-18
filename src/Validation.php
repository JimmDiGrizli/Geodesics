<?php
namespace GetSky\Geodesics;

interface Validation
{
    const MAX_LONG = 180;
    const MIN_LONG = -180;
    const MIN_LAT = -90;
    const MAX_LAT = 90;

    public function valid(array $coordinates);
}
