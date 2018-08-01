<?php

setFn('Do', function ($Call) {
    return filter_var($Call['Value'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
});