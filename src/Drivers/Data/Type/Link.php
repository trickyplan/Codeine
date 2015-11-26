<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Read', function ($Call)
    {
        if (
            isset($Call['Data'][$Call['Node']['Link']['Type']])
            &&
            isset($Call['Data'][$Call['Node']['Link']['Object']])
        )
            return F::Run('Entity', 'Read',
                [
                    'Entity'    => $Call['Data'][$Call['Node']['Link']['Type']],
                    'Where'     => $Call['Data'][$Call['Node']['Link']['Object']],
                    'One'       => true
                ]);
        else
            return null;
    });

    setFn('Write', function ($Call)
    {
        return null;
    });