<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $Imploder = function ($Node) {
                            return implode(array_map([$Node->ownerDocument, "saveHTML"],iterator_to_array($Node->childNodes)));};
        $Call['DOM'] = new DOMDocument();
        $Call['DOM']->loadHTML('<?xml encoding="UTF-8">'.$Call['Output']);
        $Call['DOM']->validateOnParse = false;
        $Call['DOM']->formatOutput = true;
        
        do
        {
            $FoundPassNodes = 0;
            // Find
            foreach ($Call['View']['HTML']['CodeineTags']['Enabled'] as $Tag)
            {
                $Nodes = $Call['DOM']->getElementsByTagName(strtolower($Tag));
                
                $FoundTagNodes = $Nodes->length;
               
                $FoundPassNodes += $FoundTagNodes;
                
                if ($Nodes->length == 0)
                    ;
                else
                {
                    foreach ($Nodes as $IX => $Node)
                    {
                        $Matched[$Tag]['Node'][$IX] = $Node;
                        $Matched[$Tag]['Match'][$IX] = $Call['DOM']->saveHTML($Node);
                        $Matched[$Tag]['Value'][$IX] = $Imploder ($Node);
        
                        foreach ($Node->attributes as $Key => $Value)
                            $Matched[$Tag]['Options'][$IX][$Key] = $Value->nodeValue;
                    }
                    $Matched[$Tag]['Replace'] =
                        F::Apply('View.HTML.Parslets.' . $Tag, 'Parse', $Call,
                            [
                                'Parsed!' => $Matched[$Tag]
                            ]);
                }
            }
            
            // Replace
            if (empty($Matched))
                ;
            else
                foreach ($Matched as $Tag => $TagMatched)
                {
                    foreach ($TagMatched['Node'] as $IX => $CNode)
                    {
                        $Fragment = $Call['DOM']->createDocumentFragment();
                        $Fragment->appendXML($TagMatched['Replace'][$IX]);
        
                        if ($CNode->parentNode === null)
                            ;
                        else
                            $CNode->parentNode->replaceChild($Fragment, $CNode);
                    }
                }
        } while ($FoundPassNodes > 0);
            
        $Call['Output'] = html_entity_decode($Call['DOM']->saveHTML());
        
        return $Call;
    });