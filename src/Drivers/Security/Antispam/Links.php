<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Calculate', function ($Call)
    {
        if (preg_match_all('/href=\=/', $Call['Value'], $Pockets))
            return count($Pockets)*$Call['Antispam']['Link']['Weight'];
        else
            return 0;
    });