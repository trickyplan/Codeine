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

        $Header = '<form action="" method="post">';
        $Footer = '</form>';

        foreach($Call['Item']['Data']['Nodes'] as $Title => $Node)
        {
            $Output[$Title] = Code::Run(
                array(
                   'N' => 'View.UI.Codeine.'.$Node['Controls'][$Call['Item']['Purpose']],
                   'F' => 'Make',
                   'Name' => $Title,
                   'Label' => 'Model.'.$Call['Item']['Entity'].'.'.$Title.'.Label',
                   'ID' => 'Form_'.$Call['Item']['Purpose'].
                           '_'.$Node['Controls'][$Call['Item']['Purpose']].'_'.$Title
                ));
        }

        $Output['Submit'] = Code::Run(
                array(
                   'N' => 'View.UI.Codeine.Submit',
                   'F' => 'Make'
                ));

        return $Header.implode('',$Output).$Footer;
    });
