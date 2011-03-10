<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Cascade FS
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 23.11.10
     * @time 21:12
     */

    self::Fn('Connect', function ($Call)
    {
        return true;
    });

    self::Fn('Read', function ($Call)
    {
        if (!is_array($Call['Where']['ID']))
            $Call['Where']['ID'] = array($Call['Where']['ID']);

        foreach ($Call['Where']['ID'] as $cName)
        {
            $cName = $Call['Options']['DSN'].'/'.
                     Data::Path($Call['Options']['Scope']).$Call['Prefix'].$cName.$Call['Postfix'];

            if (file_exists(Root.$cName))
                $R = Root.$cName;
            elseif (file_exists(Engine.$cName))
                $R = Engine.$cName;
       }

       if (isset($R))
           return file_get_contents($R);
       else
           return null;
    });

    self::Fn('Version', function ($Call)
    {
        if (!is_array($Call['Where']['ID']))
            $Call['Where']['ID'] = array($Call['Where']['ID']);

        foreach ($Call['Where']['ID'] as $cName)
        {
            $cName = $Call['Options']['DSN'].'/'.
                     Data::Path($Call['Options']['Scope']).$Call['Prefix'].$cName.$Call['Postfix'];

            if (file_exists(Root.$cName))
                $R = Root.$cName;
            elseif (file_exists(Engine.$cName))
                $R = Engine.$cName;
            // FIXME
//            else
//                foreach (self::$_Options['Shared'] as $Shared)
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

