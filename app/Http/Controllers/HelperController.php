<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class HelperController extends BaseController
{

    public function createRobots()
    {
        $site = Site::where('fqdn', config('app.name'))->firstOrFail();
        header('Content-Type: text/plain');
        echo 'Host: ' . config('app.name') . PHP_EOL;
        echo 'Sitemap: ' . config('app.url') . '/sitemap.xml' . PHP_EOL;
        foreach (array('Yandex', 'Googlebot', 'Mail.Ru', '*') as $bot) {
            echo 'User-agent: ' . $bot . PHP_EOL;
            foreach ($site->items()->orderBy('pivot_sort')->get(array('url')) as $item) {
                echo 'Allow: /' . $item->url . PHP_EOL;
            }
            echo 'Allow: /' . PHP_EOL;
            echo 'Disallow: /LICENSE.txt' . PHP_EOL;
        }
    }

    public function createSitemap()
    {
        $site = Site::where('fqdn', config('app.name'))->firstOrFail();

        $dom = new \DOMDocument('1.0', 'utf-8');
        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        $urlsite = array(
            'loc' => config('app.url'),
            'changefreq' => 'hourly',
            'priority' => '0.9',
            'lastmod' => date(DATE_W3C, strtotime($site->updated_at)),
        );

        $url = $dom->createElement('url');
        foreach ($urlsite as $key => $value) {
            $elem = $dom->createElement($key);
            $elem->appendChild($dom->createTextNode($value));
            $url->appendChild($elem);
        }
        $urlset->appendChild($url);

        foreach ($site->items()->orderBy('pivot_sort')->get(array('url', 'updated_at')) as $item) {
            $url = $dom->createElement('url');
            $urlitem = array(
                'loc' => config('app.url') . '/' . $item->url,
                'changefreq' => 'daily',
                'priority' => '0.7',
                'lastmod' => date(DATE_W3C, strtotime($item->updated_at)),
            );
            foreach ($urlitem as $key => $value) {
                $elem = $dom->createElement($key);
                $elem->appendChild($dom->createTextNode($value));
                $url->appendChild($elem);
            }
            $urlset->appendChild($url);
        }

        header("Content-type: text/xml");
        $dom->appendChild($urlset);
        echo $dom->saveXML();
    }
}
