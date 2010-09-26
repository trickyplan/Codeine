<?php

function F_Native_Step1 ($Args)
{
    Page::Nest('Application/Gate/Input');
}

function F_Native_Step2 ($Args)
{
    $Agent = new Object('_User');
    
    if ($Agent->Query('=Login='.Server::Arg('Login')))
    {
        Client::$Ticket->Set('MayBe', $Agent->Name);
        $Authorizers = $Agent->Get('Authorizer:Installed', false);

        if (!is_array($Authorizers ))
            $Output = '<l>Auth:BlindAuth</l>';
        else
        {
            $Output = '';
            foreach($Authorizers as $Authorizer)
            {
                $Elements = $Agent->Get('Authorizer:'.$Authorizer, false);
                foreach($Elements as $Element)
                    $Output.= Page::Get('Auth/Input/'.$Authorizer);
            }
        }
        Page::Add($Output);
        return true;
    }
    else
        throw new WTF ('User not found', 404);
}

function F_Native_Step3 ($Args)
{
    if (Client::$Agent->Load(Client::$Ticket->Get('MayBe')))
    {
        $Authorizers = Client::$Agent->Get('Authorizer:Installed', false);
        $Output = '';
        $Decisions = array();

        if (is_array($Authorizers))
            foreach($Authorizers as $Authorizer)
            {
                if (
                    Code::E('Security/Authorizers','Check',
                        array('True'=> Client::$Agent->Get('Authorizer:'.$Authorizer,false),
                              'Challenge'=>Server::Arg($Authorizer)),$Authorizer)
                    )
                        $Decisions[$Authorizer] = true;
                else
                        $Decisions[$Authorizer] = false;
            }

        else
            return Client::$Agent->Name;

        if (in_array(false, $Decisions))
            return false;
        else
            return Client::$Agent->Name;
    } else
        return false;
}