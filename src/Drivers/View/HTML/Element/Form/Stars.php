<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        $Stars = '';
        for ($IC = 1; $IC <= $Call['Stars']; $IC++)
        {
            $StarData = array('Num' => $IC);

            if (isset($Call['Value']) && $Call['Value'] == $IC)
                $StarData['Checked'] = true;

            $Stars.=  F::Run('View', 'LoadParsed', array('Scope' => 'Default', 'ID' => 'UI/Form/Star', 'Data' => F::Merge($Call, $StarData)));
        }

        return F::Run('View', 'LoadParsed', array('Scope' => 'Default', 'ID' => 'UI/Form/Stars', 'Data' => F::Merge($Call,array('Stars' => $Stars))));
     });