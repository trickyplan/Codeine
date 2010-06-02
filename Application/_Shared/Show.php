<?php

self::$Object->Query(self::$ID);

if (!Access::Check(self::$Object, self::$Plugin))
    throw new WTF ('Access Denied', 4030);

if (!self::$Object->Load())
    throw new WTF('404: Object Not Found', 4040);

if (self::$Interface == 'ajax' && !empty(self::$Mode))
    {
        if (Client::$Authorized)
            Client::$Agent->Set('Selected:Amplua:'.self::$Object, self::$Mode);
    }
elseif (!isset(self::$Mode))
            self::$Mode = self::$Plugin;

    if (self::$Interface == 'slice')
        if (!isset(self::$Mode) or empty(self::$Mode))
        {
            if (Client::$Authorized)
            {
                if (null !== ($UMode = Client::$Agent->Get('Selected:Amplua:'.self::$Object)))
                    self::$Mode = $UMode;
                else
                    self::$Mode = 'First';
            }
            else
                self::$Mode = 'First';
        }

Page::Add (Page::Fusion(
    'Objects/'.self::$Name.'/'.self::$Name.'_'.self::$Mode
    , self::$Object));

if (!isset(Page::$Slots['Title']['ID']))
    Page::$Slots['Title']['ID'] =
        self::$Object->Get('Title');

if (self::$Interface == 'web')
    {
        $LiveURL = "/ajax/".self::$Name.'/Show/'.self::$ID;
        Page::$Slots['LiveURL'] = $LiveURL;
    }