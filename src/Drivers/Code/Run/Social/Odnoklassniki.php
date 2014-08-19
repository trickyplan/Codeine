<?php

    setFn('Run', function ($Call)
    {
        if (isset($Call['Call']['access_token']))
            ;
        else
            $Call['Call']['access_token'] = F::Run(null, 'Access Token', $Call);

        $Call['Call']['method'] = $Call['Method'];
        $Call['Call']['application_key'] = $Call['Odnoklassniki']['AppID'];
        if (!ksort($Call['Call']))
	{
            F::Log('Sort error', LOG_ERR);
            return null;
        } 
	else
	{
            $requestStr = "";
            foreach($Call['Call'] as $key=>$value){
                $requestStr .= $key . "=" . $value;
            }
            $requestStr .= md5($Call['Call']['access_token'] . self::$app_secret_key);
            return md5($requestStr);
        }


        if (isset($Call['Call']))
            $Query = '?'.urldecode(http_build_query($Call['Call']));
        else
            $Query = '';

        $URL = $Call['Odnoklassniki']['Entry Point'].$Call['Method'].$Query;

        $Result = F::Run('IO', 'Read',
               [
                   'Storage'    => 'Web',
                   'Output Format'   => 'Formats.JSON',
                   'Where'      => $URL
               ]);

        $Result = array_pop($Result);

        if (isset($Call['Return Key']) && F::Dot($Result, $Call['Return Key']))
            $Result = F::Dot($Result, $Call['Return Key']);

        return $Result;
    });

    setFn('Access Token', function ($Call)
    {
        $Result = null;

        if (isset($Call['Session']['User']['Odnoklassniki']['Auth']))
            $Result = $Call['Session']['User']['Odnoklassniki'];
        else
        {
            $Result = $Call['Data']['Odnoklassniki'] =
                F::Run ('Entity', 'Read',
                    [
                        'Entity' => 'User',
                        'Where'  =>
                            [
                                'Odnoklassniki.Auth' =>
                                [
                                    '$exists' => true
                                ]
                            ],
                        'Sort' => ['Modified' => false],
                        'One' => true
                    ])['Odnoklassniki'];

            if (isset($Result['Expire']) > time())
                ;
            else
            {
                $URL = 'http://api.odnoklassniki.ru/oauth/token.do';
                $Result = F::Run('IO', 'Write',
                     [
                         'Storage'  => 'Web',
                         'Where'    => $URL,
                         'Output Format'   => 'Formats.JSON',
                         'Data'     =>
                         [
                             'client_id' => $Call['Odnoklassniki']['AppID'],
                             'client_secret' => $Call['Odnoklassniki']['Secret'],
                             'grant_type' => 'refresh_token',
                             'refresh_token' => $Result['Refresh']
                         ]
                     ]);

// 'Data'     => urldecode(http_build_query($params))

                $Result = array_pop($Result);
		$Result = json_decode($Result, true);
		if (empty($Result['access_token']))
		    $Result = null;
		else
		{

		}
            }
        }
        return $Result;
    });
