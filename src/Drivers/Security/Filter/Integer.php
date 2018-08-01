<?php

setFn('Do', function ($Call) {
    return filter_var($Call['Value'], FILTER_SANITIZE_NUMBER_INT);
});