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
                   'F' => 'View/UI/Codeine/'.$Node['Controls'][$Call['Item']['Purpose']].'::Make',
                   'Name' => $Title,
                   'ID' => 'Form_'.$Call['Item']['Purpose'].
                           '_'.$Node['Controls'][$Call['Item']['Purpose']].'_'.$Title
                ));
        }

        $Output['Submit'] = Code::Run(
                array(
                   'F' => 'View/UI/Codeine/Submit::Make'
                ));

        return $Header.implode('',$Output).$Footer;
    });