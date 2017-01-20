<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Apriori Parser 
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Output']))
        {
            F::Log('*Start* parslets processing', LOG_INFO);
            $MaxPass = $Call['View']['HTML']['Parslets']['Max Passes'];
            $Queue = $Call['View']['HTML']['Parslets']['Queue'];
    
            for ($Step = 1; $Step <= 3; $Step++)
            {
                $Patterns = [];
                $Tags = [];
                $StepPostfix = ($Step > 1 ? $Step: '');
                
                foreach ($Queue as $Parslet)
                    $Tags[strtolower($Parslet)] = strtolower($Parslet).$StepPostfix;
                
                $Tags = implode('|', $Tags);
                $Patterns[] = '<('.$Tags.') (.*?)>(.*?)</(\1)>';
                $Patterns[] = '<('.$Tags.')()>(.*?)</(\1)>';
                
                do
                {
                    $Pass = 0;
                    $Found = 0;
                    
                    foreach ($Patterns as $Pattern)
                    {
                        $Parsed = F::Run('Text.Regex', 'All',
                            [
                                'Pattern' => $Pattern,
                                'Value'   => $Call['Output']
                            ]);
                        
                        if ($Parsed === false)
                            ;
                        else
                        {
                            $Found += count($Parsed[0]);
                            $Matched = [];
                            
                            foreach ($Queue as $Parslet)
                            {
                                $Matched[$Parslet] = [];
                                
                                foreach ($Parsed[1] as $IX => $Tag)
                                    if ($Tag == strtolower($Parslet).$StepPostfix)
                                    {
                                        $Matched[$Parslet]['Match'][] = $Parsed[0][$IX];
                                        $Matched[$Parslet]['Options'][] = $Parsed[2][$IX];
                                        $Matched[$Parslet]['Value'][] = $Parsed[3][$IX];
                                    }
                                    
                                if (empty($Matched[$Parslet]))
                                    ;
                                else
                                {
                                    $Call = F::Apply('View.HTML.Parslets.' . $Parslet, 'Parse', $Call,
                                        [
                                            'Parsed!' => $Matched[$Parslet]
                                        ]);
                                    
                                    F::Log('Parslet *' . $Parslet . '* processed', LOG_DEBUG);
                                }
                            }
                        }
                        F::Log('*' . $Found. '* parslet used', LOG_DEBUG);
                    }
                    $Pass++;
                }
                while ($Found > 0 and $Pass < $MaxPass);
            }
        }
        
        F::Log('*'.$Pass.'* passes proceeded', LOG_INFO);
        F::Log('*End* parslets processing', LOG_INFO);
        return $Call;
    });