<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Value'] = 'Грязный текст - это  оскорбление    читателя.Смерть неграмотным!Мучительную... (c)';

        $Call = F::Run('Text.Beautifier', 'Process', $Call);

        $Call['Output']['Content'][]
            = array(
            'Type' => 'Text',
            'Value' => $Call['Value']
        );

        return $Call;
     });