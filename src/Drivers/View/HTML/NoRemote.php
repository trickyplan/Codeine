<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        if (preg_match_all('@a href="http://(.*)"@SsUu',$Call['Output'], $Links))
            foreach ($Links[1] as $IX => $Link)
                if (!in_array($Link, $Call['No Remote']['Excluded'])
                &&
                !in_array($Link, $Call['Project']['Hosts']))
                    $Call['Output'] = str_replace($Links[0][$IX], 'a href="/go/'.$Link.'"',$Call['Output']);

        return $Call;
    });