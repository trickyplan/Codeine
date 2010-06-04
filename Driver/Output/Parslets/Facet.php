<?php

function F_Facet_Parse ($Input)
{
    $Input = json_decode($Input, true);
    $Object = new Object ($Input['Object']);
    return Page::Fusion('Objects/'.$Object->Scope.'/Facets/'.$Input['Layout'], $Object);
}

    