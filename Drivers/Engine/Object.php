<?php

    /* Codeine
     * @author BreathLess
     * @description: Model Server
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Model.Load', function ($Call)
    {
        $Model =  json_decode(file_get_contents(F::Find('Options/Object/'.$Call['Scope'].'.json')), true); //FIXME

        foreach ($Model['Nodes'] as &$Node)
            if (!isset($Node['Point']))
                $Node['Point'] = 'Default';

        return $Model;
    });

    self::Fn('Create', function ($Call)
    {
        $Data = array();
        $Model = F::Run($Call, array('_F' => 'Model.Load'));

        foreach ($Call['Value'] as $Key => $Value)
            if (isset($Model['Nodes'][$Key]))
                $Data[$Model['Nodes'][$Key]['Point']][$Key] = $Value;

        foreach ($Data as $Point => $Sector)
        {
            $Call['Value'] = $Sector;
            F::Run($Call, $Model['Points'][$Point],
                array(
                    '_N' => 'Object.'.$Model['Points'][$Point]['Scheme']
                )
            );
        }

        return true;
    });

    self::Fn('Load', function ($Call)
    {
        $Model = F::Run($Call, array('_F' => 'Model.Load'));
        $Data = array();

        foreach ($Model['Points'] as $Name => $Point)
            if (null !== ($Stored = F::Run($Call, $Point, array('_N' => 'Object.'.$Point['Scheme']))))
                $Data = F::Merge($Data, $Stored);

        return $Data;

    });

    self::Fn('Find', function ($Call)
    {
        $Model = F::Run($Call, array('_F' => 'Model.Load'));
        $Data = array();

        foreach ($Model['Points'] as $Name => $Point)
            if (null !== ($Stored = F::Run($Call, $Point, array('_N' => 'Object.'.$Point['Scheme']))))
                $Data = F::Merge($Data, $Stored);

        return $Data;

    });

    self::Fn('Erase', function ($Call)
    {
  
    });

    self::Fn('Node.Add', function ($Call)
    {
        $Model = F::Run($Call, array('_F' => 'Model.Load'));

        $Point = isset($Model['Nodes'][$Call['Key']]['Point'])? $Model['Nodes'][$Call['Key']]['Point']: 'Default';

        return F::Run($Call, $Model['Points'][$Point],
                    array('_N' => 'Object.'.$Model['Points'][$Point]['Scheme'])
                    );
    });

    self::Fn('Node.Set', function ($Call)
    {


        $Model = F::Run($Call, array('_F' => 'Model.Load'));

        $Point = isset($Model['Nodes'][$Call['Key']]['Point'])? $Model['Nodes'][$Call['Key']]['Point']: 'Default';

        return F::Run($Call, $Model['Points'][$Point],
                    array('_N' => 'Object.'.$Model['Points'][$Point]['Scheme'])
                    );
    });

    self::Fn('Node.Del', function ($Call)
    {
        $Model = F::Run($Call, array('_F' => 'Model.Load'));

        $Point = isset($Model['Nodes'][$Call['Key']]['Point'])? $Model['Nodes'][$Call['Key']]['Point']: 'Default';

        return F::Run($Call, $Model['Points'][$Point],
                    array('_N' => 'Object.'.$Model['Points'][$Point]['Scheme'])
                    );
    });