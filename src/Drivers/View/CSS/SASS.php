<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Check', function ($Call)
    {
        list($Asset, $ID) = F::Run('View', 'Asset.Route',
            [
                'Value' => $Call['CSS Name']
            ]);

        $SASS = F::Run('IO', 'Execute',
            [
                'Execute' => 'Exist',
                'Storage' => 'SASS',
                'Scope'   => [$Asset, 'sass'],
                'Where'   => $ID
            ]);

        if ($SASS)
        {
            $SASSVersion = F::Run('IO', 'Execute',
            [
                'Execute' => 'Version',
                'Storage' => 'SASS',
                'Scope'   => [$Asset, 'sass'],
                'Where'   => $ID
            ]);

            $CSSVersion = F::Run('IO', 'Execute',
                [
                    'Execute' => 'Version',
                    'Storage' => 'CSS',
                    'Scope'   => [$Asset, 'css'],
                    'Where'   => $ID
                ]);

            if (($SASSVersion > $CSSVersion) or (isset($Call['HTTP']['Request']['Headers']['Pragma']) && $Call['HTTP']['Request']['Headers']['Pragma'] == 'no-cache'))
            {
                $Call = F::Dot($Call, 'View.CSS.SASS.Filename', Root.'/Assets/'.$Asset.'/sass/'.$ID.'.scss');
                $Call = F::Dot($Call, 'View.CSS.SASS.Command',
                    'sass --style '.F::Dot($Call, 'View.CSS.SASS.Style')
                    .' '
                    .F::Dot($Call, 'View.CSS.SASS.Filename').' > '.Root.'/Assets/'.$Asset.'/css/'.$ID.'.css');
                
                // FIXME! Temporary decision.
                if (file_exists(F::Dot($Call, 'View.CSS.SASS.Filename')))
                {
                    $Exec = F::Run('Code.Run.External.Exec', 'Run', $Call,
                        [
                            'Command' => F::Dot($Call, 'View.CSS.SASS.Command')
                        ]);
                    
                    if ($Exec['Code'] == 0)
                        F::Log('SASS *processed* '.F::Dot($Call, 'View.CSS.SASS.Filename'), LOG_INFO, 'Developer');
                    else
                        F::Log('SASS *failed* '.F::Dot($Call, 'View.CSS.SASS.Filename'), LOG_WARNING, 'Developer');
                }
            }
            else
                F::Log('SASS *skipped* '.F::Dot($Call, 'View.CSS.SASS.Filename'), LOG_INFO, 'Developer');
        }

        return $Call;
    });