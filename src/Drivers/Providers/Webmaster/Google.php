<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Auth', function ($Call)
    {
        if ($Call['ID'] == $Call['Google']['Webmaster']['ID'])
            $Call['Output']['Content'][] = $Call['Google']['Webmaster']['Prefix']
                .$Call['Google']['Webmaster']['ID']
                .$Call['Google']['Webmaster']['Postfix'];
        else
            $Call = F::Apply('Error.Page', 'Do', $Call, ['Code' => 404]);

        return $Call;
    });