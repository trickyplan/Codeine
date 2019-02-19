<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 12.x
     */
    
    setFn('Run.Test', function ($Call)
    {
        self::$_Perfect = false;
        $Call = F::Hook('Test.Run.Before', $Call);
        
            F::Log('Try to load *'.$Call['ID'].'*', LOG_INFO);
            
            $Call['Test'] = F::Run('IO', 'Read', $Call,
                [
                    'Storage'   => 'Tests',
                    'Where'     => $Call['ID'],
                    'IO One'    => true
                ]);

            if (isset($Call['Test']['Mixins']) && is_array($Call['Test']['Mixins']))
            {
                foreach($Call['Test']['Mixins'] as $Mixin)
                {
                    $Call['Test'] = F::Merge(F::loadOptions($Mixin), $Call['Test']);
                }
                unset($Call['Test']['Mixins']);
            }

            if (isset($Call['Test']['Suites']))
                foreach($Call['Test']['Suites'] as
                        $Call['Test']['Suite']['Name'] => $Call['Test']['Suite']['Cases'])
                    $Call = F::Apply(null, 'Run.Suite', $Call);
            
            if (isset($Call['Test']['Results']))
                foreach ($Call['Test']['Results'] as $TestResult)
                    $Call['Output']['Content'][$TestResult['Name']] =
                        [
                            'Type'  => 'Template',
                            'Scope' => 'Test',
                            'ID'    => 'Case',
                            'Data'  => $TestResult
                        ];

        $Call = F::Hook('Test.Run.After', $Call);
        
        return $Call;
    });
    
    setFn('Run.Suite', function ($Call)
    {
        $Call = F::Hook('Test.Suite.Run.Before', $Call);
        
            $Call['Virtual'] = $Call;

            foreach ($Call['Test']['Suite']['Cases'] as
                     $Call['Test']['Case']['Name'] => $Call['Test']['Case'])
                $Call = F::Apply(null, 'Run.Case', $Call);
        
        $Call = F::Hook('Test.Suite.Run.After', $Call);
        return $Call;
    });
    
    setFn('Run.Case', function ($Call)
    {
        $Call = F::Hook('Test.Case.Run.Before', $Call);
            
            if (mb_substr($Call['Test']['Case']['Name'], 0, 1) == '-')
                return $Call;
        
            if (isset($Call['Test']['Case']['Apply']))
                $Call['Virtual'] = F::Live($Call['Test']['Case']['Apply'], $Call['Virtual']);
            
            // Run
            $Call = F::Hook('Test.Case.Run.Execute.Before', $Call);
            
                F::Start($Call['Test']['Case']['Name']);

                $Call = F::Dot($Call,
                    'Test.Case.Result.Actual',
                    F::Live(F::Dot($Call, 'Test.Case.Run'), $Call['Virtual']));
            
                F::Stop($Call['Test']['Case']['Name']);
                $Call = F::Dot($Call, 'Test.Case.Time.Run',
                    F::Time($Call['Test']['Case']['Name']));
                
            $Call = F::Hook('Test.Case.Run.Execute.After', $Call);
            
            // Assert
            $Status = 'Passed';
            
            if (isset($Call['Test']['Case']['Assert']))
                foreach ($Call['Test']['Case']['Assert'] as $Assert => $Expected)
                {
                    $Call = F::Dot($Call, 'Test.Case.Result.'.$Assert.'.Expected', $Expected);
                    F::Start($Call['Test']['Case']['Name'].$Assert);
                    
                    $Decision = F::Run('Test.Assert.'.$Assert, 'Do', $Call);
                    $Call = F::Dot ($Call, 'Test.Case.Assert.'.$Assert.'.Decision',$Decision);
                    
                    if ($Decision == false)
                        $Status = 'Failed';
                    
                    F::Stop($Call['Test']['Case']['Name'].$Assert);
                    
                    $Call = F::Dot($Call, 'Test.Case.Time.'.$Assert,
                        F::Time($Call['Test']['Case']['Name'].$Assert));
                }
        
        $Call['Test']['Case']['Status'] = $Status;
        $Call = F::Hook('Test.Case.Run.After', $Call);
        
        if (is_scalar(F::Dot($Call, 'Test.Case.Result.Actual')))
            ;
        else
            $Call = F::Dot($Call, 'Test.Case.Result.Actual',
                j(F::Dot($Call, 'Test.Case.Result.Actual')));
        
        $Call['Test']['Case']['ID'] = $Call['ID'];
        $Call['Test']['Case']['CID'] = uniqid();
        $Call['Test']['Case']['Suite'] = $Call['Test']['Suite']['Name'];
        $Call['Test']['Results'][$Call['Test']['Case']['Name']] = $Call['Test']['Case'];
        return $Call;
    });
    
    setFn('Run.All', function ($Call)
    {
        $Tests = F::Run(null, 'Enumerate', $Call);
        
        foreach ($Tests as $Call['ID'])
            $Call = F::Apply(null, 'Run.Test', $Call);
        
        return $Call;
    });
    
    setFn('Enumerate', function ($Call)
    {
        $Options = [];
        $Paths = F::getPaths();
        
        foreach ($Paths as $Path)
        {
            if (is_dir($Path.'/Tests'))
            {
                $Directory = new RecursiveDirectoryIterator($Path.'/Tests');
                $Iterator = new RecursiveIteratorIterator($Directory);
                $Regex = new RegexIterator($Iterator,
                    '@Tests/(.+).json$@', RecursiveRegexIterator::GET_MATCH);
                
                $Found = iterator_to_array($Regex);
                $Options = array_merge($Options, array_column($Found, 1));
            }
        }

        sort($Options);
        
        return $Options;
    });