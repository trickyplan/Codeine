<?php

    setFn('Run', function ($Call)
    {
        if (isset($Call['Call']['access_token']))
    	{
            $Call['access_token'] = $Call['Call']['access_token'];
            unset($Call['Call']['access_token']);
    	}
        else
            $Call['access_token'] = F::Run(null, 'Access Token', $Call);

        $Call['Call']['method'] = $Call['Method'];
        $Call['Call']['application_key'] = $Call['Odnoklassniki']['Public'];
    	$Call['Call']['sig'] = F::Run(null, 'Calc Signature', $Call);
    	$Call['Call']['access_token'] = $Call['access_token'];

    	$Result = F::Run('IO', 'Read',
             [
		         'Storage'  => 'Web',
		         'Where'    => $Call['Odnoklassniki']['Entry Point'].'?'.http_build_query($Call['Call']),
		         'Output Format'   => 'Formats.JSON'
	         ])[0];
	    $Result = jd($Result, true);

        if (isset($Result['error_code']))
        {
            // http://apiok.ru/wiki/pages/viewpage.action?pageId=77824003
            if (isset($Call['Odnoklassniki']['Error']['Codes'][$Result['error_code']]))
                F::Hook('Odnoklassniki.'.$Call['Odnoklassniki']['Error']['Codes'][$Result['error_code']], $Call);

            F::Log($Result['error_code'].': '.$Result['error_msg'], LOG_WARNING);
            $Result = null;
        }
        else
        {
            if (isset($Call['Return Key']) && F::Dot($Result, $Call['Return Key']))
                $Result = F::Dot($Result, $Call['Return Key']);
        }
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
                                'Odnoklassniki.Active' => true,
                            ],
                        'Sort' => ['Modified' => false],
                        'One' => true
                    ])['Odnoklassniki'];

            if (isset($Result['Expire']) > time())
                ;
            else
            {
                $oldAuth = $Result['Auth'];
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
			// 'Data'     => urldecode(http_build_query($params))
                     ]);

                $Result = array_pop($Result);
		$Result = jd($Result, true);
		if (empty($Result['access_token']))
		    $Result = null;
		else
		{
		    F::Run ('Entity', 'Update',
		        [
		            'Entity' => 'User',
		            'Where'  =>
		            [
		                'Odnoklassniki.Auth' => $oldAuth
		            ],
		            'Data' => 
		            [
		                'Odnoklassniki.Auth' => $Result['Auth']
		            ],
		            'One'  => true
		        ]);
		}
            }
        }
        return $Result;
    });

    setFn('Calc Signature', function ($Call)
    {
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
            $requestStr .= md5($Call['access_token'] . $Call['Odnoklassniki']['Secret']);
            return md5($requestStr);
        }
    });


    setFn('WidgetMediatopicPost.GetURL', function ($Call)
    {
        $Call['Attachment'] = base64_encode(json_encode([
            'media' => $Call['Attachment']
        ]));
        $Call['Signature'] = md5('st.attachment='.$Call['Attachment'].$Call['Odnoklassniki']['Secret']);

        $URL = 'http://connect.ok.ru/dk?st.cmd=WidgetMediatopicPost&st.app='.$Call['Odnoklassniki']['AppID'].'&st.attachment='.$Call['Attachment'].'&st.signature='.$Call['Signature'].'&st.utext='.$Call['Text'];
        return $URL;
    });
