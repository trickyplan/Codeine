<?php

    setFn('Process', function ($Call)
    {
        if (F::Dot($Call, 'Node.Unique') && F::Dot($Call['Data'], $Call['Name']))
        {
            if (($Where = F::Dot($Call, 'Node.Unique.Where')) === null)
                $Where = [];
            else
                $Where = F::Live($Where, $Call);

            switch ($Mode = F::Dot($Call, 'Validation.Unique.Mode'))
            {
                case 'Create':
                    $Limit = 0;
                    break;

                case 'Update':
                    $Limit = 1;
                    break;
                default:
                    $Limit = 0;
                    F::Log('Incorrect Validation Unique Mode: '.$Mode, LOG_WARNING);
            }

            if ($Call['Name'] !== 'ID')
            {
                $Where[$Call['Name']] = F::Dot($Call['Data'], $Call['Name']);
                $Where['ID'] =
                    [
                        '$ne' => $Call['Data']['ID']
                    ];
                $Limit = 0;
            }
            else
            {
                $Where['ID'] = $Call['Data']['ID'];
            }

            F::Log('Checking unique *'.$Call['Name']
                .'* value *'
                .F::Dot($Call['Data'], $Call['Name'])
                .'* ('.$Limit.')', LOG_INFO);

            $Entity = F::Run('Entity', 'Read',
                [
                    'Entity'  => $Call['Entity'],
                    'Where'   => $Where
                ]);

            $Count = count($Entity);

            if ($Count > $Limit)
            {
                F::Log('Non-unique ('.$Count.'>'.$Limit.') '.$Call['Name'].' value: '.j(F::Dot($Call['Data'],$Call['Name'])).' ', LOG_NOTICE);
                return
                    [
                        'Validator'     => 'Unique',
                        'Entity'        => $Call['Entity'],
                        'Name'          => $Call['Name'],
                        'ID'            => $Entity[0]['ID']
                    ];
            }
        }

        return true;
    });
