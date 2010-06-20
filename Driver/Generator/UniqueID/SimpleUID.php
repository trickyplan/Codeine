<?php

function F_SimpleUID_Generate($Prefix)
    {
        return md5(time());
    }
