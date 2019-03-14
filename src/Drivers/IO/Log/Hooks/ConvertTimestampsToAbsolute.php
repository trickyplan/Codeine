<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeLogSpit', function ($Call)
    {
        $Call = F::Apply('IO', 'Open', $Call, ['Storage' => $Call['Channel']]);

        if (F::Dot($Call, 'Storages.'.$Call['Channel'].'.Log.Timestamps.Absolute'))
            foreach ($Call['Channel Logs'] as &$Row)
                $Row['T'] = date(DATE_W3C, floor(Started)).'(+'.$Row['T'].')';
            
        return $Call;
    });