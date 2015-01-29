<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        $XML = new XMLWriter();
        $XML->openMemory();
        $XML->startDocument('1.0', 'UTF-8');
        $XML->setIndent(true);
        $XML->startElement('methodCall');
            $XML->startElement('methodName');
                $XML->text($Call['Method']);
            $XML->endElement();

            $Root = '';

            $XML->startElement('params');
                F::Map($Call['Call'],
                   function ($Key, $Value) use ($XML, &$Root)
                   {
                       if (substr($Key, 0, 1) == '@')
                       {
                           $XML->startAttribute(substr($Key, 1));
                           $XML->text($Value);
                           $XML->endAttribute();
                       }
                       else
                       {
                           if (is_numeric($Key))
                           {
                               if ($Key > 0) // FIXME Костыль!
                                   $XML->endElement();
                           }
                           else
                           {
                               $XML->startElement($Key);
                               $Root = $Key;
                           }

                           if (is_array($Value))
                               ;
                           else
                           {
                               $XML->text($Value);
                               $XML->endElement();
                           }

                       }
                   }
               );

            $XML->endElement();

        $XML->endDocument();

        $Query = $XML->outputMemory(true);

        $Result = F::Run('IO', 'Write',
         [
             'Storage' => 'Web',
             'Where' =>
             [
                 'ID' => $Call['Service']
             ],
             'Data' => $Query
         ]);

        $Result = array_pop($Result);
        $Result = jd(j(simplexml_load_string($Result)), true);
        return $Result['params'];
    });