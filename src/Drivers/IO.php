<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  New IO Date
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call) {
        if (isset($Call['Storage'])) {
            if (isset($Call['Storages'][$Call['Storage']])) {
                $Call = F::Merge($Call, $Call['Storages'][$Call['Storage']]);

                if (($Call['Link'] = F::Get('Storage.' . $Call['Storage'])) === null) {
                    F::Counter('IO.' . $Call['Storage'] . '.Opens', 1);

                    if (
                        is_string($Call['Storages'][$Call['Storage']])
                        && isset($Call['Storages'][$Call['Storages'][$Call['Storage']]])
                    ) {
                        $Call['Storages'][$Call['Storage']] = $Call['Storages'][$Call['Storages'][$Call['Storage']]];
                    }

                    $Call['Link'] = F::Set('Storage.' . $Call['Storage'], F::Run($Call['Driver'], 'Open', $Call));
                    F::Log(
                        'Storage *' . $Call['Storage'] . '* (*' . $Call['Driver'] . '*) connected',
                        LOG_INFO,
                        'Administrator'
                    );
                } else {
                    F::Log(
                        'Storage *' . $Call['Storage'] . '* (*' . $Call['Driver'] . '*) cached',
                        LOG_DEBUG,
                        'Administrator'
                    );
                }
            } else {
                F::Log('Storage *' . $Call['Storage'] . '*' . ' not found', LOG_CRIT, 'Administrator');
                $Call['Link'] = null;
            }
        } else {
            F::Log('IO.Open.Storage.Undefined', LOG_CRIT, 'Administrator');
            $Call['Link'] = null;
        }

        return $Call;
    });

    setFn('Read', function ($Call) {
        $Result = null;

        if (isset($Call['Storage'])) {
            if (isset($Call['Result'])) {
                unset($Call['Result']);
            }

            F::Counter('IO.' . $Call['Storage'] . '.Reads', 1);

            $IOID = $Call['Storage'];

            if (isset($Call['Scope'])) {
                $IOID .= '.' . $Call['Scope'];
            }

            if (isset($Call['Where'])) {
                $IOID .= '(' . j($Call['Where'], false) . ')';
            }

            F::Start('IO:' . $IOID);

            $Call = F::Apply('IO', 'Open', $Call);

            if ($Call['Link'] === null) {
                return null;
            }

            $Call = F::Hook('beforeIORead', $Call);

            if (isset($Call['Result'])) {
            } else {
                // Если в Where простая переменная - это ID.
                if (isset($Call['Where']) && is_scalar($Call['Where'])) {
                    $Call['Where'] = ['ID' => $Call['Where']];
                }

                if (isset($Call['IO TTL'])) {
                    $Call['RTTL'] = $Call['IO TTL'];
                }

                if (isset($Call['Driver'])) {
                    $Call['Result'] = F::Run($Call['Driver'], 'Read', $Call);
                } else {
                    $Call['Result'] = null;
                }

                if (isset($Call['Format']) && is_array($Call['Result'])) {
                    foreach ($Call['Result'] as &$Element) {
                        $Element = F::Run($Call['Format'], null, $Call, ['Value' => $Element]);
                    }
                }

                $Call = F::Hook('afterIORead', $Call);
            }


            if (isset($Call['Return Key']) && $Call['Result'][$Call['Return Key']]) {
                $Result = $Call['Result'][$Call['Return Key']];
            } else {
                $Result = $Call['Result'];
            }

            if (isset($Call['IO One']) && $Call['IO One'] && is_array($Result)) {
                $Result = array_pop($Result);
                unset($Call['IO One']);
            }

            if (isset($Call['Get Last']) && $Call['Get Last'] && is_array($Result)) {
                $Result = array_shift($Result);
                unset($Call['Get Last']);
            }

            if (isset($Call['Get First']) && $Call['Get First'] && is_array($Result)) {
                $Result = array_pop($Result);
                unset($Call['Get First']);
            }

            F::Stop('IO:' . $IOID);
        } else {
            F::Log('IO.Read.Storage.Undefined', LOG_CRIT);
        }

        return $Result;
    });

    setFn('Write', function ($Call) {
        if (isset($Call['Storage'])) {
            $Call = F::Apply('IO', 'Open', $Call);

            if ($Call['Link'] === null) {
                return null;
            }

            F::Counter('IO.' . $Call['Storage'] . '.Writes', 1);

            $Call = F::Hook('beforeIOWrite', $Call);

            if (F::Dot($Call, 'IO.Skip')) {
                F::Log('Write skipped', LOG_INFO, 'Administrator');
            } else {
                // Если в Where простая переменная - это ID.
                if (isset($Call['Where']) && is_scalar($Call['Where'])) {
                    $Call['Where'] = ['ID' => $Call['Where']];
                }

                if (isset($Call['Format'])) {
                    if (isset($Call['Data']) && is_array($Call['Data']) && isset($Call['Data']['ID'])) {
                        $Call['ID'] = $Call['Data']['ID'];
                    }

                    $Call['Data'] = F::Run($Call['Format'], null, $Call, ['Value!' => $Call['Data']]);
                }

                if (isset($Call['Driver'])) {
                    $Call['Data'] = F::Run($Call['Driver'], null, $Call);
                } else {
                    F::Log('IO Driver not set.', LOG_CRIT);
                }
            }

            $Call = F::Hook('afterIOWrite', $Call);

            if (isset($Call['Output Format'])) {
                foreach ($Call['Data'] as $Key => $Data) {
                    $Call['Data'][$Key] = F::Run($Call['Output Format'], 'Read', $Call, ['Value!' => $Data]);
                }
            }

            if (isset($Call['IO One']) && $Call['IO One'] && is_array($Call['Data'])) {
                $Call['Data'] = array_pop($Call['Data']);
                unset($Call['IO One']);
            }

            return $Call['Data'];
        } else {
            F::Log('IO Null Storage: ', LOG_ERR);
            return null;
        }
    });

    setFn('Close', function ($Call) {
        if (isset($Call['Storage'])) {
            $Call = F::Apply('IO', 'Open', $Call);

            if ($Call['Link'] === null) {
                return null;
            }

            return F::Run($Call['Driver'], 'Close', $Call);
        } else {
            F::Log('IO Null Storage: ', LOG_ERR);
            return null;
        }
    });

    setFn('Execute', function ($Call) {
        if (isset($Call['Storage'])) {
            F::Counter('IO.' . $Call['Storage'] . '.Executes', 1);
            $Call = F::Apply('IO', 'Open', $Call);

            if ($Call['Link'] === null) {
                return null;
            }

            if (isset($Call['Where']) && is_scalar($Call['Where'])) {
                $Call['Where'] = ['ID' => $Call['Where']];
            }

            return F::Run($Call['Driver'], $Call['Execute'], $Call);
        } else {
            F::Log('IO Null Storage: ', LOG_ERR);
            return null;
        }
    });

    setFn('Commit', function ($Call) {
        if (isset($Call['Storage'])) {
            $Call = F::Apply('IO', 'Open', $Call);

            if ($Call['Link'] === null) {
                return null;
            }

            return F::Run($Call['Driver'], null, $Call);
        } else {
            F::Log('IO Null Storage: ', LOG_ERR);
            return null;
        }
    });

    setFn('Rollback', function ($Call) {
        if (isset($Call['Storage'])) {
            $Call = F::Apply('IO', 'Open', $Call);

            if ($Call['Link'] === null) {
                return null;
            }

            return F::Run($Call['Driver'], null, $Call);
        } else {
            F::Log('IO Null Storage: ', LOG_ERR);
            return null;
        }
    });

    setFn('Shutdown', function ($Call) {
        foreach ($Call['Storages'] as $StorageName => $Storage) {
            if (null !== F::Get($StorageName)) {
                F::Run('IO', 'Close', $Call, ['Storage' => $StorageName]);
            }
        }

        return $Call;
    });
