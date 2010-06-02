<?php

function F_Guest_Check($Args)
{
    return !Client::$Authorized;
}