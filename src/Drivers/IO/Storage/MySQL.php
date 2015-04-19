<?php

  /* Codeine
     * @author bergstein@trickyplan.com
     * @description  MySQL Driver
     * @package Codeine
     * @version 8.x
     */

    setFn ('Open', function ($Call)
    {
        F::Log('Connecting to '.$Call['Server'].' via '.$Call['User'], LOG_INFO);
        $Link = new mysqli($Call['Server'], $Call['User'], F::Live($Call['Password']));

        if (!$Link->ping())
        {
            F::Log($Link->connect_error, LOG_CRIT, 'Administrator');
            return null;
        }

        F::Log($Link->host_info, LOG_INFO, 'Administrator');

        $Link->select_db ($Call['Database']);
        $Link->set_charset ($Call['Charset']);
        $Link->begin_transaction();
     //   $Link->autocommit ($Call['AutoCommit']);

        return $Link;
    });

    setFn('Operation', function ($Call)
    {
        $Call['MySQL Result'] = $Call['Link']->query($Call['Query']);

        if ($Call['Link']->errno != 0)
        {
            F::Log($Call['Query'], LOG_ERR, 'Administrator');
            F::Log($Call['Link']->errno.':'.$Call['Link']->error, LOG_ERR, 'Administrator');
            $Call = F::Hook('MySQL.Error.'.$Call['Link']->errno, $Call);
        }
        else
        {
            F::Log($Call['Query'], LOG_INFO, 'Administrator');
            F::Counter('MySQL');
        }

        return $Call;
    });

    setFn ('Read', function ($Call)
    {
        $Query = F::Run('IO.Storage.MySQL.Syntax', 'Read', $Call);

        F::Log($Query, LOG_DEBUG, 'Administrator');

        $Call = F::Apply(null, 'Operation', $Call, ['Query' => $Query]);

        if ($Call['MySQL Result']->num_rows>0)
        {
            $Data = $Call['MySQL Result']->fetch_all(MYSQLI_ASSOC);
            $Call['MySQL Result']->free();
        }
        else
            $Data = null;

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

        $Call = F::Apply(null, 'Operation', $Call, ['Query' => $Query]);

        if (!isset($Call['Data']['ID']))
            $Element['ID'] = $Call['Link']->insert_id;

        if ($Call['Link']->errno != 0)
        {
            F::Log($Call['Link']->error, LOG_ERR, 'Administrator');
            return null;
        }

        if (isset($Call['Data']))
            return $Call['Data'];
        else
            return null;
    });

    setFn ('Close', function ($Call)
    {
        $Call['Link']->commit();
        return $Call['Link']->close();
    });

    setFn ('Run', function ($Call)
    {
        $Call = F::Apply(null, 'Operation', $Call, ['Query' => $Call['Run']]);

        if ($Call['MySQL Result']->num_rows>0)
            {
                $Data = $Call['MySQL Result']->fetch_all(MYSQLI_ASSOC);
                $Call['MySQL Result']->free();
                F::Log('['.sizeof($Data).'] '.$Call['Run'], LOG_DEBUG, 'Administrator');
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

        $Call = F::Apply(null, 'Operation', $Call, ['Query' => $Query]);

        if ($Call['MySQL Result'])
            $Call['MySQL Result'] = $Call['MySQL Result']->fetch_assoc();

        return $Call['MySQL Result']['count(*)'];
    });

    setFn ('ID', function ($Call)
    {
        $Call = F::Apply(null, 'Operation', $Call, ['Query' => 'SELECT MAX(id) AS ID FROM '.$Call['Scope']]);

        if ($Call['MySQL Result'])
            $Call['MySQL Result'] = $Call['MySQL Result']->fetch_assoc();

        return $Call['MySQL Result']['ID']+$Call['Increment'];
    });

    setFn('Size', function ($Call)
    {
        return 0;
    });

    setFn('Commit', function ($Call)
    {
        return $Call['Link']->commit();
    });

    setFn('Rollback', function ($Call)
    {
        return $Call['Link']->rollback();
    });

    setFn('Table', function ($Call)
    {
        $Entity = F::loadOptions($Call['Entity'].'.Entity');


        $Fields = [];

        foreach ($Entity['Nodes'] as $Key => $Node)
        {
            if (!isset($Node['Type']))
                $Node['Type'] = '';

            switch ($Node['Type'])
            {
                case 'Numeral.Integer':
                    $Type = 'int';
                break;

                case 'Literal.String':
                    $Type = 'varchar (255)';
                break;

                default:
                    $Type = 'text';
                break;
            }

            $Fields[] = '`'.$Key.'` '.$Type;
        }

        echo 'create table '.$Call['Entity'].' ('.implode(',', $Fields).')'.PHP_EOL;
    });