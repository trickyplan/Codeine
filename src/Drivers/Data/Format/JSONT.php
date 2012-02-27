<?php

    /* Codeine
    * @author Muhtar
    * @description
     * JSONT implementation with additional features
     * like modifiers, context-independent rules(templates)
     * and extended Functions API
    * @package Codeine
    * @version 7.2
    */

    function expand ($a, $e = null)
    {
        if (!is_null($e)) $a = str_replace('$', $e, $a);

        $p = strrpos($a, '^');
        if ($p !== false)
        {
            if ($p + 1 != strlen($a))
            {
                $l = substr($a, $p + 1);
            }
            else
            {
                $l = 1;
            }

            $a = substr_replace($a, '', $p);
            if (is_numeric($l))
            {
                $l = intval($l);
                for ($i = 0; $i < $l; $i++)
                {
                    $a = substr_replace($a, '', strrpos($a, '.'));
                }
            }
        }
        if (substr($a, 0, 4) != 'self') $a = 'self' . ($a != '' ? '.' : '') . $a;
        return $a;
    }

    self::setFn('Query', function($Call)
    {
        $path = str_replace('self', '$Call[\'json\']', $Call['path']);
        $path = str_replace('[*]', '', $path);
        $path = 'return ' . str_replace('.', '->', $path) . ';';
        return eval($path);
    });

    self::setFn('Check', function($Call)
    {
        $path = $Call['path'];

        $rb = strrpos($path, ']');
        if ($rb !== false && $rb + 1 == strlen($path))
        {
            $lb        = strrpos($path, '[') + 1;
            $len       = $rb - $lb;
            $key       = substr($path, $lb, $len);
            $path      = substr_replace($path, '', $lb - 1);
            $keyexists = false;
        }
        else
        {
            $keyexists = true;
        }

        $path = str_replace('self', '$Call[\'json\']', $path);
        $path = str_replace('[*]', '', $path);
        $path = str_replace('.', '->', $path);

        $isset = eval('return isset(' . $path . ');');

        if (!$keyexists)
        {
            $keyexists = eval('return array_key_exists(' . $key . ',' . $path . ');');
        }

        return $isset && $keyexists;
    });

    self::setFn('Init', function($Call)
    {
        if (gettype($Call['jsont']) == 'string')
        {
            //jsont becomes an array
            $Call['jsont'] = json_decode($Call['jsont'], 1);
        }

        if (gettype($Call['json']) == 'string')
        {
            //json becomes an object
            $Call['json'] = json_decode($Call['json']);
        }

        foreach (
            $Call['jsont'] as $key => $rule
        )
        {
            if (gettype($rule) == 'string' && substr($rule, 0, 8) == 'function')
            {
                $rule                = substr_replace($rule, ' $_rule=func_get_arg(1); $_context=func_get_arg(2);', strpos($rule, '{') + 1, 0);
                $rule                = 'return ' . $rule . ';';
                $rule                = eval($rule);
                $Call['jsont'][$key] = $rule;
            }

            if (substr($key, 0, 4) != 'self')
            {
                $Call['jsont']['self.' . $key] = $rule;
                unset($Call['jsont'][$key]);
            }
        }

        if (!$Call['rule'])
        {
            $Call['rule'] = 'self';
        }
        else
        {
            $Call['rule'] = expand($Call['rule']);
        }

        $Call['expression'] = $Call['rule'];
        if (F::Run('Data.Format.JSONT', 'Check', $Call, array ('path'=> $Call['rule'])))
        {
            $Call['context'] = $Call['rule'];
        }
        else
        {
            $Call['context'] = 'self';
        }

        $Call['output'] = false;

        return $Call;
    });

    self::setFn('Apply', function($Call)
    {
        if (gettype($Call['jsont']) == 'string' || gettype($Call['jsont']) == 'string')
        {
            $Call = F::Run('Data.Format.JSONT', 'Init', $Call);
        }

        $trf = function ($s, $Call)
        {
            return preg_replace_callback('/\{(.+?)\}/',
                function ($matches) use ($Call)
                {
                    $Call['expression'] = $matches[1];
                    return F::Run('Data.Format.JSONT', 'ProcessArg', $Call);
                }, $s);
        };

        $transformed = '';
        $rule        = preg_replace('/\[[0-9]+\]/', '[*]', $Call['rule']);
        if (array_key_exists($rule, $Call['jsont']))
        {
            $Call['output'] = true;
            if ($Call['arg'])
            {
                $Call['context'] = $Call['arg'];
                unset($Call['arg']);
            }
            else
            {
                if (F::Run('Data.Format.JSONT', 'Check', $Call, array ('path'=> $Call['rule'])))
                {
                    $Call['context'] = $Call['rule'];
                }
            }

            if (get_class($Call['jsont'][$rule]) == 'Closure')
            {
                $transformed = $trf(F::Run('Data.Format.JSONT', 'Eval', $Call, array ('expression'=> $Call['jsont'][$rule])), $Call);
            }
            else
            {
                $transformed = $trf($Call['jsont'][$rule], $Call);
            }
        }
        else
        {
            $transformed = F::Run('Data.Format.JSONT', 'Eval', $Call, array ('expression'=> $Call['rule']));
        }

        return $transformed;
    });

    self::setFn('ProcessArg', function($Call)
    {
        $Call['output'] = true;
        $transformed    = '';

        $expression = explode(':', $Call['expression']);
        if (count($expression) > 1)
        {
            $modifier   = $expression[0];
            $expression = $expression[1];
        }
        else
        {
            $expression = $expression[0];
        }

        if (!isset($modifier))
        {
            if ($expression[0] == '@')
            {
                $transformed = preg_replace_callback('/@([A-za-z0-9_]+)\(([A-Za-z0-9_\$\.\[\]\'\^]+)\)/',
                    function($matches) use ($Call)
                    {
                        return F::Run('Data.Format.JSONT', 'Apply', $Call, array ('rule'    => expand($matches[1]),
                                                                       'arg'     => expand($matches[2], $Call['context'])
                                                                ));
                    }, $expression);
            }
            else
            {
                if ($expression != '$')
                {
                    if (strpos($expression, '(') === false)
                    {
                        $transformed = F::Run('Data.Format.JSONT', 'Apply', $Call, array ('rule'=> expand($expression, $Call['context'])));
                    }
                    else
                    {
                        $transformed = preg_replace_callback('/([A-za-z0-9_]+)\(([A-Za-z0-9_\$\.\[\]\'\^]+)\)/',
                            function($matches) use ($Call)
                            {
                                return F::Run('Data.Format.JSONT', 'Apply', $Call, array ('rule'    => expand($matches[1]),
                                                                               'arg'     => expand($matches[2], $Call['context'])
                                                                        ));
                            }, $expression);
                    }
                }
                else
                {
                    $transformed = F::Run('Data.Format.JSONT', 'Eval', $Call, array ('expression'=> $Call['context']));
                }
            }
        }
        else
        {
            $lb = strpos($modifier, '(');
            if ($lb !== false)
            {
                $rb       = strrpos($modifier, ')');
                $arg      = substr($modifier, $lb + 1, $rb - $lb - 1);
                $modifier = substr_replace($modifier, '', $lb);
            }
            switch ($modifier)
            {
                case 'foreach':
                    $context = F::Run('Data.Format.JSONT', 'Query', $Call, array ('path'=> $Call['context']));
                    if (gettype($context) == 'object')
                    {
                        foreach ($context as $key=> $value)
                        {
                            $transformed .= F::Run('Data.Format.JSONT', 'Apply', $Call, array ('rule' => expand($expression),
                                                                                    'arg'  => $Call['context'] . '.' . $key));
                        }
                    }
                    break;
                case 'if':
                    $res = F::Run('Data.Format.JSONT', 'ProcessArg', $Call, array ('expression'=> $arg,
                                                                         'rawOutput' => true));
                    if ($res == '1')
                    {
                        $transformed = F::Run('Data.Format.JSONT', 'ProcessArg', $Call, array ('expression' => $expression));
                    }
                    break;
            }
        }

        $Call['output'] = false;
        return $transformed;
    });

    self::setFn('Eval', function($Call)
    {
        $expression = $Call['expression'];

        if (get_class($expression) == 'Closure')
        {
            $context = F::Run('Data.Format.JSONT', 'Query', $Call, array ('path'=> $Call['context']));
            $res     = $expression($context, $Call['rule'], $Call['context']);
            if ($Call['rawOutput']) return $res;
        }
        else
        {
            $res = F::Run('Data.Format.JSONT', 'Query', $Call, array ('path'=> $expression));
        }

        $transformed = '';

        if (!is_null($res))
        {
            switch (gettype($res))
            {
                case 'array':
                    foreach (
                        $res as $key=> $value
                    )
                    {
                        $transformed .= F::Run('Data.Format.JSONT', 'Apply', $Call, array ('rule'  => $expression . '[' . $key . ']',
                                                                                'output'=> false));
                    }
                    break;
                case 'object':
                    foreach (
                        $res as $key=> $value
                    )
                    {
                        $transformed .= F::Run('Data.Format.JSONT', 'Apply', $Call, array ('rule'  => $expression . '.' . $key,
                                                                                'output'=> false));
                    }
                    break;
                case 'string':
                    if ($Call['output'])
                    {
                        $transformed .= $res;
                    }
                    break;
                default:
                    if ($Call['output'])
                    {
                        $transformed .= var_export($res, 1);
                    }
                    break;
            }
        }

        return $transformed;
    });