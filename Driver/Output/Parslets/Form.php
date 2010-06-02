<?php

  function F_Form_Parse($Input)
  {
      Form::Model(json_decode($Input));
      return Form::Render();
  }