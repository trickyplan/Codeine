<?php

    class Access
    {
        private static $Rules;

        public static function Initialize()
            {
                self::$Rules = new Collection('_Access');
                self::$Rules->Query('@All');
                self::$Rules->Load();
            }

        public static function Check($Target, $Action)
            {
                $Sob = new Object($Target->Scope);

                if (sizeof(self::$Rules->_Items)>0)
                {
                    $Decisions = array();
                    foreach(self::$Rules->_Items as $Rule)
                    {
                        if ($Rule->Get('Action') == '*' or $Rule->Get('Action') == $Action)
                            if ($Rule->Get('Scope') == '*' or $Rule->Get('Scope') == $Target->Scope)
                                if ($Rule->Get('Selector')=='*' or $Sob->Query($Rule->Get('Selector'), $Target->Name))
                                    if ($Rule->Get('Agent')=='*' or $Sob->Query($Rule->Get('Selector'), Client::$User->Name))
                                        if ($Rule->Get('Condition') === null or Code::E('Access/Conditions','Check', $Target) == true)
                                            $Decisions[$Rule->Get('Weight')] = $Rule->Get('Decision');
                    }

                    return $Decisions[max(array_keys($Decisions))];
                }
                else
                    return true;
            }
    }