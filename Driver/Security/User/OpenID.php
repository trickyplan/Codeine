<?php

function F_OpenID_Step2 ($Args)
{
    $m = array();
    $OpenIDServerPage = file_get_contents ('http://'.$Args['Login']);

    $ServerURL = "";
    $DelegateID = "";

    $OpenIDServerPage = preg_replace('/<body\b.*/i', '', $OpenIDServerPage);

    if(preg_match('/<link\b[^>]+?\brel=([\'"])openid\.server\1.*?>/i', $OpenIDServerPage, $m))
    {
        $link = $m[0];
        if(preg_match('/\bhref=([\'"])(.*?)\1/i', $link, $m))
                $ServerURL = html_entity_decode($m[2]);
    }

    if(!$ServerURL)
        return Log::Error("openid.server не найден");

    if(!preg_match('{^https?://}i', $ServerURL))
        return Log::Error("некорректный url openid.server");

    if(preg_match('/<link\b[^>]+?\brel=([\'"])openid\.delegate\1.*?>/i', $OpenIDServerPage, $m))
    {
        $link = $m[0];
        if(preg_match('/\bhref=([\'"])(.*?)\1/i', $link, $m)) $DelegateID = html_entity_decode($m[2]);
    }

    if(!$DelegateID)
        $DelegateID = $ServerURL;

    $Params = array(
        "openid.mode"       => 'checkid_setup',
        "openid.identity"   => 'http://'.$Args['Login'],
        "openid.return_to"  => Host.'web/Gate/Step3',
        "openid.trust_root" => Host);

    $ParamsStr = array();

    foreach($Params as $Key => $Value)
        $ParamsStr[] = urlencode($Key).'='.urlencode($Value);

    Client::$Ticket->Set('OpenIDServer', $ServerURL);
    Client::$Ticket->Set('MayBe',$Args['Login']);

    Client::Redirect($ServerURL.'?'.implode('&',$ParamsStr));
}

function F_OpenID_Step3($Args)
{
    if (null !== Server::Get('openid_mode'))
    {
        switch (Server::Get('openid_mode'))
        {
            case 'cancel':

            break;

            case 'error':

            break;

            case 'id_res':
                $Params = array(
                    'openid.mode'           => 'check_authentication',
                    'openid.assoc_handle'   => Server::Get('openid_assoc_handle'),
                    'openid.sig'            => Server::Get('openid_sig'),
                    'openid.signed'         => Server::Get('openid_signed'),
            );
            
            $Data = Server::Data();

            foreach($Data as $Key => $Value)
                if (mb_substr($Key, 0, 7) ==  'openid_')
                        {
                            $Key = str_replace('openid_', 'openid.', $Key);
                            if (!isset($Params[$Key]))
                                $Params[$Key] = $Value;
                        }
            Data::Mount('HTTP');
            $Params['I'] = Client::$Ticket->Get('OpenIDServer');
            $Result = Data::Create('HTTP', $Params); // Create == POST

            // ответ придёт в виде строк вида key:val\n
            // разбираем его...
            $Vals = Code::E('Input/Importers','Import', $Result, 'ColonSeparatedArray');

            // нас интересует только один параметр ответа — lifetime
            if(isset($Vals["lifetime"])&&(int)$Vals["lifetime"] > 0 or isset($Vals["is_valid"])&&$Vals["is_valid"] == "true")
                return Client::$Ticket->Get('MayBe');
            else
                {
                    Page::Nest('Application/Gate/OpenID/Failed');
                    return false;
                }
            break;

        }
    }
}