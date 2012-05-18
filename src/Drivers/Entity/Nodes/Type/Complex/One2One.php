<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Write', function ($Call)
    {
        return $Call['Value'];
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });

    self::setFn('Widget', function ($Call)
    {
        return F::Merge(F::Merge($Call['Widgets'][$Call['Purpose']],
                        array(
                            'Value' => F::Run('Entity.Dict', 'Get',
                                array(// FIXME
                                     'Entity' => $Call['Node']['Link']['Entity'],
                                     'Key' => $Call['Node']['Link']['Key'])))),
                            array('Entity' => $Call['Node']['Link']['Entity'], 'Link' => $Call['Node']['Link']['Entity'],
                                  'Key' => $Call['Node']['Link']['Key']));
    });

    self::setFn('Form', function ($Call)
    {
        // FIXME ASAP
        $ID = md5(rand());

        return '<div class="well">
            <h6>Один-к-одному</h6>
            <div class="control-group">
                <label class="control-label" for="Name"> Имя атрибута:</label>
                <div class="controls">
                    <input id="Name" type="text" name="Nodes[Name]['.$ID.']" class="span4"/>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="Link"> Сущность:</label>
                <div class="controls">
                    <input id="Link" type="text" name="Nodes[Link]['.$ID.'][Entity]" class="span4"/>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="Link"> Атрибут сущности:</label>
                <div class="controls">
                    <input id="Link" type="text" name="Nodes[Link]['.$ID.'][Key]" class="span4"/>
                </div>
            </div>

            <input id="Type" type="hidden" name="Nodes[Type]['.$ID.']" value="Complex.One2One">

            <div class="control-group">
                <label class="control-label" for="Type"> Обязательное?</label>
                <div class="controls">
                    <input type="checkbox" id="Required" name="Nodes[Required]['.$ID.']"  />
                </div>
            </div>
        </div></div>';
    });