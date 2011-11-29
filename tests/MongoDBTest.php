<?php

    class MongoDBTest extends PHPUnit_Framework_TestCase
    {
        protected $_User = array(
                                  'ID' => 'Tester',
                                  'Name' => array('First' => 'Firstname',
                                                  'Second' => 'Secondname',
                                                  'Third'  => 'Thirdname')
        );

        protected function setUp ()
        {
            F::Run(
                 array(
                      '_N' => 'Engine.Data',
                      '_F' => 'Create',
                      'Scope' => 'User',
                      'Storage' => 'Primary',
                      'Data' => $this->_User
                 )
             );

            return parent::setUp ();
        }

        public function testLoadByID()
        {
            $User = F::Run(
                             array(
                                  '_N' => 'Engine.Data',
                                  '_F' => 'Load',
                                  'Storage' => 'Primary',
                                  'Scope' => 'User',
                                  'Where' =>
                                      array('ID' => $this->_User['ID'])
                             )
                         );

            foreach ($this->_User as $Key => $Value)
                $this->assertArrayHasKey($Key, $User, $Value);
        }

        protected function tearDown ()
        {
            F::Run(
                     array(
                          '_N' => 'Engine.Data',
                          '_F' => 'Delete',
                          'Scope' => 'User',
                          'Storage' => 'Primary',
                          'Where' => array('ID' => $this->_User['ID'])
                     )
                 );

            return parent::tearDown ();
        }

    }