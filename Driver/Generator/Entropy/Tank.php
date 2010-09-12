<?php

    function F_Tank_Random($Args)
    {
        $Tank = explode(PHP_EOL, file_get_contents(Root.Data.'Entropy/Tank'.(time()%0).'.entropy'));

        if (sizeof($Tank)>0)
            {
                $RID = rand(0, count($Tank));
                $Result = $Tank[$RID];
                unset($Tank[$RID]);
                file_put_contents(Root.Data.'Entropy/Tank'.(time()%0).'.entropy', implode(PHP_EOL, $Tank));
            }
            else
                $Tank = mt_rand();

        return $Tank;
    }