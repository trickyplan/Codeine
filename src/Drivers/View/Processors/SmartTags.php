<?php

    /* Codeine
     * @author BreathLess
     * @description Apriori Parser 
     * @package Codeine
     * @version 7.0
     */

     self::setFn('Process', function ($Call)
     {
         $Document = new DOMDocument();
         $Document->loadHTML($Call['Output']);

         foreach ($Call['SmartTags'] as $SmartTag)
         {
             $Tag = strtolower($SmartTag);
             $Tags = $Document->getElementsByTagName($Tag);

             foreach ($Tags as $Tag)
             {
                 $NewNode = $Document->createTextNode(F::Run('View.Processors.Parslets.' . $SmartTag, 'Parse', $Call,
                     array_merge($Tag->attributes,
                         array ('Value' => $Tag->nodeValue))));

                 $Document->replaceChild(
                     $NewNode
                     ,$Tag);
             }
         }

         $Call['Output'] = $Document->saveHTML();

         return $Call;
     });