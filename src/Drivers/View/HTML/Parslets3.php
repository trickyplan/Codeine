<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (isset($Call['Output']))
        {
            F::Log('*Start* Parslets3 processing', LOG_INFO);
            
            $Parslets = $Call['View']['HTML']['Parslets']['Enabled'];

            $TotalFound = 0;
            $Pass = 0;
            do
            {
                $Pass++;
                $Matched = [];
                $PassFound = 0;
                F::Log('Start Pass *№'.$Pass.'*', LOG_INFO);
                
                foreach ($Parslets as $Parslet)
                {
                    $Tag = strtolower($Parslet);
                    $Pattern = '<('.$Tag.')(\d*)(.*?)>(.*?)</(\1)(\2)>';
    
                    $Parsed = F::Run('Text.Regex', 'All',
                        [
                            'Pattern' => $Pattern,
                            'Value'   => $Call['Output']
                        ]);

                    if ($Parsed === false)
                        $ParsletFound = 0;
                    else
                    {
                        $ParsletFound = count($Parsed);
                        $PassFound += $ParsletFound;
                    }
                    
                    F::Log('Found *'.$ParsletFound.'* of '.$Parslet, LOG_INFO);
                    
                    if (empty($Parsed))
                        ;
                    else
                    {
                        $Matched[$Parslet] = [];
        
                        foreach ($Parsed[1] as $IX => $Tag)
                            if ($Tag == strtolower($Parslet))
                            {
                                $Attributes = [];
                                $Root = simplexml_load_string('<root '.$Parsed[3][$IX].'></root>');
                                
                                if ($Root)
                                {
                                    $Attributes = (array) $Root->attributes();
                                
                                    if (isset($Attributes['@attributes']))
                                        $Attributes = $Attributes['@attributes'];
                                }
                                
                                $Matched[$Parslet]['Match'][] = $Parsed[0][$IX];
                                $Matched[$Parslet]['Options'][] = $Attributes;
                                $Matched[$Parslet]['Value'][] = $Parsed[4][$IX];//.$Parsed[4][$IX];
                            }
                        
                        if (empty($Matched[$Parslet]))
                            ;
                        else
                        {
                            $Matched[$Parslet]['Replace'] = F::Apply('View.HTML.Parslets.' . $Parslet, 'Parse', $Call,
                                [
                                    'Parsed!' => $Matched[$Parslet]
                                ]);
                            
                            if (false && F::Environment() == 'Development')
                                foreach ($Matched[$Parslet]['Replace'] as $IX => $Replace)
                                    $Matched[$Parslet]['Replace'][$IX] =
                                        '<!--['
                                        .$Parslet
                                        .j($Matched[$Parslet]['Options'][$IX])
                                        .']-->'
                                        .PHP_EOL
                                        .$Matched[$Parslet]['Replace'][$IX]
                                        .PHP_EOL
                                        .'<!--[/'
                                        .$Parslet.j($Matched[$Parslet]['Options'][$IX]).']-->';
                        }
                    }
                }

                foreach ($Matched as $Parslet => $cMatched)
                    if (isset($cMatched['Match']))
                        $Call['Output'] = str_replace($cMatched['Match'], $cMatched['Replace'], $Call['Output']);
                
                F::Log('*'.$PassFound.'* parslets found on pass №'.$Pass, LOG_INFO);
                $TotalFound += $PassFound;
            }
            while ($PassFound > 0);
            
            F::Log('Total *'.$PassFound.'* found', LOG_INFO);
        }
        
        
        return $Call;
    });