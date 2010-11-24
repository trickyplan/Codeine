<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Cascade FS
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 23.11.10
     * @time 21:12
     */

    self::Fn('Connect', function ($Call)
    {
        return $Call;
    });

    self::Fn('Read', function ($Call)
    {
        if (!is_array($Call['Data']['Where']['ID']))
            $Call['Data']['Where']['ID'] = array($Call['Data']['Where']['ID']);

        foreach ($Call['Data']['Where']['ID'] as $cName)
        {
            $cName = $Call['Store']['Point']['DSN'].'/'.Data::Path($Call['Point']['Scope']).$cName;

            if (file_exists(Root.$cName))
                $R = Root.$cName;
            elseif (file_exists(Engine.$cName))
                $R = Engine.$cName;
            // FIXME
//            else
//                foreach (self::$_Conf['Shared'] as $Shared)
//                    if (file_exists($Shared.DS.$cName))
//                    {
//                        $R = $Shared.DS.$cName;
//                        break;
//                    }
       }

       if (isset($R))
           return file_get_contents($R);

       return null;
    });

    self::Fn('Version', function ($Call)
    {
        if (!is_array($Call['Data']['Where']['ID']))
            $Call['Data']['Where']['ID'] = array($Call['Data']['Where']['ID']);

        foreach ($Call['Data']['Where']['ID'] as $cName)
        {
            $cName = $Call['Store']['Point']['DSN'].'/'.Data::Path($Call['Point']['Scope']).$cName;

            if (file_exists(Root.$cName))
                $R = Root.$cName;
            elseif (file_exists(Engine.$cName))
                $R = Engine.$cName;
            // FIXME
//            else
//                foreach (self::$_Conf['Shared'] as $Shared)
//                    if (file_exists($Shared.DS.$cName))
//                    {
//                        $R = $Shared.DS.$cName;
//                        break;
//                    }
        }

       if (isset($R))
           return filemtime($R);

       return null;
    });

    self::Fn('Create', function ($Call)
    {
        return file_put_contents($Call['ID'], $Call['Body']);
    });

