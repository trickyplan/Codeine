<?php

    function F_H_Parse ($Text)
    {
       return str_replace(Application::$ID, '<span class="HighLight">'.Application::$ID.'</span>', $Text);
    }
    
    