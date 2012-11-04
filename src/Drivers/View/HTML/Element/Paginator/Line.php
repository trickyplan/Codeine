<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        $Output = '';
        if ($Call['Page']> 1)
            $Output.= F::Run('View', 'LoadParsed',
                array('Scope' => 'Default',
                      'ID' => 'UI/Paginator/Prev',
                      'Data' =>
                        array(
                            'Num' => $Call['Page']-1,
                            'URL' => $Call['PageURL'],
                            'PageURLPostfix' => $Call['PageURLPostfix'],
                        )));

        for ($ic = 1; $ic <= $Call['PageCount']; $ic++)
            $Output.= F::Run('View', 'LoadParsed',
                array('Scope' => 'Default',
                      'ID' => ($ic == $Call['Page']? 'UI/Paginator/Current': 'UI/Paginator/Page'),
                      'Data' =>
                        array(
                            'Num' => $ic,
                            'URL' => $Call['PageURL'],
                            'PageURLPostfix' => $Call['PageURLPostfix'])));

        if ($Call['Page']< $Call['PageCount'])
            $Output.= F::Run('View', 'LoadParsed',
                array('Scope' => 'Default',
                      'ID' => 'UI/Paginator/Next',
                      'Data' =>
                        array(
                            'Num' => $Call['Page']+1,
                            'URL' => $Call['PageURL'],
                            'PageURLPostfix' => $Call['PageURLPostfix'])));

        return F::Run('View', 'LoadParsed', array('Scope' => 'Default', 'ID' => 'UI/Paginator', 'Data' => array($Call, 'Pages' => $Output)));
     });