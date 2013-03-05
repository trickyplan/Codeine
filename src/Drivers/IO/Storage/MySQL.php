<?php

  /* Codeine
     * @author BreathLess
     * @description  MySQL Driver
     * @package Codeine
     * @version 7.x
     */

    setFn ('Open', function ($Call)
    {
        $Link = new mysqli($Call['Server'], $Call['User'], F::Live($Call['Password']));

        if (!$Link->ping())
            return null;

        $Link->select_db ($Call['Database']);
        $Link->set_charset ($Call['Charset']);
     //   $Link->autocommit ($Call['AutoCommit']);

        return $Link;
    });

    setFn ('Read', function ($Call)
    {
        $Query = F::Run('IO.Storage.MySQL.Syntax', 'Read', $Call);

        if (null == ($Data = F::Get($Query)) or isset($Call['NoMemo'])) // FIXME
        {
            $Result = $Call['Link']->query($Query);
            F::Counter('MySQL');

            if ($Call['Link']->errno != 0)
            {
                F::Log($Query, LOG_ERR);
                F::Log($Call['Link']->error, LOG_ERR);
            }

            if ($Result->num_rows>0)
            {
                $Data = $Result->fetch_all(MYSQLI_ASSOC);
                $Result->free();
                F::Log('['.sizeof($Data).'] '.$Query, LOG_INFO);
            }
            else
                F::Log('[Empty] '.$Query, LOG_INFO);

            F::Set($Query, $Data);
        }

        return $Data;
    });

    setFn ('Write', function ($Call)
    {
        if (isset($Call['Where']))
        {
            if (isset($Call['Data']))
                $Query = F::Run('IO.Storage.MySQL.Syntax', 'Update', $Call);
            else
                $Query = F::Run('IO.Storage.MySQL.Syntax', 'Delete', $Call);
        }
        else
            $Query = F::Run('IO.Storage.MySQL.Syntax', 'Insert', $Call);

        F::Log($Query, LOG_INFO);

        $Call['Link']->query($Query);

        if (!isset($Call['Data']['ID']))
            $Call['Data']['ID'] = $Call['Link']->insert_id;

        F::Counter('MySQL');

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

    setFn ('Close', function ($Call)
    {
        return true;
    });

    setFn ('Run', function ($Call)
    {
        $Result = $Call['Link']->query($Call['Run']);

        if ($Result->num_rows>0)
            {
                $Data = $Result->fetch_all(MYSQLI_ASSOC);
                $Result->free();
                F::Log('['.sizeof($Data).'] '.$Call['Run'], LOG_DEBUG);
            }
        else
            $Data = null;

        return $Data;
    });

    setFn ('Status', function ($Call)
    {
        $Data = explode('  ', $Call['Link']->stat());
        foreach ($Data as &$Row)
            $Row = explode(':', $Row);

        return $Data;
    });

    setFn ('Count', function ($Call)
    {
        $Query = F::Run('IO.Storage.MySQL.Syntax', 'Count', $Call);
        $Result = $Call['Link']->query($Query);

        F::Log($Query, LOG_DEBUG);
        F::Counter('MySQL');

        if ($Result)
            $Result = $Result->fetch_assoc();

        return $Result['count(*)'];
    });

    setFn ('ID', function ($Call)
    {
        $Result = $Call['Link']->query('SELECT MAX(id) AS ID FROM '.$Call['Scope']);
        F::Counter('MySQL');

        if ($Result)
            $Result = $Result->fetch_assoc();

        return $Result['ID']+$Call['Increment'];
    });