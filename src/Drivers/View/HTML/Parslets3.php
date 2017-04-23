<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('afterViewLoad', function ($Call)
    {
        if (isset($Call['Value']))
        {
            $Call = F::Apply(null, 'Process', $Call,
                [
                    'Parslets' =>
                        [
                            'Queue'  => $Call['View']['HTML']['Parslets']['afterViewLoad']['Enabled'],
                            'Source' => $Call['Value']
                        ]
                ]);
            
            $Call['Value'] = $Call['Parslets']['Source'];
            
            unset($Call['Parslets']);
        }
        
        return $Call;
    });
    
    setFn('afterHTMLRender', function ($Call)
    {
        if (isset($Call['Output']))
        {
            $Call = F::Apply(null, 'Process', $Call,
                [
                    'Parslets' =>
                        [
                            'Queue'  => $Call['View']['HTML']['Parslets']['afterHTMLRender']['Enabled'],
                            'Source' => $Call['Output']
                        ]
                ]);
            
            $Call['Output'] = $Call['Parslets']['Source'];
        }
        
        return $Call;
    });
    
    setFn('Process', function ($Call)
    {
        F::Log('*Start* Parslets3 processing', LOG_DEBUG);
            
        $TotalFound = 0;
        $Pass = 0;
        do
        {
            $Pass++;
            $Matched = [];
            $PassFound = 0;
            F::Log('Start Pass *№'.$Pass.'*', LOG_DEBUG);
            
            foreach ($Call['Parslets']['Queue'] as $Parslet)
            {
                $Tag = strtolower($Parslet);
                $Pattern = '<('.$Tag.')(\d*)(.*?)>(.*?)</(\1)(\2)>';

                $Parsed = F::Run('Text.Regex', 'All',
                    [
                        'Pattern' => $Pattern,
                        'Value'   => $Call['Parslets']['Source']
                    ]);

                if ($Parsed === false)
                    $ParsletFound = 0;
                else
                {
                    $ParsletFound = count($Parsed);
                    $PassFound += $ParsletFound;
                }
                
                if ($ParsletFound > 0)
                    F::Log('Found *'.$ParsletFound.'* of '.$Parslet, LOG_DEBUG);
    
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
                            $Matched[$Parslet]['Value'][] = $Parsed[4][$IX];
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
                {
                    $Count = 0;
                    $Call['Parslets']['Source'] = str_replace($cMatched['Match'], $cMatched['Replace'], $Call['Parslets']['Source'], $Count);
                    F::Log('Parslet '.$Parslet.', *'.count($cMatched['Match']).'* matched , *'.count($cMatched['Replace']).'* replaces prepared, *'.$Count.'* replaced', LOG_INFO);
                }
            
            if ($PassFound > 0)
                F::Log('*'.$PassFound.'* parslets found on pass №'.$Pass, LOG_DEBUG);
            
            $TotalFound += $PassFound;
        }
        while ($PassFound > 0);
        
        F::Log('Total *'.$PassFound.'* parslets found', LOG_DEBUG);
            
        return $Call;
    });