<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $Call['DOM'] = new DOMDocument('1.0', 'UTF-8');
        $Call['DOM']->loadHTML('<?xml encoding="UTF-8">'.$Call['Output']);
        
        foreach ($Call['View']['HTML']['CodeineTags']['Tags'] as $Tag)
        {
            $Nodes = $Call['DOM']->getElementsByTagName('codeine-'.strtolower($Tag));
           
            if ($Nodes->length == 0)
                ;
            else
            {
                foreach ($Nodes as $Node)
                {
                    $Inner = $Call['DOM']->saveHTML($Node);
                    
                    $Attributes = [];
                    foreach ($Node->attributes as $Key => $Value)
                        $Attributes[$Key] = $Value->nodeValue;
                   
                    $Replace = F::Run('View.HTML.CodeineTags.' . $Tag, null, $Call,
                        [
                            'Node'       => $Node,
                            'Inner'      => $Inner,
                            'Attributes' => $Attributes
                        ] );
                   
                    $Fragment = $Call['DOM']->createDocumentFragment();
                    $Fragment->appendXML($Replace);
                    
                    $Node->parentNode->replaceChild($Fragment, $Node);
                }
            }
        }
        $Call['Output'] = $Call['DOM']->saveHTML();
        
        return $Call;
    });