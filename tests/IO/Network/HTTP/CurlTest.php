<?php

    class CurlTest extends PHPUnit_Framework_TestCase
    {
        protected $_Path;

        public function testRead()
        {
            $this->assertRegExp('/Google/', F::Run('IO', 'Read', array(
                 'Storage' => 'Web',
                 'Where' =>
                     array(
                         'ID' => 'http://google.com'
                     )
             ))[0]);
        }

        public function testMultiRead()
        {
            $Results = F::Run('IO', 'Read', array(
                 'Storage' => 'Web',
                 'Where' =>
                     array(
                         'ID' => array(
                             'http://google.com',
                             'http://microsoft.com'
                         )
                     )
             ));

            $this->assertRegExp('/Google/', $Results[0]);
            $this->assertRegExp('/Microsoft/', $Results[1]);
        }
    }
