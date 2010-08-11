<?php

function F_SHA1_Generate($Object)
    {
        return sha1(serialize($Object->Data()));
    }