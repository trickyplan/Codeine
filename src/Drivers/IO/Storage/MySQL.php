<?php

  /* Codeine
     * @author BreathLess
     * @description  MySQL Driver
     * @package Codeine
     * @version 7.x
     */

    self::setFn ('Open', function ($Call)
    {
        $Link = new mysqli($Call['Server'], $Call['User'], F::Live($Call['Password']));

        if (!$Link->ping())
            return null;

        $Link->select_db ($Call['Database']);
        $Link->set_charset ($Call['Charset']);
     //   $Link->autocommit ($Call['AutoCommit']);

        return $Link;
    });

    self::setFn ('Read', function ($Call)
    {
        $Query = F::Run('IO.Storage.MySQL.Syntax', 'Read', $Call);

        if (null == ($Data = F::Get($Query))) // FIXME Нормальная мемоизация
        {
            $Result = $Call['Link']->query($Query);
            F::Counter('MySQL');

            if ($Call['Link']->errno != 0)
                F::Log($Call['Link']->error, LOG_CRIT);

            if ($Result->num_rows>0)
            {
                $Data = $Result->fetch_all(MYSQLI_ASSOC);
                $Result->free();
                F::Log('['.sizeof($Data).'] '.$Query, LOG_DEBUG);
            }
            else
                F::Log('[Empty] '.$Query, LOG_INFO);

            F::Set($Query, $Data);
        }

        return $Data;
    });

    self::setFn ('Write', function ($Call)
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

        F::Counter('MySQL');

        if ($Call['Link']->errno != 0)
            F::Log($Call['Link']->error, LOG_ERR);

        if (isset($Call['Data']))
            return $Call['Data'];
        else
            return null;
    });

    self::setFn ('Close', function ($Call)
    {
        return true;
    });

    self::setFn ('Run', function ($Call)
    {
        return $Call['Link']->query($Call['Run']);
    });

    self::setFn ('Status', function ($Call)
    {
        $Data = explode('  ', $Call['Link']->stat());
        foreach ($Data as &$Row)
            $Row = explode(':', $Row);

        return $Data;
    });

    self::setFn ('Count', function ($Call)
    {
        $Result = $Call['Link']->query(F::Run('IO.Storage.MySQL.Syntax', 'Count', $Call));
        F::Counter('MySQL');

        if ($Result)
            $Result = $Result->fetch_assoc();

        return $Result['count(*)'];
    });