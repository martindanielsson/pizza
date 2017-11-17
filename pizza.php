#!/usr/bin/env php
<?php
require_once 'vendor/autoload.php';

use Symfony\Component\DomCrawler\Crawler;

$url = 'http://www.pizzahut.se/lunchbuffe';

$html = file_get_contents($url);

if ($html) {
    if (date('N', time()) == 1) {
        $date = new DateTime();
    } else {
        $date = new DateTime('last monday');
    }

    $crawler = new Crawler($html);

    $crawler->filter('form > ul')->each(function (Crawler $node, $i) use ($date) {
        $prefix = $date->format('Y-m-d') == date('Y-m-d') ? "\033[32m" : '';
        echo $prefix . $date->format('l M jS') . PHP_EOL;

        $node->filter('li')->each(function (Crawler $node, $i) {
            if ($i > 1) {
                echo "    VEG: " . $node->text() . PHP_EOL;
            } else {
                echo "    " . $node->text() . PHP_EOL;
            }
        });

        if ($prefix) {
            echo "\033[0m";
        }

        $date->modify("+1 day");
    });

    exit(0);
} else {
    echo "Can't read from url\n";
    exit(1);
}