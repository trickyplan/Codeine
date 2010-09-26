<?php

if (isset(Client::$Agent))
{   
    if (!self::$Object->Load(self::$ID))
       throw new WTF('Not Found Object');
    
    $Face = new Object (self::$Object->Get('Face'));

    $Raters = self::$AppObject->Get('Rater:Installed', false);

    switch (Server::$REST)
    {
        case 'post':

           $Rater = Server::Arg('Rater');

           //if (!in_array($Rater,$Raters)) die();

           if (null !== ($Power = Client::$Face->Get('Rating:Overall')))
                $Power = round($Power/100,2);
           else
                $Power = 1;
                    
           $RatedBy = self::$Object->Get('RatedBy:'.$Rater, false);

           if (!$RatedBy)
               $RatedBy = array();
           
           //if (!in_array(Client::$UID, $RatedBy))
                   {
                       if (Server::Arg('Direction') == 'Minus')
                           $Power = -$Power;
                       
                       self::$Object->Inc('Rating:'.$Rater, $Power);
                       $Face->Inc('Rating:Overall', $Power);

                       self::$Object->Add('RatedBy:'.$Rater, Client::$UID);

                       self::$Object->Save();
                   }

           $Rate = self::$Object->Get('Rating:'.$Rater);
           Page::Body($Rate);
        break;

        case 'get':
             if ($Raters)
             foreach ($Raters as $Rater)
                {
                    $RatedBy = self::$Object->Get('RatedBy:'.$Rater, false);

                    if(!is_array($RatedBy))
                        $RatedBy = array();
                    
                    if (in_array(Client::$UID, $RatedBy))
                        $RateL = 'Raters/Default';
                    else
                        $RateL = 'Raters/Already';

                    if (!($Rate = self::$Object->Get('Rating:'.$Rater)))
                        $Rate = 0;

                    Page::AddBuffered(Page::Replace($RateL, array(
                        '<scope/>' => self::$Object->Scope,
                        '<name/>'  => self::$Object->Name,
                        '<rate/>'  =>$Rate,
                        '<rater/>' =>$Rater)));
                }
             Page::Flush();
        break;
    }
}
else
    Page::Add('Авторизуйтесь!');