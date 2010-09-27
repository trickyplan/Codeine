<?php

self::$Collection->Query(self::$ID);

if (self::$Collection->Length > 1)
{
    self::$Mode = 'List';
    include Engine.Apps.'_Shared/List.php';
}
else
{
    self::$Object->Query(self::$ID);

    if (!Access::Check(self::$Object, self::$Plugin))
        throw new WTF ('Access Denied', 4030);

    if (!self::$Object->Load())
        throw new WTF('404: Object Not Found', 4040);

    switch (self::$Interface)
    {
        case 'ajax':
            if (!empty(self::$Mode) and Client::$Level == 2)
                Client::$Face->Set('Selected:Amplua:'.self::$Object, self::$Mode);
        break;
        case 'slice':
            if (empty(self::$Mode))
                {
                    if (Client::$Level == 2 && null !== ($UMode = Client::$Face->Get('Selected:Amplua:'.self::$Object)))
                        self::$Mode = $UMode;
                    else
                        self::$Mode = 'First';
                }
        break;
    }

    if (empty(self::$Mode))
                self::$Mode = self::$Plugin;

    Page::Add (Page::Fusion(
        'Objects/'.self::$Name.'/'.self::$Name.'_'.self::$Mode
        , self::$Object));

    if (!isset(Page::$Slots['Title']['ID']))
        Page::$Slots['Title']['ID'] =
            self::$Object->GetOr(array('Title', 'Handle', 'Login'));

    if (self::$Interface == 'web')
        {
            $LiveURL = "/ajax/".self::$Name.'/Show/'.self::$ID;
            Page::$Slots['LiveURL'] = $LiveURL;
        }
}