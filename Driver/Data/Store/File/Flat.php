<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Flatfile
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 23.11.10
     * @time 20:49
     */

    self::Fn('Open', function ($Call)
    {
        return $Call['Options']['DSN'];
    });

    self::Fn('Close', function ($Call)
    {
        return true;
    });

    self::Fn('Read', function ($Call)
    {
        Code::On('Data.FS.Query',$Call);
        return file_get_contents(Root.$Call['Link'].'/'.$Call['Scope'].'/'.$Call['Where']['ID']);
    });

    self::Fn('Create', function ($Call)
    {
        Code::On('Data.FS.Query',$Call);
        return file_put_contents(Root.$Call['Link'].'/'.$Call['Options']['Scope'].'/'.$Call['ID'], $Call['Body']);
    });

    self::Fn('Update', function ($Call)
    {
        Code::On('Data.FS.Query',$Call);
        return file_put_contents(Root.$Call['Link'].'/'.$Call['ID'], $Call['Body']);
    });

    self::Fn('Delete', function ($Call)
    {
        Code::On('Data.FS.Query',$Call);
        return unlink(Root.$Call['Link'].'/'.$Call['ID']);
    });
