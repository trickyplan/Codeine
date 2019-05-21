<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2019.x
     */

    setFn('Read', function ($Call)
    {
        return $Call['Data'];
    });

    setFn('Write', function ($Call)
    {
        return $Call['Data'];
    });