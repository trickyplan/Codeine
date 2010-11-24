<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: MongoDB Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 24.11.10
     * @time 21:56
     */

    self::Fn('Connect', function ($Call)
    {
        $M  = new Mongo($Call['Server']);
        // FIXME Error
        return $M->$Call['Database'];
    });

    self::Fn('Disconnect', function ($Call)
    {
        $Call['Store']->close();
    });

    self::Fn('Read', function ($Call)
    {
        $Data = array();
        $Cursor = $Call['Store']->$Call['Point']['Scope']->find($Call['Data']['Where']['ID']);
        $IC = 0;

        while ($Cursor->hasNext())
        {
            $Data[$IC] = $Cursor->getNext();
            unset($Data[$IC]['_id']);
            $IC++;
        }

        if (empty($Data))
            $Data = null;
        elseif (count($Data)==1)
                $Data = $Data[0];

        return $Data;
    });

    self::Fn('Create', function ($Call)
    {
        if (!$Call['Store']->$Call['Point']['Scope']->insert($Call['Data']['Data']))
        {
            Code::Hook('Data', 'errDataMongoDBInsertFailed', $Call);
            return false;
        }
        else
            return true;
    });

    self::Fn('Update', function ($Call)
    {
        if ($Call['Store']->$Call['Point']['Scope']->remove($Call['Where']['ID']))
            if ($Call['Store']->$Call['Point']['Scope']->insert($Call['Data']['Data']))
                return true;

        Code::Hook('Data', 'errDataMongoDBUpdateFailed', $Call);
        return false;
    });

    self::Fn('Delete', function ($Call)
    {
        if (!$Call['Store']->$Call['Point']['Scope']->remove($Call['Where']['ID']))
        {
            Code::Hook('Data', 'errDataMongoDBDeleteFailed', $Call);
            return false;
        }
        else
            return true;
    });