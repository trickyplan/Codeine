<?php

    class CodeineTest extends PHPUnit_Framework_TestCase
    {
        protected $_Path;

        public function setUp ()
        {
            $this->_Path = Codeine . '/Options/';
        }

        protected function enumerateContracts ()
        {
            $Directory = new RecursiveDirectoryIterator($this->_Path);
            $Iterator  = new RecursiveIteratorIterator($Directory);
            $Regex     = new RegexIterator($Iterator, '/^.+\.json$/i', RecursiveRegexIterator::GET_MATCH);

            $szPath = strlen($this->_Path);
            $List = array();

            foreach ($Regex as $File)
                $List[] = mb_substr ($File[0], $szPath, strlen ($File[0]) - $szPath - 5);

            return $List;
        }

        public function testByContract()
        {
            $Contracts = $this->enumerateContracts();

            foreach ($Contracts as $Service)
            {
                $Contract = json_decode(file_get_contents($this->_Path.$Service.'.json'), true);

                $Service = strtr ($Service, '/', '.');

                if (isset($Contract['Test']))
                    foreach($Contract['Test'] as $Method => $Suite)
                    {
                        foreach($Suite as $Case)
                        {
                            $Result = F::Run($Service, $Method, $Case['Call']);

                            foreach ($Case['Result'] as $Condition => $Value)
                            {
                                switch ($Condition)
                                {
                                    case 'Equals':
                                        $this->assertEquals($Value, $Result);
                                        break;

                                    case 'NotEquals':
                                        $this->assertNotEquals($Value, $Result);
                                    break;
                                }
                            }
                        }
                    }
            }
        }
    }
