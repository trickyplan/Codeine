<?php

function F_ShortTimeWithSup_Format ($Date)
{
    return date ('H', $Date).'<sup>'.date ('i', $Date).'</sup>';
} 