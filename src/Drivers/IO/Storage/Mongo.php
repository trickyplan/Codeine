<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn ('Open', function ($Call)
    {
        $Link = new Mongo('mongodb://'.$Call['Server']);
        $Link = $Link->selectDB($Call['Database']);

        if (isset($Call['Auth']))
            $Link->authentificate($Call['Auth']['Username'], $Call['Auth']['Password']);

        return $Link;
    });

    self::setFn ('Read', function ($Call)
    {
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');
        $Data = null;
        if (isset($Call['Where']))
        {
            $Where = [];

            foreach ($Call['Where'] as $Key => &$Value) // FIXME Повысить уровень абстракции
            {
                if (!isset($Call['Nodes'][$Key]['Type']))
                    $Call['Nodes'][$Key]['Type'] = 'Dummy';

                if (is_array($Value))
                    foreach ($Value as $Relation => &$cValue)
                    {
                        if ($Relation == 'IN')
                            $Where[$Key] = ['$in' => $cValue];
                        else
                            $Where[$Key] = [$Relation => F::Run('Data.Type.'.$Call['Nodes'][$Key]['Type'], 'Read', array('Value' => $cValue, 'Purpose' => 'Where'))];
                    }
                else
                    $Where[$Key] = F::Run('Data.Type.'.$Call['Nodes'][$Key]['Type'], 'Read', array('Value' => $Value, 'Purpose' => 'Where'));
            }
            unset($Value, $Key);
            $Cursor = $Call['Link']->$Call['Scope']->find($Where);
        }
        else
            $Cursor = $Call['Link']->$Call['Scope']->find();

        if (isset($Call['Fields']))
        {
            $Fields = array();
            foreach ($Call['Fields'] as $Field)
                $Fields[$Field] = true;

            $Cursor->fields($Fields);
        }

        if (isset($Call['Sort']))
            foreach($Call['Sort'] as $Key => $Direction)
                $Cursor->sort(array($Key => (int)(($Direction == SORT_ASC) or ($Direction == 1))? 1: -1));

        if (isset($Call['Limit']))
            $Cursor->limit($Call['Limit']['To']-$Call['Limit']['From'])->skip($Call['Limit']['From']);

        if ($Cursor->count()>0)
            foreach ($Cursor as $cCursor)
            {
                unset($cCursor['_id']);
                $Data[] = $cCursor;
            }
        else
            $Data = null;

        return $Data;
    });

    self::setFn ('Write', function ($Call)
    {
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');
        if (isset($Call['Where']))
        {
            $Where = [];

            foreach ($Call['Where'] as $Key => &$Value) // FIXME Повысить уровень абстракции
            {
                if (!isset($Call['Nodes'][$Key]['Type']))
                    $Call['Nodes'][$Key]['Type'] = 'Dummy';

                if (is_array($Value))
                    foreach ($Value as $Relation => &$cValue)
                    {
                        if ($Relation == 'IN')
                            $Where[$Key] = ['$in' => $cValue];
                        else
                            $Where[$Key] = [$Relation => F::Run('Data.Type.'.$Call['Nodes'][$Key]['Type'], 'Read', array('Value' => $cValue, 'Purpose' => 'Where'))];
                    }
                else
                    $Where[$Key] = F::Run('Data.Type.'.$Call['Nodes'][$Key]['Type'], 'Read', array('Value' => $Value, 'Purpose' => 'Where'));
            }

            unset($Value, $Key);

            $Call['Where'] = $Where;
        }

        if (null === $Call['Data'])
        {
            if (isset($Call['Where']))
                return $Call['Link']->$Call['Scope']->remove ($Call['Where']);
            else
                return $Call['Link']->$Call['Scope']->remove ();
        }
        else
        {
            $Data = array();

            foreach ($Call['Data'] as $Key => $Value)
                $Data = F::Dot($Data, $Key, $Value);

            if (isset($Call['Current']))
                $Data = F::Merge($Call['Current'], $Data);

            if (isset($Call['Where']))
                $Call['Link']->$Call['Scope']->update($Call['Where'], ['$set' => $Data]) or F::Hook('IO.Mongo.Update.Failed', $Call);
            else
                $Call['Link']->$Call['Scope']->insert ($Data);

            return $Data;
        }
    });

    self::setFn ('Close', function ($Call)
    {
        return true;
    });

    self::setFn ('Execute', function ($Call)
    {

        return $Call['Link']->execute($Call['Command']);
    });

    self::setFn ('Count', function ($Call)
    {
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');
        if (isset($Call['Where']))
        {
            foreach ($Call['Where'] as $Key => &$Value) // FIXME Повысить уровень абстракции
            {
                if (isset($Call['Nodes'][$Key]['Type']))
                {
                    if (is_array($Value))
                        foreach ($Value as &$cValue)
                            $cValue = F::Run('Data.Type.'.$Call['Nodes'][$Key]['Type'], 'Read', array('Value' => $cValue, 'Purpose' => 'Where'));
                    else
                        $Value = F::Run('Data.Type.'.$Call['Nodes'][$Key]['Type'], 'Read', array('Value' => $Value, 'Purpose' => 'Where'));
                }

            }

            unset($Value, $Key);

            $Cursor = $Call['Link']->$Call['Scope']->find($Call['Where']);
        }
        else
            $Cursor = $Call['Link']->$Call['Scope']->find();

        return $Cursor->count();
    });
