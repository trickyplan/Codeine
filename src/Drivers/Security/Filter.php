<?php

setFn('Do', function ($Call) {
    $ValuePath = 'Data.'.F::Dot($Call, 'Filter.Name');
    $FieldValue = F::Run('Security.Filter.'.F::Dot($Call, 'Filter.Type'), 'Do', [
        'Value' => F::Dot($Call, $ValuePath)
    ]);
    $Call = F::Dot($Call, $ValuePath, $FieldValue);
    return $Call;
});