<?php

    $F = function ($In)
        {
            Application::$Object->Query(Application::$ID);

            if (!Access::Check(Application::$Object, Application::$Plugin))
                throw new WTF ('Access Denied', 4030);

            if (!Application::$Object->Load())
                throw new WTF('404: Object Not Found', 4040);

            switch (Application::$Interface)
            {
                case 'ajax':
                    if (!empty(Application::$Mode) and Client::$Level == 2)
                        Client::$Face->Set('Selected:Amplua:'.Application::$Object, Application::$Mode);
                break;
                case 'slice':
                    if (empty(Application::$Mode))
                        {
                            if (Client::$Level == 2 && null !== ($UMode = Client::$Face->Get('Selected:Amplua:'.Application::$Object)))
                                Application::$Mode = $UMode;
                            else
                                Application::$Mode = 'First';
                        }
                break;
            }

            if (empty(Application::$Mode))
                        Application::$Mode = Application::$Plugin;

            View::Add (View::Fusion(
                'Objects/'.Application::$Name.'/'.Application::$Name.'_'.Application::$Mode
                , Application::$Object));

            if (!isset(View::$Slots['Title']['ID']))
                View::$Slots['Title']['ID'] =
                    Application::$Object->GetOr(array('Title', 'Handle', 'Login'));

            if (Application::$Interface == 'web')
                {
                    $LiveURL = "/ajax/".Application::$Name.'/Show/'.Application::$ID;
                    View::$Slots['LiveURL'] = $LiveURL;
                }
        };