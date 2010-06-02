<?php

if (!function_exists('zodiacrev'))
{
    function zodiacrev($x) { return $x-floor($x/360.0)*360.0; };
}

function F_EuropeanZodiacIcon_Format ($Date)
{

        $European = array('♈','♉','♊','♋','♌','♍','♎','♏','♐','♑','♒','♓');

        $Year  = date('Y',$Date);
        $Month = date('m',$Date);
        $Day   = date('d',$Date);

        $d = (367*$Year-floor((7*($Year+floor(($Month+9)/12)))/4)+floor((275*$Month)/9)+$Day-730530);
        $w = zodiacrev(282.9404+4.70935E-5*$d); $M=zodiacrev(356.0470+0.9856002585*$d);
        
    return $European[floor(zodiacrev($w+$M)/30.0)];
}

    