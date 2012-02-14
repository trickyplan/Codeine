<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

    self::setFn('Layouts', function ($Call)
    {
        $Slices = explode('.', $Call['Service']);
        $Slices[] = $Call['Method'];

        $sz = sizeof($Slices);

        for ($ic = 0; $ic<$sz; $ic++)
        {
            $ID = implode('/', array_slice($Slices, 0, $ic));

            if ($Sublayout = F::Run('Engine.Template', 'Load', array ('Scope' => 'Layout','ID'=> $ID)))
                $Call['Layout'] = str_replace('<place>Content</place>', $Sublayout, $Call['Layout']);
        }

        return $Call;
     });