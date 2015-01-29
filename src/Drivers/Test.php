<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        $Test = jd(file_get_contents(F::findFile('Tests/'.$Call['Test'].'.json')), true);

        $Call['Test'] = str_replace('.json', '', $Call['Test']);
        $Call['Test'] = strtr($Call['Test'], '/', '.');

        if (isset($Test) && isset($Test['Suites']))
            foreach ($Test['Suites'] as $SuiteName => $Suite)
            {
                foreach ($Suite as $CaseName => $Call['Case'])
                {
                    $Call['Return'] = F::Live($Call['Case']['Run'], $Call);

                    if (isset($Call['Return']['Output']['Content']))
                        $Call['Return'] = print_r($Call['Return']['Output']['Content'], true);

                    foreach ($Call['Case']['Result'] as $Assert => $Call['Checker'])
                    {
                        $TestTime = microtime(true); // FIXME
                            $Call['Return'] = F::Run('Test.Assert.'.$Assert, 'Do', $Call);
                        $TestTime = microtime(true)-$TestTime;
                    }

                    $Call['Report'][] = [
                        $Call['Test'],
                        $SuiteName,
                        $CaseName,
                        round($TestTime, 5)*1000,
                        '_Class' => $Call['Return'][0]? 'success' : 'danger'
                    ];

                    $Call['Return'][0]?
                        F::Log('Test case '.$CaseName.' passed', LOG_INFO):
                        F::Log('Test case '.$CaseName.' failed', LOG_ERR);

                }
            }
        else
            $Call = F::Hook('NotFound', $Call);

        return $Call;
    });

    setFn('Do', function ($Call)
    {
        $Paths = F::getPaths();

        $Options = [];
        $Call['Report'] = [];

        if (isset($Call['Test']))
        {
            $VCall = F::Apply(null, 'Run', ['Test' => $Call['Test']]);

            $Call['Output']['Content'][] =
            [
                'Type' => 'Table',
                'Value' => $VCall['Report']
            ];
        }
        else
        {
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
                $VCall = F::Apply(null, 'Run', $Call, ['Test' => $Option[1]]);

                $Call['Output']['Content'][] =
                [
                    'Type' => 'Table',
                    'Value' => $VCall['Report']
                ];
            }
        }

        return $Call;
    });