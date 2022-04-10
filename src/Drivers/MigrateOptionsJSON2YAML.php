<?php

    setFn('Do', function ($Call) {
        $Options = [];
        $Directory = new RecursiveDirectoryIterator(Root . '/src/Options');
        $Iterator = new RecursiveIteratorIterator($Directory);
        $Regex = new RegexIterator(
            $Iterator,
            '@src/Options/(.+).json$@',
            RecursiveRegexIterator::GET_MATCH
        );

        $Found = iterator_to_array($Regex);
        $Options = array_merge($Options, array_column($Found, 1));

        foreach ($Options as $OptionsFilename) {
            $YAML = F::Run(
                'Format',
                'Convert',
                [
                    'Format' =>
                        [
                            'Input' => 'JSON',
                            'Output' => 'YAML'
                        ],
                    'Value' => file_get_contents(Root . '/src/Options/' . $OptionsFilename . '.json')
                ]
            );

            file_put_contents(Root . '/src/Options/' . $OptionsFilename . '.yaml', $YAML);
        }

        return $Call;
    });
