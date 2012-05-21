<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Write', function ($Call)
    {
        return filter_var($Call['Value'], FILTER_SANITIZE_STRING);
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });

    self::setFn('Widget', function ($Call)
    {
        return $Call['Widgets'];
    });

    self::setFn('Form', function ($Call)
    {
        // FIXME ASAP
        return '
            <div class="well">
            <h6>Строковый</h6>
            <div class="control-group">
                <label class="control-label" for="Name"> Имя атрибута:</label>
                <div class="controls">
                    <input id="Name" type="text" name="Nodes[Name][]" class="span4"/>
                </div>
            </div>

            <input id="Type" type="hidden" name="Nodes[Type][]" value="Literal.String">

            <div class="control-group">
                <label class="control-label" for="Control"> Элемент управления</label>
                <div class="controls">
                    <input type="radio" name="Nodes[Control][]" value="Normal" /> Textfield
                    <input type="radio" name="Nodes[Control][]" value="Long" /> Tetxarea
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="Type"> Обязательное?</label>
                <div class="controls">
                    <input type="checkbox" id="Required" name="Nodes[Required][]"  />
                </div>
            </div>
        </div></div>';
    });