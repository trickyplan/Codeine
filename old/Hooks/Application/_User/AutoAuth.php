<?php

    function F_AutoAuth_Hook($Args)
    {
        Client::Attach(Application::$Object->Name);
    }