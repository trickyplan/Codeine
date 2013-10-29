<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.x
     */

    setFn('Render', function ($Call)
    {
        $XML = new XMLWriter();
        $XML->openMemory();
        $XML->startDocument('1.0', 'UTF-8');
        $XML->setIndent(true);

        $XML->startElement($Call['Output']['Root']);

        if ($Call['Namespace'])
        {
            $XML->startAttribute('xmlns');
                $XML->text($Call['Namespace']);
            $XML->endAttribute();
        }

        if (isset($Call['Attributes']))
            foreach ($Call['Attributes'] as $Namespace)
            {
                $XML->startAttributeNs($Namespace['Prefix'], $Namespace['Key'], null);
                    $XML->text($Namespace['Value']);
                $XML->endAttribute();
            }

        F::Map($Call['Output']['Content'],
           function ($Key, $Value) use ($XML)
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
                       $XML->startElement($Key);

                   if(!is_array($Value))
                   {
                       $XML->text($Value);
                       $XML->endElement();
                   }
               }
           }
       );

        $XML->endElement();
        $XML->endDocument();

        $Call['Output'] = $XML->outputMemory(true);
        
        return $Call;
    });
