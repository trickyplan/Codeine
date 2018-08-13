<?php

setFn('Tests List', function ($Call) {
    $Paths = F::getPaths();
    $JSONs = [];
    foreach ($Paths as $Path) {
        $JSONs = array_merge($JSONs, F::Run(null, 'Glob JSONs', F::Dot($Call, 'Test.List.BasePath', $Path.'/Tests')));
    }

    return F::Dot($Call, 'Output.Content', $JSONs);
});


setFn('Glob JSONs', function ($Call) {
    $Path = F::Dot($Call, 'Test.List.Path');
    if (empty($Path)) {
        $Path = F::Dot($Call, 'Test.List.BasePath');
        $Call = F::Dot($Call, 'Test.List.Path', $Path);
    }

    $Files = glob($Path.'/*');
    $JSONs = [];
    foreach ($Files as $File) {
        if (is_dir($File)) {
            $Filename = basename($File);
            if ($Filename[0] == '.' || $Filename[0] == '-') {
                continue;
            }

            $JSONs = array_merge($JSONs, F::Run(null, 'Glob JSONs', F::Dot($Call, 'Test.List.Path', $File)));
        } else {
            $JSONs[] = str_replace([
                F::Dot($Call, 'Test.List.BasePath'),
                '.json'
            ], [
                F::Live(F::Dot($Call, 'Test.List.BaseURL'), $Call),
                ''
            ], $File);
        }
    }

    return $JSONs;
});