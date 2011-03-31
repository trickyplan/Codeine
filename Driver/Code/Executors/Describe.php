<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Describe Function
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 04.12.10
     * @time 17:28
     */

    self::Fn('Run', function ($Call)
    {
        $Call = Code::LoadContract($Call['Call']);
        $Contract = $Call['Contract'];

        $Call['Items']['Header'] =
                array('UI'=>'Heading', 'Level'=>2, 'Data' => strtr($Call['N'], '/','.').' '.$Call['F'].'()');

        if (isset($Contract['Description']))
            $Call['Items']['Description'] =
                    array('UI'=>'Block', 'Data' => $Contract['Description']['ru_RU.UTF-8']);

        if (isset($Contract['Driver']))
        {
            $Call['Items']['Driver'] = array('UI'=>'Heading', 'Level'=>3, 'Data' => '<l>Driver Policy</l>');
            
            foreach ($Contract['Driver'] as $Role => $Driver)
                $Call['Items']['Driver'.$Role] = array('UI'=>'Block', 'Level'=>4,
                             'Data' => '<l>Driver.Role.'.$Role.'</l>: '.$Driver);
        }

        $Call['Items']['Arguments'] = array('UI'=>'Heading', 'Level'=>3, 'Data' => '<l>Arguments</l>');

        if (isset ($Contract['Arguments']))
            foreach ($Contract['Arguments'] as $Name => $Argument)
            {
                $Call['Items'][$Name.'_Header'] = array('UI'=>'Heading', 'Level'=>4, 'Data' => $Name);

                if (isset($Argument['Description']['ru_RU.UTF-8']) && $Argument['Description']['ru_RU.UTF-8'])
                    $Call['Items'][$Name.'_Description']
                            = array('UI'=>'Block', 'Data' => $Argument['Description']['ru_RU.UTF-8']);

                if (isset($Argument['Type']) && $Argument['Type'])
                    $Call['Items'][$Name.'_Type'] =
                            array('UI'=>'Badge', 'Value' => '<l>Data.Type.Self</l>: <l>Data.Type.'.$Argument['Type'].'</l> ');

                if (isset($Argument['Required']) && $Argument['Required'])
                    $Call['Items'][$Name.'_Required'] =
                            array('UI'=>'Badge', 'Class' => array('Required'),'Value' => '<l>Required</l>');
                else
                    $Call['Items'][$Name.'_Optional'] =
                            array('UI'=>'Badge', 'Class' => array('Optional'), 'Value' => '<l>Optional</l>');

            }

        $Call['Items']['Return'] = array('UI'=>'Heading', 'Level'=>3, 'Data' => '<l>Return</l>');

        if (isset($Contract['Return']) && isset($Contract['Return']['Type']))
                    $Call['Items']['Return_Type'] =
                            array('UI'=>'Badge', 'Value' => '<l>Data.Type.Self</l>: <l>Data.Type.'.$Contract['Return']['Type'].'</l>');
        
        return $Call;
    });
