<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Ported Formalin
     * @package Codeine
     * @subpackage Drivers
     * @version 0
     * @date 16.11.10
     * @time 4:01
     */

    self::Fn('Make', function ($Call)
    {
        $Output = array();

        $FormLayout = Data::Read('Layout::UI/Codeine/Form/Form');

        foreach($Call['Item']['Model']['Nodes'] as $Title => $Node)
        {
            $Value = isset ($Call['Item']['Data'][$Title])? $Call['Item']['Data'][$Title]: '';
            
            $Output[$Title] = Code::Run(
                array(
                   'N' => 'View.UI.Codeine.'.$Node['Controls'][$Call['Item']['Purpose']],
                   'F' => 'Make',
                   'Name' => $Title,
                   'Value' => $Value,
                   'Label' => 'Model.'.$Call['Item']['Entity'].'.'.$Title.'.Label',
                   'ID' => 'Form_'.$Call['Item']['Purpose'].
                           '_'.$Node['Controls'][$Call['Item']['Purpose']].'_'.$Title
                ));
        }

        $Output['Submit'] = Code::Run(
                array(
                   'N' => 'View.UI.Codeine.Submit',
                   'F' => 'Make',
                   'Name' => ''
                ));

        return str_replace('<content/>',implode('',$Output),$FormLayout);
    });
