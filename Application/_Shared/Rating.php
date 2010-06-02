<?php

if (isset(Client::$Agent))
{
    $BufferRate = '';
    
    $Object = new Object (self::$Name);
    if (!$Object->Load(self::$ID))
            throw new WTF('Not Found Object');
    
    $Owner = new Object ('_User',$Object->Get('Owner'));

    $Raters = self::$AppObject->Get('Rater:Installed', false);

    switch (Server::$REST)
    {
        case 'post':

           $Rater = Server::Get('Rater');

           if (!in_array($Rater,$Raters)) die();

           if (null !== ($Power = Client::$Agent->Get('Rating:'.$Rater)))
                $Power = round($Power/100,2);
           else
                $Power = 1;
                    
           $RatedBy = $Object->Get('RatedBy:'.$Rater, false);

           if (!$RatedBy)
               $RatedBy = array();
           
           if (!in_array(Client::$UID, $RatedBy))
                   {
                       if (Server::Get('Direction') == 'Minus')
                           $Power = -$Power;
                       
                       $Object->Inc('Rating:'.$Rater, $Power);
                       $Owner->Inc('Rating:'.$Rater, ($Power/10));

                       $Object->Add('RatedBy:'.$Rater, Client::$UID);

                       $Object->Save();
                   }

           $Rate = $Object->Get('Rating:'.$Rater);
           Page::Add($Rate);
        break;

        case 'get':
             if ($Raters)
             foreach ($Raters as $Rater)
                {
                    $RatedBy = $Object->Get('RatedBy:'.$Rater, false);

                    if(!is_array($RatedBy))
                        $RatedBy = array();
                    
                    if (!in_array(Client::$UID, $RatedBy))
                        $RateL = 'Raters/Default';
                    else
                        $RateL = 'Raters/Already';

                    if (!($Rate = $Object->Get('Rating:'.$Rater)))
                            $Rate = 0;

                    $BufferRate.= Page::Replace($RateL, array(
                        '<rate/>'=>$Rate,
                        '<rater/>'=>$Rater));
                }
             Page::Add($BufferRate);
        break;
    }
}
else
    Page::Add('Авторизуйтесь!');