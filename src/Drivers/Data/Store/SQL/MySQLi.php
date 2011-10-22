<?php

    /* Codeine
     * @author BreathLess
     * @description: Standart MySQLi Driver
     * @package Codeine
     * @version 6.0
     * @date 13.08.11
     * @time 21:22
     */

    self::Fn('Open', function ($Call)
    {
        $Link = new mysqli($Call['URL'], $Call['User'], $Call['Password'], $Call['Database']);
        // FIXME Error handling
        $Link->set_charset('utf8');

        return $Link;
    });

    self::Fn('Find', function ($Call)
    {
        $Rows = array();

        $Query = 'select `ID` from '.$Call['Scope'].' where '.F::Run($Call, array('_N' => 'Data.Syntax.MySQL'));

        $Result = $Call['Link']->query($Query);

        while($Row = $Result->fetch_assoc())
            $Rows[] = $Row['ID'];
        
        $Result->free();
        return $Rows;
    });

    self::Fn('Load', function ($Call)
    {
        $Rows = array();

        $Query = 'select * from '.$Call['Scope'].' where '. F::Run($Call, array('_N' => 'Data.Syntax.MySQL'));

        $Result = $Call['Link']->query($Query);

        if ($Result->num_rows > 0)
        {
            while($Row = $Result->fetch_assoc())
                $Rows[] = $Row;

            $Result->free();

            return $Rows;
        }
        else
            return null;
    });

    self::Fn('Create', function ($Call)
    {
        $Queries = array();
        
        $Groups = F::Run($Call, array('_N' => 'Data.Syntax.MySQL'));

        foreach ($Groups as $Keys => $Group)
            $Queries[] = 'insert into '.$Call['Scope'].' ('.$Keys.') values '.implode(',',$Group);

        return $Call['Link']->query(implode(';', $Queries))
               or F::Run($Call, array('_N' => 'Code.Flow.Hook','_F'=>'Run','On'=> $Call['_N'], 'Message' => $Call['Link']->error));
    });

    self::Fn('Update', function ($Call)
    {
        $Where = F::Run($Call, array('_N' => 'Data.Syntax.MySQL', '_F' => 'Find'));

        $Set = F::Run($Call, array('_N' => 'Data.Syntax.MySQL'));

        $Query = 'UPDATE '.$Call['Scope'].' SET '.$Set.' WHERE '.$Where;

        return  $Call['Link']->query($Query) or
                F::Run(array('_N' => 'Code.Flow.Hook','_F'=>'Run','On'=> $Call['_N'], 'Message' => $Call['Link']->error));
    });

    self::Fn('Delete', function ($Call)
    {
        $Rows = array();

        $Query = 'delete from '.$Call['Scope'].' where '. F::Run($Call, array('_N' => 'Data.Syntax.MySQL'));
        $Result = $Call['Link']->query($Query);

        while($Row = $Result->fetch_assoc())
            $Rows[$Row['ID']] = $Row;

        $Result->free();
        return $Rows;
    });