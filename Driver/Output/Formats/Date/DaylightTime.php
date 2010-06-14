<?php

function F_DaylightTime_Format ($Date)
{
    $Hour = date ('H', $Date);

    $Daylight = 'Night';
    if ($Hour > 5)
        $Daylight = 'Morning';

    if ($Hour > 11)
        $Daylight = 'Day';

    if ($Hour > 18)
        $Daylight = 'Evening';

    if ($Hour > 23)
        $Daylight = 'Night';

    return $Daylight;
} 