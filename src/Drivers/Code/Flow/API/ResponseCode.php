<?php

setFn('afterAPIRun', function ($Call)
{
    if (isset($Call['Output']['Content']['Response']['Data']['Status']) &&
        $Call['Output']['Content']['Response']['Data']['Status']['Code'] === 1) {
        $Call['HTTP']['Headers']['HTTP/1.1'] = '204 Not Found';
    }

    return $Call;
});