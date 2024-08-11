<?php

$json_url = 'https://cdn.jsdelivr.net/gh/disposable/disposable-email-domains@master/domains.json';
$json_data = file_get_contents($json_url);

if ($json_data === FALSE) {
    die('Errore durante il recupero dei dati JSON.');
}

$remote_domains = json_decode($json_data, true);

$site_url = 'https://yopmail.com/it/domain?d=all'; // Sostituisci con l'URL reale
$html = file_get_contents($site_url);

if ($html === FALSE) {
    die('Errore durante il recupero dei dati dal sito.');
}

$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($html);
libxml_clear_errors();

$xpath = new DOMXPath($dom);

$nodes = $xpath->query('//div[@class="lstdom"]/div');

$site_domains = [];
foreach ($nodes as $node) {
    $domain = str_replace('@', '', trim($node->nodeValue));
    $site_domains[] = $domain;

}

$merged_domains = array_unique(array_merge($remote_domains, $site_domains));
sort($merged_domains);

$new_json_file = 'domains.json';
file_put_contents($new_json_file, json_encode($merged_domains, JSON_PRETTY_PRINT));
?>