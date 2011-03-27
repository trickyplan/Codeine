<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 15.03.11
     * @time 2:30
     */

    self::Fn('Route', function ($Call)
    {
        if (is_string($Call['Call']) && strpos($Call['Call'],'/') !== null)
        {
            $Routed = array();
            
            if (mb_strpos($Call['Call'], '.') !== false)
            {
                // FIXME Extension Detect
                list($Call['Call'], $Routed['Format']) = explode('.', $Call['Call']);
            }
            
            $Actions = Code::Run(
                            array(
                                'N' => 'Code.Source.Enumerate',
                                'F' => 'Drivers',
                                'Namespace' => 'Entity',
                                'onlyNames' => true
                            )
                        );

            $Pieces = explode('/',$Call['Call']);
            $sizeofPieces = count($Pieces);

            if ($sizeofPieces > 0)
                $Action = $Pieces[0];

            if (in_array($Action, $Actions))
            {
                $Routed['N'] = 'Entity.'.$Action;
                $Routed['F'] = $Action;

                if ($sizeofPieces > 1)
                    $Routed['Entity'] = $Pieces[1];

                if ($sizeofPieces > 2)
                    $Routed['ID'] = $Pieces[2];

                if ($sizeofPieces > 3)
                    $Routed['Mode'] = $Pieces[3];
            }
            else
                return null;

            return $Routed;
        }
        else
            return null;
    });
