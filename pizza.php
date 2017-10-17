#!/usr/bin/env php
<?php
$pattern = '/<form.*<\/p>(<h3>.*<\/ul>)<\/form>/';
$url = 'http://www.pizzahut.se/lunchbuffe';

$html = file_get_contents($url);

if ($html) {
    preg_match($pattern, $html, $matches);

    if ($matches && $matches[1]) {
        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $matches[1]);

        if (date('N', time()) == 1) {
            $date = new DateTime();
        } else {
            $date = new DateTime('last monday');
        }

        /** @var DOMElement[] $menu */
        $menu = $dom->getElementsByTagName('ul');

        $d = 0;

        /** @var DOMElement $day */
        foreach ($dom->getElementsByTagName('h3') as $day) {
            $prefix = $date->format('Y-m-d') == date('Y-m-d') ? "\033[32m" : '';
            echo $prefix . $date->format('l M jS') . PHP_EOL;

            $i = 0;

            /** @var DOMElement $item */
            foreach ($menu[$d]->getElementsByTagName('li') as $item) {
                if ($i > 1) {
                    echo "    VEG: " . $item->nodeValue . PHP_EOL;
                } else {
                    echo "    " . $item->nodeValue . PHP_EOL;
                }

                $i++;
            }

            if ($prefix) {
                echo "\033[0m";
            }

            $d++;
            $date->modify("+1 day");
        }
        exit(0);
    } else {
        echo "No html match\n";
        exit(1);
    }
} else {
    echo "Can't read from url\n";
    exit(1);
}