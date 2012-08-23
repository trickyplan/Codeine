<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Make', function ($Call)
    {
        $Output = '';
        for ($ic = $Call['Page']-7; $ic <= $Call['Page']+7; $ic++)
            if ($ic>0 && $ic<=$Call['PageCount'])
                $Output.= F::Run('View', 'LoadParsed',
                array('Scope' => 'Default',
                      'ID' => ($ic == $Call['Page']? 'UI/HTML/Paginator/Current': 'UI/HTML/Paginator/Page'),
                      'Data' =>
                        array(
                            'Num' => $ic,
                            'URL' => $Call['PageURL'],
                            'PageURLPostfix' => $Call['PageURLPostfix'])));

        return F::Run('View', 'LoadParsed', array('Scope' => 'Default', 'ID' => 'UI/HTML/Paginator', 'Data' => array($Call, 'Pages' => $Output)));
     });