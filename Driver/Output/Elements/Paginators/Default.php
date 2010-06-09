<?php

  function F_Default_Paginate($Args)
  {
      $Output = '';
      $PageSize = Application::GetTune('PageSize',10);
      
      $PageCount = ceil($Args['Objects']/$PageSize);

      if ($PageCount <= 1)
          return '';
      
      if (!isset(Application::$In['Page']))
          $Page = 1;
      else
          $Page = Application::$In['Page'];

      if (($Start = $Page - 3) < 1) $Start = 1;
      if (($End = $Page + 3) > $PageCount) $End = $PageCount;

      $From = $PageSize*($Page-1)+1;
      
      if ($From <= 0)
          $From = 1;

      $To = $PageSize*$Page;
      if ($To > $Args['Objects'])
          $To = $Args['Objects'];

      if ($PageCount>1)
        for ($a = $Start; $a <= $End; $a++)
            if ($a == $Page)
                $Output.= '<span class="PageNumber_Selected">'.$a.'</span>';
            else
                $Output.= '<span class="PageNumber"><a class="'.Application::$Interface.'Link" href="'.$Args['URL'].$a.'">'.$a.'</a></span>';

      if ($Page >= 5)
          $Output = '<span class="PageNumber"> <a class="'.Application::$Interface.'Link" href="'.$Args['URL'].'1">1</a></span>&nbsp; &hellip;'. $Output;

      if ($Page != $PageCount and $PageCount>6)
          $Output.= '&nbsp; &hellip; <span class="PageNumber"> <a  class="'.Application::$Interface.'Link"href="'.$Args['URL'].$PageCount.'">'.$PageCount.'</a></span>';

      // FIXME Templatize

      $Output.= '&nbsp; <small> <l>Paginator:Default:Showed</l> <l>Paginator:Default:From</l> '.$From.' <l>Paginator:Default:To</l> '.$To.' <l>Paginator:Default:Total</l> '.$Args['Objects'].'</small>';
      return $Output;
  }