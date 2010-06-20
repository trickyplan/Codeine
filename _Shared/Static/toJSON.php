<?php

  $Lines = explode("\n", file_get_contents($_SERVER['argv'][1]));
  file_put_contents($_SERVER['argv'][1], json_encode($Lines));