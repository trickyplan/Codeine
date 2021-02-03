<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call)
    {
        $Replaces = [];

        foreach ($Call['Parsed']['Value'] as $IX => $Cludge)
        {
            if (($Except = F::Dot($Call, 'Parsed.Options.'.$IX.'.except')) === null)
                $Except = [];
            else
            {
                if (mb_strpos($Except, ',') !== false)
                    $Except = explode(',', $Except);
                else
                    $Except = [$Except];
            }

            $Output = [];
            if (isset($Call['Parsed']['Options'][$IX]['key']))
            {
                $Key = $Call['Parsed']['Options'][$IX]['key'];

                if (mb_strpos($Key, ',') !== false)
                    $Key = explode(',', $Key);
                else
                    $Key = [$Key];

                foreach ($Key as $CMatch)
                {
                    $Value = F::Live(F::Dot($Call['Data'], $CMatch));

                    if ($Value === null)
                        ;
                    else
                        break;
                }
            }

            if (isset($Call['Parsed']['Options'][$IX]['call']))
            {
                $Key = $Call['Parsed']['Options'][$IX]['call'];

                if (mb_strpos($Key, ',') !== false)
                    $Key = explode(',', $Key);
                else
                    $Key = [$Key];

                foreach ($Key as $CMatch)
                {
                    $Value = F::Live(F::Dot($Call, $CMatch)); // FIXME?

                    if ($Value === null)
                        ;
                    else
                        break;
                }
            }

            if (is_array($Value))
            {
                foreach ($Value as $CKey => $CValue)
                {
                    if (in_array($CKey, $Except))
                        ;
                    else
                    {
                        $Replace = ['<fe-key/>' => $CKey];

                        if ($CValue === null)
                        {
                            $Replace['<fe-value/>'] = 'null';
                        }
                        else
                        {
                            if (is_array($CValue))
                                foreach ($CValue as $SubKey => $SubValue)
                                    $Replace['<fe-'.$SubKey.'/>'] = $SubValue;
                            else
                                $Replace['<fe-value/>'] = $CValue;

                            if ($CValue === 0)
                                $Replace['<fe-value/>'] = '0';

                            if ($CValue === false)
                                $Replace['<fe-value/>'] = 'false';

                            if ($CValue === true)
                                $Replace['<fe-value/>'] = 'true';
                        }

                        $Output[$CKey] = strtr($Cludge, $Replace);
                    }
                }
            }
            else
                $Output = [];

            $Output = implode('', $Output);

            $Replaces[$Call['Parsed']['Match'][$IX]] = $Output;
        }

        return $Replaces;
     });