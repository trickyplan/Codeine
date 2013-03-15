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

    setFn('Operation', function ($Call)
    {
        $Call['Result'] = $Call['Link']->query($Call['Query']);

        if ($Call['Link']->errno != 0)
        {
            F::Log($Call['Query'], LOG_ERR);
            F::Log($Call['Link']->errno.':'.$Call['Link']->error, LOG_ERR);
            $Call = F::Hook('MySQL.Error.'.$Call['Link']->errno, $Call);
        }
        else
            F::Log($Call['Query'], LOG_INFO);

        F::Counter('MySQL');

        return $Call;
    });

    setFn ('Read', function ($Call)
    {
        $Query = F::Run('IO.Storage.MySQL.Syntax', 'Read', $Call);

        if (null == ($Data = F::Get($Query)) or isset($Call['NoMemo'])) // FIXME
        {
            $Call = F::Run(null, 'Operation', $Call, ['Query' => $Query]);

            if ($Call['Result']->num_rows>0)
            {
                $Data = $Call['Result']->fetch_all(MYSQLI_ASSOC);
                $Call['Result']->free();
            }

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

        $Call = F::Run(null, 'Operation', $Call, ['Query' => $Query]);

        if (!isset($Call['Data']['ID']))
            $Call['Data']['ID'] = $Call['Link']->insert_id;

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
        $Call = F::Run(null, 'Operation', $Call, ['Query' => $Call['Run']]);

        if ($Call['Result']->num_rows>0)
            {
                $Data = $Call['Result']->fetch_all(MYSQLI_ASSOC);
                $Call['Result']->free();
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

        $Call = F::Run(null, 'Operation', $Call, ['Query' => $Query]);

        if ($Call['Result'])
            $Call['Result'] = $Call['Result']->fetch_assoc();

        return $Call['Result']['count(*)'];
    });

    setFn ('ID', function ($Call)
    {
        $Call = F::Run(null, 'Operation', $Call, ['Query' => 'SELECT MAX(id) AS ID FROM '.$Call['Scope']]);

        if ($Call['Result'])
            $Call['Result'] = $Call['Result']->fetch_assoc();

        return $Call['Result']['ID']+$Call['Increment'];
    });