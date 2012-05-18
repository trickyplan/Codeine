<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Write', function ($Call)
    {
        return (bool) $Call['Value'];
    });

    self::setFn('Read', function ($Call)
    {
        return (bool) $Call['Value'];
    });

    self::setFn('Widget', function ($Call)
    {
        return $Call['Widgets'][$Call['Purpose']];
    });

    self::setFn('Form', function ($Call)
    {
        // FIXME ASAP
        return '
            <div class="well">
            <h6>Логический</h6>
            <div class="control-group">
                <label class="control-label" for="Name"> Имя атрибута:</label>
                <div class="controls">
                    <input id="Name" type="text" name="Nodes[Name][]" class="span4"/>
                </div>
            </div>

            <input id="Type" type="hidden" name="Nodes[Type][]" value="Boolean">

            <div class="control-group">
                <label class="control-label" for="Type"> Обязательное?</label>
                <div class="controls">
                    <input type="checkbox" id="Required" name="Nodes[Required][]"  />
                </div>
            </div>
        </div></div>';
    });