<?php

  function F_Ico2_Process($Data)
  {
      $Pockets = array();
// TODO Title у иконок
      if (preg_match_all('@<icon>(.*)<\/icon>@SsUu', $Data, $Matches))
      {
          $Icos = array_unique($Matches[1]);
          sort($Icos);
          
          $IcoID = Code::E('Process/Hash','Get',(implode('',$Icos)));
          $IcoFile = Server::Path('Temp').'Icons/'.$IcoID.'.png';

          if (!file_exists(Root.$IcoFile))
          {
              $IM = imagecreatetruecolor(count($Icos)*16, 16);
              imagealphablending($IM, false);
              imagesavealpha($IM, true);

              foreach ($Icos as $IX => $Ico)
              {
                  $URL = Server::Locate('Icons', $Ico.'.png');

                  if (!$URL)
                      $URL = Server::Locate('Icons', 'Default.png');

                  $IM2 = imagecreatefrompng($URL);
                  imagealphablending($IM2, false);
                  imagesavealpha($IM2, true);

                  imagecopy ($IM, $IM2, $IX*16, 0, 0, 0, 16, 16);
              }
              imagepng($IM, Root.$IcoFile);
          }

          View::Add('.Icon'.$IcoID.' { display: inline; background-image: url("/'.$IcoFile.'"); }', '<iconcss/>');
          
          foreach($Icos as $IX => $Ico)
            $Data = str_replace('<icon>'.$Ico.'</icon>', '<div class="Icon'.$IcoID.'" style="background-position: -'.($IX*16).'px top;"><img src="/Images/s.gif" width=16 height=17 /></div>', $Data);
      }

      return $Data;
  }