<?php
    /* Codeine
     * @author Muhtar
     * @description
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Parse', function($Call)
    {
        $jsont = $Call['jsont'];
        $jsont = json_decode($jsont, 1);
        foreach (
            $jsont as $key => $rule
        )
        {
            if (substr($key, 0, 4) != 'self')
            {
                $jsont['self.' . $key] = $rule;
            }
        }
        $Call['jsont'] = $jsont;
        return $Call;
    });

    self::setFn('Apply', function($Call)
    {
        $trf = function ($s, $Call)
        {
            return preg_replace_callback('/\{(.+?)\}/',
                function ($matches) use ($Call)
                {
                    $Call['arg'] = $matches[1];
                    $res         = F::Run('Data.Format.JSONT', 'ProcessArg', $Call);
                    return $res['transformed'];
                }, $s);
        };
        if (!$Call['expr'])
        {
            $Call['expr']   = 'self';
            $Call['output'] = false;
        }
        if (gettype($Call['jsont']) == 'string')
        {
            $Call = F::Run('Data.Format.JSONT', 'Parse', $Call);
        }
        if (gettype($Call['json']) == 'string')
        {
            $Call['json'] = json_decode($Call['json']);
        }
        $expr = preg_replace('/\[[0-9]+\]/', '[*]', $Call['expr']);
        if (array_key_exists($expr, $Call['jsont']))
        {
            if (substr($Call['jsont'][$expr], 0, 8) == 'function')
            {
                $res         = F::Run('Data.Format.JSONT', 'Eval', $Call, array ('function'=> 1,
                                                                     'expr'    => $Call['jsont'][$expr],
                                                                     'arg'     => $Call['expr']));
                $transformed = $trf($res['transformed'], $Call);
            }
            else
            {
                $Call['parentExpr'] = $Call['expr'];
                $Call['function']   = 0;
                $transformed        = $trf($Call['jsont'][$expr], $Call);
            }
        }
        else
        {
            $res         = F::Run('Data.Format.JSONT', "Eval", $Call);
            $transformed = $res['transformed'];
        }
        $Call['transformed'] = $transformed;
        return $Call;
    });

    F::setFn("ProcessArg", function($Call)
    {
        $expand         = function($a, $e)
        {
            $a = str_replace('$', $e, $a);
            if (substr($a, 0, 4) != 'self') $a = 'self.' . $a;
            return $a;
        };
        $Call['output'] = true;
        $arg            = $Call['arg'];
        $arg            = str_replace('$', $Call['parentExpr'], $arg);
        $Call['expr']   = $arg;
        if ($arg[0] == '@')
        {
            $res['transformed'] = preg_replace_callback('/@([A-za-z0-9_]+)\(([A-Za-z0-9_\$\.\[\]\']+)\)/',
                function($matches) use ($Call, $expand)
                {
                    $res = F::Run('Data.Format.JSONT', 'Eval', $Call, array ('expr'    => 'self.' . $matches[1],
                                                                 'arg'     => $expand($matches[2], $Call['parentExpr']),
                                                                 'function'=> 2));
                    return $res['transformed'];
                }, $arg);
        }
        else
        {
            if ($Call['arg'] != '$')
            {
                $res = F::Run('Data.Format.JSONT', 'Apply', $Call, array ('expr'=> $expand($Call['arg'], $Call['parentExpr'])));
            }
            else
            {
                $res = F::Run('Data.Format.JSONT', 'Eval', $Call);
            }
        }
        $transformed         = $res['transformed'];
        $Call['transformed'] = $transformed;
        $Call['output']      = false;
        return $Call;
    });

    self::setFn('Eval', function($Call)
    {
        $expr = $Call['expr'];
        if ($Call['function'] == 1 || $Call['function'] == 2)
        {
            if ($Call['function'] == 2)
            {
                if (array_key_exists($expr, $Call['jsont']))
                {
                    $expr = $Call['jsont'][$expr];
                }
                else
                {
                    return "";
                }
            }
            preg_match('/\{(.*)\}/', $expr, $matches);
            $var  = str_replace('self', '$Call[\'json\']', $Call['arg']);
            $var  = str_replace('.', '->', $var);
            $expr = $matches[1];
            $expr = str_replace('$x', $var, $expr);
            $res  = eval($expr);
        }
        else
        {
            $expr = str_replace('self', '$Call[\'json\']', $expr);
            $expr = 'return ' . str_replace('.', '->', $expr) . ';';
            $res  = eval($expr);
        }
        $transformed = "";
        if (!is_null($res))
        {
            switch (gettype($res))
            {
                case 'array':
                    foreach (
                        $res as $key=> $value
                    )
                    {
                        $expr  = $Call['expr'] . '[' . $key . ']';
                        $value = F::Run("Data.Format.JSONT", "Apply", $Call, array ('expr'      => $expr,
                                                                        'parentExpr'=> $expr));
                        $transformed .= $value['transformed'];
                    }
                    break;
                case 'object':
                    foreach (
                        $res as $key=> $value
                    )
                    {
                        $expr  = $Call['expr'] . '.' . $key;
                        $value = F::Run("Data.Format.JSONT", "Apply", $Call, array ('expr'      => $expr,
                                                                        'parentExpr'=> $expr));
                        $transformed .= $value['transformed'];
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
        $Call['transformed'] = $transformed;
        return $Call;
    });