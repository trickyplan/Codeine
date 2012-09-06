<?php

    class ServiceTest extends PHPUnit_Framework_TestCase
    {
        public function __call($Name, $Args)
        {
            if (substr($Name, 0, 4) =='test')
            {
                $Service = substr($Name, 4);

                $Tests = F::findFiles('Options/'.$Service.'.json');

                if (is_array($Tests))
                    foreach ($Tests as $Test)
                    {
                        $Test = json_decode(file_get_contents($Test),true);
                        foreach ($Test['Suites'] as $Suite)
                            foreach ($Suite as $Case)
                                $this->assertEquals(F::Run($Test['Service'], $Case['Method'], $Case['Call']), $Case['Result']);
                    }
            }
            else
                return $this->$Name($Args);
        }
    }


    class CodeineTest extends PHPUnit_Framework_TestCase
    {
        protected $_Path;

        public function setUp ()
        {
            $this->_Path = Codeine . '/Options/';
        }

        public function testAll()
        {
            $Directory = new RecursiveDirectoryIterator($this->_Path);
            $Iterator  = new RecursiveIteratorIterator($Directory);
            $Regex     = new RegexIterator($Iterator, '/^.+\.test.json$/i', RecursiveRegexIterator::GET_MATCH);

            $szPath = strlen($this->_Path);
            $List = array();

            foreach ($Regex as $File)
                $List[] = mb_substr ($File[0], $szPath, strlen ($File[0]) - $szPath - 5);

            foreach ($List as $Service)
            {
                $Test = new ServiceTest();
                $Service = 'test'.$Service;
                $Test->$Service ();
            }

            return $List;
        }


    }
