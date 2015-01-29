<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Check', function ($Call)
    {
        return F::file_exists(Root.'/locks/'.$Call['ID']);
    });

    setFn('Toggle', function ($Call)
    {
        return F::file_exists(Root.'/locks/'.$Call['ID'])? unlink(Root.'/locks/'.$Call['ID']): touch (Root.'/locks/'.$Call['ID']);
    });