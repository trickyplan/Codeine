<?php

    class ObjectEngineTest extends PHPUnit_Framework_TestCase
    {
        protected $_User = array(
                                  'ID' => 'Tester',
                                  'Name' => array('First' => 'Firstname',
                                                  'Second' => 'Secondname',
                                                  'Third'  => 'Thirdname')
        );

        protected function _loadObject ($ID)
        {
            return F::Run(
                             array(
                                  '_N' => 'Engine.Data',
                                  '_F' => 'Load',
                                  'Storage' => 'Primary',
                                  'Scope' => 'User',
                                  'Where' =>
                                      array('ID' => $ID)
                             )
                         );
        }

        public function testCreate ()
        {
            $this->assertNotEmpty(F::Run(
                 array(
                      '_N' => 'Engine.Data',
                      '_F' => 'Create',
                      'Scope' => 'User',
                      'Storage' => 'Primary',
                      'Data' => $this->_User
                 )
            ));
        }

        /*
             * @depends testCreate
             */
        public function testLoadByID()
        {
            $User = $this->_loadObject($this->_User['ID']);

            foreach ($this->_User as $Key => $Value)
                $this->assertArrayHasKey($Key, $User, $Value);
        }

        /*
             * @depends testCreate
             */
        public function testUpdate ()
        {
            $this->assertTrue(F::Run(
                                 array(
                                      '_N' => 'Engine.Data',
                                      '_F' => 'Update',
                                      'Scope' => 'User',
                                      'Storage' => 'Primary',
                                      'Where' => array('ID' => $this->_User['ID']),
                                      'Data' => array('Name' => array('First' => 'New Name'))
                                 )
                             ));
        }

        /*
                * @depends testCreate
                */
           public function testDelete ()
           {
               $this->assertTrue(F::Run(
                                    array(
                                         '_N' => 'Engine.Data',
                                         '_F' => 'Delete',
                                         'Scope' => 'User',
                                         'Storage' => 'Primary',
                                         'Where' => array('ID' => $this->_User['ID'])
                                    )
                                ));

               $this->assertNull($this->_loadObject($this->_User['ID']));
           }
    }