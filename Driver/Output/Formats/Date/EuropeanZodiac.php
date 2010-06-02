<?php

function rev($x) { return $x-floor($x/360.0)*360.0; };

function F_EuropeanZodiac_Format ($Date)
{
        $European = array('Aries','Taurus','Gemini','Cancer','Leo','Virgo','Libra',
                        'Scorpio','Sagittarius','Capricorn','Aquarius','Pisces');

        $Year  = date('Y',$Date);
        $Month = date('m',$Date);
        $Day   = date('d',$Date);

        $d = (367*$Year-floor((7*($Year+floor(($Month+9)/12)))/4)+floor((275*$Month)/9)+$Day-730530);
        $w = rev(282.9404+4.70935E-5*$d); $M=rev(356.0470+0.9856002585*$d);
        
    return $European[floor(rev($w+$M)/30.0)];
}

    