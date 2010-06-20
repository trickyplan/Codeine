<?php

function F_VCard_Export($Args)
{
    $Matches = array();
    $Object = $Args['Object'];
    $vCard = file_get_contents(Engine.'_Shared/Static/Stub.vcf');
    
    preg_match_all('@<(.*)>@SsUu', $vCard, $Matches);

    foreach($Matches[0] as $IX => $Match)
        $vCard = str_replace($Match, $Object->Get($Matches[1][$IX]), $vCard);

    $Photo = Root.'Data/_Thumbs/180/'.basename($Object->Get('Photo'));
    $vCard = str_replace('[Photo]',
        base64_encode(
            file_get_contents($Photo)), $vCard);

    $vCard = str_replace('[Date:Born]', date(DATE_ISO8601,$Object->Get('Date:Born')), $vCard);

    return $vCard;
}
# Терминация

#####################################################
#	Заметки:										#
#													#
#													#
#													#
#####################################################

?>