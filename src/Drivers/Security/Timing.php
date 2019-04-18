<?php
    
    setFn('Do', function ($Call)
    {
        return F::Run('Security.Timing.'.F::Dot($Call, 'Security.Timing.Driver'), 'Do', $Call);
    });