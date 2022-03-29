<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 8.x
     */

    setFn('Render', function ($Call) {
        $Call['XML'] = new XMLWriter();
        $Call['XML']->openMemory();
        $Call['XML']->startDocument('1.0', 'UTF-8');
        $Call['XML']->setIndent(true);

        $Root = array_shift($Call['Output']['Content']);

        $Call = F::Apply(
            null,
            'Element',
            $Call,
            [
                'Node!' => $Root
            ]
        );

        $Call['XML']->endDocument();

        $Call['Output'] = $Call['XML']->outputMemory(true);

        return $Call;
    });

    setFn('Element', function ($Call) {
        $Call['XML']->startElement($Call['Node']['_name']);

        if (isset($Call['Node']['_attributes'])) {
            foreach ($Call['Node']['_attributes'] as $Key => $Value) {
                $Call['XML']->startAttribute($Key);
                $Call['XML']->text($Value);
                $Call['XML']->endAttribute();
            }
        }

        if (isset($Call['Node']['_children'])) {
            foreach ($Call['Node']['_children'] as $IX => $Child) {
                $Call = F::Apply(
                    null,
                    'Element',
                    $Call,
                    [
                        'Node!' => $Child
                    ]
                );
            }
        } elseif (isset($Call['Node']['_text'])) {
            $Call['XML']->text($Call['Node']['_text']);
        }

        $Call['XML']->endElement();
        return $Call;
    });