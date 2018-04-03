<?php

setFn('Update', function ($Call) {
    return F::Dot($Call, 'Finance.Currency', F::Run('Finance.Currency', 'Rate.List', $Call));
});