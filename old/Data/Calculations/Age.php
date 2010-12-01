<?php

    function F_Age_Calculate($Data)
    {
        return time()-$this->Get('CreatedOn');
    }