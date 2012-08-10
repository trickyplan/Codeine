<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Render', function ($Call)
    {
        $Call['Headers']['Content-type:'] = 'application/rss+xml';
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
        $XML->writeElement('description', $Call['Description']);
        $XML->writeElement('generator', 'Codeine');
        $XML->writeElement('pubDate', date(DATE_RSS, time()));
        $XML->writeElement('lastBuildDate', date(DATE_RSS, time()));

        foreach ($Call['Output']['Content'] as $Element)
            if ($Element['Type'] == 'Template')
            {
                $XML->startElement('item');

                    $XML->startElement('title');
                        $XML->text($Element['Data']['Title']);
                    $XML->endElement(); // title

                    $XML->startElement('pubDate');
                        $XML->text(date(DATE_RSS, $Element['Data']['Created']));
                    $XML->endElement(); // description

                    $XML->startElement('description');
                            $XML->writeCdata($Element['Data']['Description']);
                    $XML->endElement(); // description

                    $XML->startElement('link');
                        $XML->text('http://'.$_SERVER['HTTP_HOST'].'/'.strtolower($Element['Scope']).'/'.$Element['Data']['Slug']); // FIXME It's shit!
                    $XML->endElement(); // title

                $XML->endElement(); // item
            }

        $XML->endElement(); // Channel
        $XML->endElement(); // rss
        $XML->endDocument();

        $Call['Output'] = $XML->outputMemory(true);
        
        return $Call;
    });
