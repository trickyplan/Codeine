<?php

    self::$Object = new Object(self::$Name, self::$ID);

    if (self::$Object->Load(self::$ID))
    {
        self::$Object->Toggle('CartedBy', Client::$UID);
        self::$Object->Save();
        
        switch (self::$Interface)
        {
            case 'ajax':
                Page::Body('<l>Cart:Finished</l>');
            break;

            default:
                Client::Redirect('/web/'.self::$Name.'/Carted');
            break;
        }
    }
    else
        throw new WTF('404',404);
