<?php

function F_vx7_Generate($Args)
{
    $Length = 4;
    $Answer = Code::E('Generator/Keyword','Generate',array('Length' => $Length),'English');
    $Fonts = Code::E('System/FS','Listing', Engine.'_Shared/Fonts/');
    $UID = sha1($Answer);

    $Width = 30*$Length;
    $Height = 50;

    $im = imagecreate($Width, $Height);

    $Black = imagecolorallocate($im, 26,26,26);
    $Gray  = imagecolorallocate($im, 52,52,52);
    $White = imagecolorallocate($im, 226,237,228);

    imagefill($im, 0,0, $White);

    $SF = sizeof($Fonts)-1;
        
    for ($Length = 0; $Length < mb_strlen($Answer); $Length++)
    {
        $CurFont = $Fonts[mt_rand(0,$SF)];
        imagettftext($im, (26 + mt_rand(-2,0)),(0 + mt_rand(-10,10)), (10+$Length*26), 35, $Black, Engine.'_Shared/Fonts/'.$CurFont, $Answer[$Length]);
    }

    $PoNoise = ($Width*$Height)/10;

    for ($Noise=0; $Noise<$PoNoise; $Noise++)
        {
            $X = mt_rand(0,$Width-1);
            $Y = mt_rand(0,$Height-1);
            
            if (imagecolorat($im, $X, $Y) == $White)
                imagesetpixel($im, $X, $Y, $Gray);
            else
                imagesetpixel($im, $X, $Y, $White);
        }

    // for ($Noise=0; $Noise<50; $Noise++)
        //imageline($im, mt_rand(0,120),mt_rand(0,60),mt_rand(0,120),mt_rand(0,60), $Black);

    for ($Noise=0; $Noise<10; $Noise++)
        imageline($im, mt_rand(0,$Width),mt_rand(0,$Height),mt_rand(0,$Width),mt_rand(0,$Height), $White);
    
    imagepng($im, Root.Temp.'_CAPTCHA/'.$UID.'.png');

    Client::$Ticket->Set('CAPTCHA:vx7:Answer', $Answer);

    return '<img src="/Temp/_CAPTCHA/'.$UID.'.png" style="vertical-align: top;"/> <input class="Text_CAPTCHA" name="CAPTCHA" type="text" />';
}

function F_vx7_Check($Args)
{
    $Answer = Client::$Ticket->Get('CAPTCHA:vx7:Answer');
    unlink (Root.Data.'_CAPTCHA/'.sha1($Answer).'.png');
    if ($Answer == Server::Get('CAPTCHA'))
        return true;
    else
        return false;
}