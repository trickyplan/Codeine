<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Test = json_decode(file_get_contents(F::findFile($Call['Test'])), true);

        $Call['Output']['Content'][] =
                [
                    'Type' => 'Heading',
                    'Level' => 2,
                    'Value' => strtr($Call['Test'], '/', '.')
                ];

        if (isset($Test))
            foreach ($Test['Suites'] as $SuiteName => $Suite)
            {
                $Call['Output']['Content'][] =
                [
                    'Type' => 'Heading',
                    'Level' => 3,
                    'Value' => 'Test suite: '.$SuiteName
                ];

                foreach ($Suite as $CaseName => $Case)
                {
                    $Call['Output']['Content'][] =
                    [
                        'Type' => 'Heading',
                        'Level' => 4,
                        'Value' => 'Test case: '.$CaseName
                    ];

                    $Result = F::Live($Case['Run'], $Call);

                    if (isset($Result['Output']['Content']))
                        $Result = print_r($Result['Output']['Content'], true);

                    if ($Result == $Case['Result']['Equal'])
                        $Call['Output']['Content'][] =
                        [
                            'Type' =>  'Block',
                            'Class' => 'alert alert-success',
                            'Value' => 'Test passed'
                        ];
                    else
                    {
                        $Call['Failure'] = true;

                        $Call['Output']['Content'][] =
                        [
                            'Type' =>  'Block',
                            'Class' => 'alert alert-danger',
                            'Value' => 'Test failed'
                        ];

                        $Call['Output']['Content'][] =
                        [
                            'Type' =>  'Block',
                            'Class' => 'alert alert-warning',
                            'Value' => 'Expectation: '.$Case['Result']['Equal']
                        ];

                        $Call['Output']['Content'][] =
                        [
                            'Type' =>  'Block',
                            'Class' => 'alert alert-warning',
                            'Value' => 'Reality: '.$Result
                        ];
                    }
                }
            }
        else
            $Call = F::Hook('NotFound', $Call);

        return $Call;
    });

    setFn('All', function ($Call)
    {
        $Paths = F::getPaths();

        $Options = [];

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

        foreach ($Options as $Option)
            $Call = F::Apply(null, 'Do', $Call, ['Test' => $Option[0]]);

        return $Call;
    });