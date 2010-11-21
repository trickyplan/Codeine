<?php

    class FluentData
    {
        public function __get()
        {
            
        }
    }

    $FluentData = new FluentData();
    $FluentData->User->Read('I=1');

    // TODO Tests