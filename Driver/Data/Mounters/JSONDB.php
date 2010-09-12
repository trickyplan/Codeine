<?php

    function F_JSONDB_Mount ($Args)
    {
        Data::$Data[$Args['DSN']] = json_decode(file_get_contents(Root.Data.$Args['DSN'].'.json'), true);
        return $Args['DSN'];
    }

    function F_JSONDB_Unmount ($Args)
    {
        file_put_contents(Root.Data.$Args.'.json', json_encode(Data::$Data[$Args]));
        return true;
    }

    function F_JSONDB_Create ($Args)
    {
        return Data::$Data[$Args['Storage']][$Args['DDL']['I']] = $Args['DDL']['Data'];
    }

    function F_JSONDB_Read ($Args)
    {
        if (isset(Data::$Data[$Args['Storage']][$Args['DDL']['I']]))
            return Data::$Data[$Args['Storage']][$Args['DDL']['I']];
        else return null;
    }

    function F_JSONDB_Update ($Args)
    {
        krumo($Args);
        return Data::$Data[$Args['Storage']][$Args['DDL']['I']] = $Args['DDL']['Data'];
    }

    function F_JSONDB_Delete ($Args)
    {
        unset(Data::$Data[$Args['Storage']][$Args['DDL']['I']]);
        return !isset(Data::$Data[$Args['Storage']][$Args['DDL']['I']]);
    }

    function F_JSONDB_Exist ($Args)
    {
        return isset(Data::$Data[$Args['Storage']][$Args['DDL']['I']]);
    }