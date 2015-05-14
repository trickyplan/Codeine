<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['No Remote']['Enabled'])
            && $Call['No Remote']['Enabled']
            && preg_match_all('@a href="(https?://.*)"@SsUu',$Call['Output'], $Links))
        {
            foreach ($Links[1] as $IX => $Link)
                if (!in_array($Link, $Call['No Remote']['Excluded'])
                    &&
                    !in_array(parse_url($Link, PHP_URL_HOST), $Call['Project']['Hosts']))
                    $Call['Output'] = str_replace($Links[0][$IX], 'a href="/go/'.$Link.'"',$Call['Output']);
        }

        return $Call;
    });