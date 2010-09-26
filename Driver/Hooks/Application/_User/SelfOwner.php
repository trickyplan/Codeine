<?php

    function F_SelfOwner_Hook($Args)
    {
        Application::$Object->Set('Owner', (string) Application::$Object);
        Application::$Object->Save();
    }