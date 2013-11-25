<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.x
     */

    setFn('Render', function ($Call)
    {
        $Call = F::Hook('beforeRSSPipeline', $Call);

        $XML = new XMLWriter();

        $XML->openMemory();
        $XML->startDocument('1.0', 'UTF-8');
        $XML->setIndent(true);

        $XML->startElement('rss');

        $XML->startAttribute('version');
            $XML->text('2.0');
        $XML->endAttribute();

        $XML->startElement('channel');

        $XML->writeElement('title', $Call['Title']);
        $XML->writeElement('link', $Call['Host'].$Call['Link']);
        if (isset($Call['Description']))
            $XML->writeElement('description', $Call['Description']);
        $XML->writeElement('generator', 'Codeine '.$Call['Version']['Codeine']['Major']);
        $XML->writeElement('pubDate', date(DATE_RSS, time()));
        $XML->writeElement('lastBuildDate', date(DATE_RSS, time()));

        foreach ($Call['Output']['Content'] as $Element)
            if ($Element['Type'] == 'Template' && isset($Element['Data']))
            {
                $XML->startElement('item');

                    $XML->startElement('title');
                        $XML->text($Element['Data']['Title']);
                    $XML->endElement(); // title

                    $XML->startElement('pubDate');
                        $XML->text(date(DATE_RSS, $Element['Data']['Created']));
                    $XML->endElement(); // description

                    if (isset($Element['Data']['Description']))
                    {
                        $XML->startElement('description');
                            $XML->writeCdata($Element['Data']['Description']);
                        $XML->endElement(); // description
                    }

                    $XML->startElement('link');
                        $XML->text($Call['Host'].'/'.strtolower($Element['Scope']).'/'.$Element['Data']['Slug']); // FIXME It's shit!
                    $XML->endElement(); // title

                $XML->endElement(); // item
            }

        $XML->endElement(); // Channel
        $XML->endElement(); // rss
        $XML->endDocument();

        $Call['Output'] = $XML->outputMemory(true);

        $Call = F::Hook('afterRSSPipeline', $Call);

        return $Call;
    });
