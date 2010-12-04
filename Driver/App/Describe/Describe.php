<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Describer
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 04.12.10
     * @time 17:46
     */

    self::Fn('Describe', function ($Call)
    {
        $Call['Entity'] = str_replace('>', '/', $Call['Entity']);
        $Contract = Data::Read(
                array(
                    'Point' => 'Contract',
                    'Where' =>
                        array(
                            'ID'=>$Call['Entity'])));

        $Contract = $Contract[$Call['ID']];

        $Call['Items']['Header'] =
                array('UI'=>'Heading', 'Level'=>2, 'Data' => $Call['Entity'].'::'.$Call['ID']);

        $Call['Items']['Description'] =
                array('UI'=>'Block', 'Data' => $Contract['Description']);

        $Call['Items']['Driver'] = array('UI'=>'Heading', 'Level'=>4, 'Data' => '<l>Driver Policy</l>');

        if (isset($Contract['Driver']))
            foreach ($Contract['Driver'] as $Role => $Driver)
                $Call['Items']['Driver'.$Role] = array('UI'=>'Block', 'Level'=>5,
                             'Data' => '<l>Driver/Role/'.$Role.'</l>: '.$Driver);

        $Call['Items']['Arguments'] = array('UI'=>'Heading', 'Level'=>4, 'Data' => '<l>Arguments</l>');

        if (isset ($Contract['Arguments']))
        foreach ($Contract['Arguments'] as $Name => $Argument)
        {
            $Call['Items'][$Name.'_Header'] = array('UI'=>'Heading', 'Level'=>5, 'Data' => $Name);

            if (isset($Argument['Description']) && $Argument['Description'])
                $Call['Items'][$Name.'_Description']
                        = array('UI'=>'Block', 'Data' => $Argument['Description']);

            if (isset($Argument['Type']) && $Argument['Type'])
                $Call['Items'][$Name.'_Type'] =
                        array('UI'=>'Badge', 'Data' => '<l>Data/Type/'.$Argument['Type'].'</l>');

            if (isset($Argument['Required']) && $Argument['Required'])
                $Call['Items'][$Name.'_Required'] = array('UI'=>'Badge', 'Data' => '<l>Required</l>');

        }

        $Call['Items']['Return'] = array('UI'=>'Heading', 'Level'=>4, 'Data' => '<l>Return</l>');

        return $Call;
    });