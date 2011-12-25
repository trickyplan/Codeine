<?php

    class DocTest extends PHPUnit_Framework_TestCase
    {
        protected $_Path;

        public function testDocPage()
        {
            d(__FILE__, __LINE__, F::Run('Code.Documentation.Generator', 'Do',
                 array(
                      'Service' => 'Security.Hash.MD5',
                      'Method'  => 'Get'
                 )));;
        }
    }
