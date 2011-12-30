#!/usr/bin/php
<?php

/**
 *  @author rtfm.rtfm.rtfm@gmail.com <Daneel S. Yaitskov>
 */
function is_help_arg($param) {
    return $param === "--help"
        or $param === "-h";
}

function tr($msg) {
    echo " *** $msg\n";
}

if (is_help_arg(@$argv[1])
    or empty($argv[1])) {
        echo "Usage: $argv[0] url1 [ url2 [ url3 ... ] ]\n";
        exit;
    }

function process_page($url) {
    if (!preg_match('/^http:\\/\\/([^\\/]+)\\//', $url, $dns)) {
        tr("Url: '$url' is invalide");
        return;
    }
    tr("Domain name: '$dns[1]'");
    $body = file_get_contents($url);
    preg_match_all('/a href="([^"]+(-video|-audio))"/', $body, $matches);

    foreach ($matches[1] as $match) {
        $u = "http://$dns[1]$match";
        tr("Getting url: '$u'");
        $content = file_get_contents($u);
        preg_match_all('/http:\\/\\/.+[^" ](mp4|mp3)/', $content, $medias);
        foreach ($medias[0] as $media)
            echo "$media\n";
    }
}

function output_urls(array $urls) {
    foreach ($urls as $url)
        echo "$url\n";
}

$urls = array_slice($argv, is_help_arg(@$argv[1]) ? 2 : 1); 

foreach ($urls as $url) {
    if (!preg_match('/^http:\\/\\//', $url))
        $url = 'http://' . $url;
    output_urls(
        process_page(
            $url));
}

?>
