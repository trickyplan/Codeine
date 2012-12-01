<?php

  /* Codeine
     * @author BreathLess
     * @description  MySQL Driver
     * @package Codeine
     * @version 7.x
     */

    setFn ('Open', function ($Call)
    {
        try
        {
            $Link = new PDO($Call['DSN'], $Call['User'], $Call['Password']);
        }
        catch (PDOException $e)
        {
            F::Log($e->getMessage(), LOG_ERR);
            $Link = null;
        }

        return $Link;
    });

    setFn ('Read', function ($Call)
    {
        $Query = F::Run('IO.Storage.PDO.Syntax', 'Read', $Call);

        if (null == ($Data = F::Get($Query))) // FIXME Нормальная мемоизация
        {
            $Result = $Call['Link']->query($Query);
            F::Counter('MySQL');

            if ($Call['Link']->errorCode() != 0)
                F::Log($Call['Link']->errorInfo(), LOG_CRIT);

            if ($Result->rowCount()>0)
            {
                $Data = $Result->fetchAll(PDO::FETCH_ASSOC);
                F::Log('['.sizeof($Data).'] '.$Query, LOG_DEBUG);
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
                $Query = F::Run('IO.Storage.PDO.Syntax', 'Update', $Call);
            else
                $Query = F::Run('IO.Storage.PDO.Syntax', 'Delete', $Call);
        }
        else
            $Query = F::Run('IO.Storage.PDO.Syntax', 'Insert', $Call);

        F::Log($Query, LOG_INFO);

        $Call['Link']->exec($Query);

        if (!isset($Call['Data']['ID']))
            $Call['Data']['ID'] = $Call['Link']->lastInsertId();

        F::Counter('MySQL');

        if ($Call['Link']->errorCode() != 0)
        {
            F::Log($Call['Link']->errorInfo(), LOG_ERR);
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
        return $Call['Link']->query($Call['Run']);
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
        $Query = F::Run('IO.Storage.PDO.Syntax', 'Count', $Call);
        $Result = $Call['Link']->query($Query);
        F::Log($Query, LOG_DEBUG);
        F::Counter('MySQL');

        if ($Result)
            $Result = $Result->fetch(PDO::FETCH_ASSOC);

        return $Result['count(*)'];
    });

    setFn ('ID', function ($Call)
    {
        $Result = $Call['Link']->query('SELECT MAX(id) AS ID FROM '.$Call['Scope']);
        F::Counter('MySQL');

        if ($Result)
            $Result = $Result->fetch(PDO::FETCH_ASSOC);

        return $Result['ID']+$Call['Increment'];
    });