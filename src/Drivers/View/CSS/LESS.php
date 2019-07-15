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
            ])
        ;

        $LESS = F::Run('IO', 'Execute',
            [
                'Execute' => 'Exist',
                'Storage' => 'LESS',
                'Scope'   => [$Asset, 'less'],
                'Where'   => $ID
            ]);

        if ($LESS === null)
            F::Log('LESS *not found* '.Root.'/Assets/'.$Asset.'/less/'.$ID.'.less', LOG_NOTICE, 'Developer');
        else
        {
            $LESSVersion = F::Run('IO', 'Execute',
            [
                'Execute' => 'Version',
                'Storage' => 'LESS',
                'Scope'   => [$Asset, 'less'],
                'Where'   => $ID
            ]);
            
            $CSSVersion = F::Run('IO', 'Execute',
                [
                    'Execute' => 'Version',
                    'Storage' => 'CSS',
                    'Scope'   => [$Asset, 'css'],
                    'Where'   => $ID
                ]);

            if ($LESSVersion > $CSSVersion or (isset($Call['HTTP']['Request']['Headers']['Pragma']) && $Call['HTTP']['Request']['Headers']['Pragma'] == 'no-cache'))
            {
                $Call = F::Dot($Call, 'View.CSS.LESS.Filename', F::findFile('Assets/'.$Asset.'/less/'.$ID.'.less'));
                
                if (F::Dot($Call, 'View.CSS.LESS.Filename'))
                {
                    $Exec = F::Run('Code.Run.External.Exec', 'Run', $Call,
                        [
                            'Command' => F::Dot($Call, 'View.CSS.LESS.Command').' "'.F::Dot($Call, 'View.CSS.LESS.Filename').'"'
                        ]);
                    
                    if ($Exec['Code'] == 0)
                    {
                        $Call['CSS']['Styles'][$Call['CSS Name']] = $Exec['Result'];
                        F::Log('LESS *processed* '.F::Dot($Call, 'View.CSS.LESS.Filename'), LOG_INFO);
                    }
                    else
                    {
                        $Call['CSS']['Styles'][$Call['CSS Name']] = '';
                        F::Log('LESS *failed* '.F::Dot($Call, 'View.CSS.LESS.Filename'), LOG_WARNING);
                    }
                    
                    F::Run('IO', 'Write',
                    [
                        'Storage'   => 'CSS',
                        'Scope'     => [$Asset, 'css'],
                        'Where'     => $ID,
                        'Data'      => $Call['CSS']['Styles'][$Call['CSS Name']]
                    ]);
                }
            }
            else
                F::Log('LESS *skipped* '.Root.'/Assets/'.$Asset.'/css/'.$ID.'.css', LOG_INFO, 'Developer');
        }

        return $Call;
    });