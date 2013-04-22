<?php
/**
 * Proof of concept attempt to backup prikpagina fora
 *
 * @todo refactor this into something clean
 * @add support for visiting next listing
 * @add support for visiting topic
 * @add support for storing content (md format?)
 */
$url = 'http://landrover.startpagina.nl/prikbord';
$contentHtml = file_get_contents($url);
$dom = new DOMDocument();
$dom->loadHTML($contentHtml);
$xpath = new DOMXpath($dom);

$table = $xpath->query('//table[@class=\'PhorumStdTable\']');

foreach ($table as $element) {
    $topicRows = $element->childNodes;
    foreach ($topicRows as $topicIndex => $topicRow) {
        $isHeaderRow = $topicIndex == 1;
        if ($isHeaderRow) {
            continue;
        }

        // @todo do this right away
        // Import current row into simplexml since this is easier to work with
        $topic = simplexml_import_dom($topicRow);

        $titleCell = $topic->td[0];
        $authorCell = $topic->td[2];
        $dateCell = $topic->td[3];
        $topicsList[] = array (
            'author' => (string) $authorCell->a,
            'href' => (string) $titleCell->a['href'],
            // @todo extract id from href
            'id'    => '',
            'date' => (string) $dateCell->a,
            'title'=> (string) $titleCell->a
        );
    }
}

print_r($topicsList);