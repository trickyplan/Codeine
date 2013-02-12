<?php

    /* Sphinx
     * @author BreathLess
     * @description  
     * @package Sphinx
     * @version 7.2
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['Call']['Where']['Location']))
            $Call['Location'] = F::Live($Call['Call']['Where']['Location']);

        if (isset($Call['Location']) && !empty($Call['Location']))
        {
            $Call['Session']['Location'] = F::Live($Call['Location']);
            $Call['Session']['LocationURL'] = '/'.F::Run('Entity', 'Far', ['Entity' => 'Location', 'Where' => F::Live($Call['Location']), 'Key' => 'Slug']);
        }
        else
        {
            $Call['Session']['Location'] = 0;
            $Call['Session']['LocationURL'] = '';
        }

        return $Call;
    });