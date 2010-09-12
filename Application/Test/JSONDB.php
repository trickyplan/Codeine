<?php

    $ID = uniqid();
    Data::Create('JSONDB', array('I'=>$ID,'Data'=>array('key'=>rand())));
    echo Data::Read('JSONDB',array('I'=>$ID));
    Data::Update('JSONDB', array('I'=>$ID,'Data'=>array('key'=>rand())));
    Data::Delete('JSONDB', array('I'=>$ID));
    
    Page::Body('');
