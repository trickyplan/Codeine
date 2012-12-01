<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Open', function ($Call)
    {
        if ($Call['Database'] == ':memory:')
            $Link = new SQLite3($Call['Database']);
        else
            $Link = new SQLite3(Root.'/Data/'.$Call['Database']);

        return $Link;
    });

    setFn('Read', function ($Call)
    {
        $Call['Scope'] = strtolower($Call['Scope']);

        $Query = F::Run('IO.Storage.SQLite.Syntax', 'Read', $Call);

        d(__FILE__, __LINE__, $Query);

        $Result = $Call['Link']->query($Query);
        F::Counter('SQLite');

        if ($Call['Link']->errno != 0)
            F::Log($Call['Link']->error, LOG_CRIT);

        if ($Result->num_rows>0)
        {
            while($Row = $Result->fetch_array(SQLITE3_ASSOC))
                $Data[] = $Row;

            $Result->finalize();
            F::Log('['.sizeof($Data).'] '.$Query, LOG_DEBUG);

            $Call['Data'] = $Data;
        }
        else
        {
            $Call['Data'] = null;
            F::Log('[Empty] '.$Query, LOG_INFO);
        }

        return $Call['Data'];
    });

    setFn('Write', function ($Call)
    {
        $Call['Scope'] = strtolower($Call['Scope']);

        if (isset($Call['Where']))
        {
            if (isset($Call['Data']))
                $Query = F::Run('IO.Storage.SQLite.Syntax', 'Update', $Call);
            else
                $Query = F::Run('IO.Storage.SQLite.Syntax', 'Delete', $Call);
        }
        else
            $Query = F::Run('IO.Storage.SQLite.Syntax', 'Insert', $Call);

        F::Log($Query, LOG_INFO);

        $Call['Link']->query($Query);

        if (!isset($Call['Data']['ID']))
            $Call['Data']['ID'] = $Call['Link']->lastInsertRowID();

        F::Counter('SQLite');

        if ($Call['Link']->errno != 0)
        {
            F::Log($Call['Link']->error, LOG_ERR);
            return null;
        }

        if (isset($Call['Data']))
            return $Call['Data'];
        else
            return null;
    });

    setFn('Close', function ($Call)
    {
        sqlite_close($Call['Link']);

        return $Call;
    });

    setFn('Count', function ($Call)
    {
        // TODO Realize "Count" function


        return $Call;
    });