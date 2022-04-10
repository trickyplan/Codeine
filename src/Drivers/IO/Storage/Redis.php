<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description XCache Data Driver
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call) {
        $Redis = new Redis();

        if (isset($Call['Socket'])) {
            if ($Result = $Redis->pconnect($Call['Socket'])) {
                F::Log('Connect to socket ' . $Call['Socket'], LOG_INFO, 'Administrator');
            } else {
                F::Log('No connection to socket ' . $Call['Socket'], LOG_ERR, 'Administrator');
            }
        } else {
            if ($Result = $Redis->pconnect($Call['Server'], $Call['Port'])) {
                F::Log('Connect to ' . $Call['Server'] . ':' . $Call['Port'], LOG_INFO, 'Administrator');
            } else {
                F::Log('No connection to ' . $Call['Server'] . ':' . $Call['Port'], LOG_ERR, 'Administrator');
            }
        }

        if ($Result) {
            switch ($Call['Redis']['Serializer']) {
                case 'JSON':
                    $Redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_JSON);
                    break;

                case 'IGBINARY':
                    $Redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY);
                    break;

                case 'MSGPACK':
                    $Redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_MSGPACK);
                    break;

                case 'PHP':
                    $Redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
                    break;

                case 'NONE':
                    $Redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);
                    break;
            }
            $Redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_JSON);
            return $Redis;
        } else {
            return null;
        }
    });

    setFn('Read', function ($Call) {
        if (is_array($Call['Where']['ID'])) {
            foreach ($Call['Where']['ID'] as &$ID) {
                $ID = $Call['Scope'] . '.' . $ID;
            }

            F::Log('Redis Read: ' . $Call['Where']['ID'], LOG_INFO, 'Administrator');
            return $Call['Link']->mGet($Call['Where']['ID']);
        } else {
            $ID = $Call['Scope'] . '.' . $Call['Where']['ID'];

            F::Log('Redis Read: ' . $ID, LOG_INFO, 'Administrator');

            if (($Result = $Call['Link']->get($ID)) !== false) {
                return [$Result];
            } else {
                return null;
            }
        }
    });

    setFn('Write', function ($Call) {
        $Call['Where']['ID'] = $Call['Scope'] . '.' . $Call['Where']['ID'];

        if (isset($Call['Where'])) {
            if (null === $Call['Data']) {
                F::Log('Redis Delete: ' . $Call['Where']['ID'], LOG_INFO, 'Administrator');
                $Result = $Call['Link']->del($Call['Where']['ID']);
            } else {
                F::Log('Redis Update: ' . $Call['Where']['ID'], LOG_INFO, 'Administrator');
                $Result = $Call['Link']->set($Call['Where']['ID'], $Call['Data'], $Call['TTL']);
            }
        } else {
            $ID = $Call['Scope'] . '.' . $Call['Data']['ID'];
            F::Log('Redis Create: ' . j($Call['Data']), LOG_INFO, 'Administrator');
            $Result = $Call['Link']->set($ID, $Call['Data'], $Call['TTL']);
        }

        if ($Result) {
            return $Call['Data'];
        } else {
            return null;
        }
    });

    setFn('Close', function ($Call) {
        return true;
    });

    setFn('Execute', function ($Call) {
        return true;
    });

    setFn('Exist', function ($Call) {
        return $Call['Link']->exists($Call['Scope'] . $Call['Where']['ID']);
    });

    setFn('Status', function ($Call) {
        return $Call['Link']->info();
    });

    setFn('DBSize', function ($Call) {
        return $Call['Link']->dbSize();
    });
