<?php

    use GuzzleHttp\Client;
    use GuzzleHttp\Promise;
    use GuzzleHttp\HandlerStack;
    use GuzzleHttp\Psr7\Request;
    use GuzzleHttp\Handler\StreamHandler;
    use GuzzleHttp\Handler\CurlHandler;
    use GuzzleHttp\Handler\CurlMultiHandler;

    setFn('Read', function ($Call) {
        $Call = F::Hook('Guzzle.BeforeRead', $Call);
        $Call['Response'] = F::Run(null, 'Make Request', F::Dot($Call, 'Guzzle.Request.Method', 'GET'));
        $Call = F::Hook('Guzzle.AfterRead', $Call);
        return $Call['Response'];
    });

    setFn('Write', function ($Call) {
        $Call = F::Hook('Guzzle.BeforeWrite', $Call);
        $Call['Response'] = F::Run(null, 'Make Request', F::Dot($Call, 'Guzzle.Request.Method', 'POST'));
        $Call = F::Hook('Guzzle.AfterWrite', $Call);
        return $Call['Response'];
    });

    setFn('Prepare Request', function ($Call) {
        $Headers = [];
        $Body = '';
        $Parameters = http_build_query($Call['Data'], null, '&');

        if ('GET' == $Call['Guzzle']['Request']['Method']) 
        {
            $Call['Where']['ID'] = rtrim($Call['Where']['ID'], '?') . '?' . $Parameters;
        } else {
            $Body = $Parameters;
        }

        $Request = new Request($Call['Guzzle']['Request']['Method'], $Call['Where']['ID'], $Headers, $Body);
        return $Request;
    });

    setFn('Prepare Client', function ($Call) {
        $ClientOpts = $Call['Guzzle']['Client'];
        $UA = $ClientOpts['User Agents'][array_rand($ClientOpts['User Agents'])];
        $Headers = array_merge($Call['Guzzle']['Request']['Headers'], [
            'User-Agent' => $UA
        ]);

        switch (F::Dot($Call, 'Guzzle.Client.Backend')) {
            case 'Stream':
                $Hanlder = new StreamHandler();
                break;
            case 'CurlMulti':
                $Handler = new CurlMultiHandler();
                break;
            case 'Curl':
            default:
                $Handler = new CurlHandler();
        }

        $Stack = HandlerStack::create($Handler);

        $Client = new Client([
            'timeout' => $ClientOpts['Timeout'],
            'headers' => $Headers,
            'verify' => $ClientOpts['SSL Verify Peer'],
            'handler' => $Stack
        ]);

        return $Client;
    });

    setFn('Make Request', function ($Call) {
        $Client = F::Run(null, 'Prepare Client', $Call);
        if (is_array($Call['Where']['ID'])) {
            $IDs = $Call['Where']['ID'];
            $Data = $Call['Data'];
            foreach ($IDs as $idx => $Call['Where']['ID']) {
                $Call['Data'] = F::Dot($Data, $idx);
                $Request = F::Run(null, 'Prepare Request', $Call);
                $Promises[$idx] = $Client->sendAsync($Request);
            }

            $Responses = Promise\settle($Promises)->wait();
            $Responses = array_map(function ($Response) {
                return $Response['value']->getBody()->getContents();
            }, $Responses);
        } else {
            $Request = F::Run(null, 'Prepare Request', $Call);
            $Responses = [(string)$Client->send($Request)->getBody()];
        }

        return $Responses;
    });
