<?php

    /* Sphinx
     * @author BreathLess
     * @description  
     * @package Sphinx
     * @version 7.2
     */

    setFn('Do', function ($Call)
    {
        $Location = '';

        if (isset($Call['Session']['Location']))
            $Location = $Call['Session']['Location'];

        if (isset($Call['Location']))
            $Location = $Call['Location'];

        if (isset($Call['Request']['Location']))
            $Location = F::Live($Call['Request']['Location']);

        if (isset($Call['Call']['Where']['Location']))
            $Location = F::Live($Call['Call']['Where']['Location']);

        $Location = F::Live($Location);

        if (isset($Location) && !empty($Location))
        {
            $Call['Session']['Location'] = $Location;
            $Call['Session']['LocationURL'] = '/'.F::Run('Entity', 'Far', ['Entity' => 'Location', 'Where' => $Location, 'Key' => 'Slug']);
        }
        else
        {
            $Call['Session']['Location'] = 0;
            $Call['Session']['LocationURL'] = '';
        }

        if ($Location != $Call['Session']['Location'])
            F::Run('Session', 'Write', $Call, ['Data' => ['Location' => $Location]]);

        return $Call;
    });