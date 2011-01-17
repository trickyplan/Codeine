<?php

function F_gZip_Compress($Args)
{
    if (!isset($Args['Ratio']))
        $Args['Ratio'] = 1;
    
    if (is_array($Args['Data']))
        foreach($Args['Data'] as $Key => $Value)
            $Output[$Key] = F_gZip_Compress(array('Data'=>$Value, 'Ratio'=>$Args['Ratio']));
    else
        $Output = gzcompress($Args['Data'],$Args['Ratio']);

    return $Output;
}


function F_gZip_Decompress($Args)
{
    if (!isset($Args['Ratio']))
        $Args['Ratio'] = 1;

    if (is_array($Args['Data']))
        foreach($Args['Data'] as $Key => $Value)
            $Output[$Key] = F_gZip_Decompress(array('Data'=>$Value, 'Ratio'=>$Args['Ratio']));
    else
        $Output = gzuncompress($Args['Data'],$Args['Ratio']);
    
    return $Output;
}
