<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        F::Log('Try to load *'.$Call['Test']['Name'].'*', LOG_INFO);
        $TestFilename = F::findFile('Tests/'.$Call['Test']['Name'].'.json');
        
        if ($TestFilename === null)
        {
            F::Log('Loading *'.$Call['Test']['Name'].'* failed', LOG_ERR);
            $Call = F::Hook('onTestNotFound', $Call);
        }
        else
        {
            F::Log('Loading *'.$Call['Test']['Name'].'* finished', LOG_INFO);
            $Test = jd(file_get_contents($TestFilename));
            
            $Call['Test']['Report'] = [];
            $Call['Test']['Name'] = str_replace('.json', '', $Call['Test']['Name']);
            $Call['Test']['Name'] = strtr($Call['Test']['Name'], '/', '.');
            
            if (isset($Test) && isset($Test['Suites']))
            {
                F::Log('Test *'.$Call['Test']['Name'].'* loaded', LOG_INFO);
    
                foreach ($Test['Suites'] as $SuiteName => $Suite)
                {
                    $VirtualCall = $Call;
                    
                    foreach ($Suite as $CaseName => $Call['Case'])
                    {
                        if (isset($Call['Case']['Apply']))
                            $VirtualCall = F::Live($Call['Case']['Apply'], $VirtualCall);
                       
                        if (isset($Call['Case']['Run']))
                        {
                            $Call['Case']['Run']['Call'] = F::Live($Call['Case']['Run']['Call']);
                            
                            $Call['Case']['Result']['Actual'] = F::Live($Call['Case']['Run'], $VirtualCall);
                            
                            if (isset($Call['Case']['Store Result']))
                                $VirtualCall[$Call['Case']['Store Result']] = $Call['Case']['Result']['Actual'];
                            
                            if (isset($Call['Case']['Result Key']))
                                $Call['Case']['Result']['Actual'] = F::Dot($Call['Case']['Result']['Actual'], $Call['Case']['Result Key']);
                            
                            foreach ($Call['Case']['Assert'] as $Assert => $Call['Checker'])
                            {
                                $TestTime = microtime(true); // FIXME
                                $Call['Decision'] = F::Run('Test.Assert.'.$Assert, 'Do', $Call);
                                $TestTime = microtime(true)-$TestTime;
                            }
                            
                            $Call['Test']['Report'][$Call['Test']['Name'].$SuiteName.$CaseName] = [
                                $Call['Test']['Name'],
                                $SuiteName,
                                $CaseName,
                                '<pre><code class="json hljs">'.(is_string($Call['Case']['Result']['Actual'])? $Call['Case']['Result']['Actual']: j($Call['Case']['Result']['Actual'])).'</code></pre>',
                                round($TestTime, 5)*1000,
                                '_Class' => $Call['Decision']? 'success' : 'danger'
                            ];
        
                            $Call['Decision']?
                                F::Log('Test case '.$CaseName.' passed', LOG_INFO):
                                F::Log('Test case '.$CaseName.' failed', LOG_NOTICE);
                        }
                    }
                }
            }
            else
                $Call = F::Hook('onTestNotFound', $Call);
        }
        
        return $Call;
    });

    setFn('Do', function ($Call)
    {
        self::$_Perfect = false;
        $Paths = F::getPaths();

        $Options = [];

        if (isset($Call['Test']['Name']))
        {
            F::Log('Test *'.$Call['Test']['Name'].'* selected', LOG_INFO);
            $VCall = F::Apply(null, 'Run', ['Test' => $Call['Test']['Name']], $Call);

            $Call['Test']['Report'] = F::Merge($Call['Test']['Report'], $VCall['Test']['Report']);
        }
        else
        {
            F::Log('Running all tests!', LOG_INFO);
            foreach ($Paths as $Path)
            {
                if (is_dir($Path.'/Tests'))
                {
                    $Directory = new RecursiveDirectoryIterator($Path.'/Tests');
                    $Iterator = new RecursiveIteratorIterator($Directory);
                    $Regex = new RegexIterator($Iterator,
                        '@Tests/(.+).json$@', RecursiveRegexIterator::GET_MATCH);

                    $Options = array_merge($Options, iterator_to_array($Regex));
                }
            }

            sort($Options);

            foreach ($Options as $Option)
            {
                $VCall = F::Apply(null, 'Run', $Call, ['Test' => ['Name' => $Option[1]]]);
                $Call['Test']['Report'] = F::Merge($Call['Test']['Report'], $VCall['Test']['Report']);
            }
        }

        $Call['Output']['Content'][] =
        [
            'Type' => 'Table',
            'Value' => $Call['Test']['Report']
        ];
        
        self::$_Perfect = true;
        return $Call;
    });