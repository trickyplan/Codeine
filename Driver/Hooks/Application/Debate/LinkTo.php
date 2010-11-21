<?php

    function F_LinkTo_Hook($Args)
    {
        $ObjectForDebate = new Object(Application::$ID);
        $ObjectForDebate->Load();

        Application::$Object->Add('WWID', (string) $ObjectForDebate);
        Application::$Object->Add('Owner',$ObjectForDebate->Get('Owner'));
        Application::$Object->Save();
    }