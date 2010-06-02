<?php

  function F_MediaTag2_Process($Data)
  {
      $JSSIncludes = array();
      $CSSIncludes = array();
      
      if (preg_match_all('@<media>(.*)</media>@SsUu', $Data, $Matches))
           {
            foreach($Matches[0] as $IX => $Match)
                {
                    if (mb_substr($Matches[1][$IX],0,1) == '~')
                    {
                        $Matches[1][$IX] = mb_substr($Matches[1][$IX],1);
                        
                        if (mb_ereg('\.css',$Matches[1][$IX]))
                            $CSSIncludes[EngineShared.'CSS/'.$Matches[1][$IX]] = filemtime(EngineShared.'CSS/'.$Matches[1][$IX]);
                        elseif (mb_ereg('\.js', $Matches[1][$IX]))
                            $JSSIncludes[EngineShared.'JS/'.$Matches[1][$IX]] = filemtime(EngineShared.'JS/'.$Matches[1][$IX]);
                    }
                    else
                    {
                        if (mb_ereg('\.css',$Matches[1][$IX]))
                            $CSSIncludes[Root.'CSS/'.$Matches[1][$IX]] = filemtime(Root.'CSS/'.$Matches[1][$IX]);
                        elseif (mb_ereg('\.js', $Matches[1][$IX]))
                            $JSSIncludes[Root.'JS/'.$Matches[1][$IX]] = filemtime(Root.'JS/'.$Matches[1][$IX]);
                    }

                    $Data = str_replace($Match,'', $Data);
                }
           }


      $CSI = Page::$CSSIncludes;

      if (is_array($CSI) and !empty($CSI) or !empty($CSSIncludes))
      {
          foreach($CSI as $CSS)
                $CSSIncludes[$CSS] = filemtime($CSS);

          $HID = md5(implode(',',$CSSIncludes).implode(',',array_keys($CSSIncludes)));
          $HCSSFile = Data.'_CSS/'.$HID.'.css';

          if (!file_exists(Root.$HCSSFile))
          {
              $SStr = '';

              foreach($CSSIncludes as $Key => $Value)
                  if (file_exists($Key))
                        $SStr.= file_get_contents($Key);
                  else
                    Log::Error('CSS '.$Key.' not found');

              file_put_contents(Root.$HCSSFile, Code::E('Output/Minify', 'CSSMinify', $SStr));
          }

          $Data = str_replace('<place>CSS</place>',
                  '<link type="text/css" rel="stylesheet" href="/'.$HCSSFile.'" />', $Data);
      }
      else
          $Data = str_replace('<place>CSS</place>','', $Data);

      $JSI = Page::$JSIncludes;
      
      if (is_array($JSI) and !empty($JSI) or !empty($JSSIncludes))
      {
          foreach($JSI as $JS)
              if (file_exists($JS))
                $JSSIncludes[$JS] = filemtime($JS);
              else
                  Log::Error($JS);

          $HID = md5(implode(',',$JSSIncludes).implode(',',array_keys($JSSIncludes)));
          $HJSFile = Data.'_JS/'.$HID.'.js';

          if (true or !file_exists(Root.$HJSFile))
          {
              $SStr = '';
              foreach($JSSIncludes as $Key => $Value)
                if (file_exists($Key))
                  $SStr.= file_get_contents($Key);
                else
                  Log::Error('JS '.$Key.' not found');

              file_put_contents(Root.$HJSFile, Code::E('Output/Minify', 'JSMinify', $SStr));
          }

          $Data = str_replace('<place>JS</place>',
                  '<script type="text/javascript" src="/'.$HJSFile.'" ></script>', $Data);
      }
      else
          $Data = str_replace('<place>JS</place>','', $Data);

      return $Data;
  }