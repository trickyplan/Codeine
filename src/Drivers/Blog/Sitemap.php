<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    setFn('Generate', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read', array ('Entity' => 'Blog'));
        $Data = ['http://'.$_SERVER['HTTP_HOST'].'/blog'];

        foreach ($Elements as $Element)
            $Data[] = 'http://'.$_SERVER['HTTP_HOST'].'/blog/'.$Element['Slug']; // FIXME!

        return $Data;
     });