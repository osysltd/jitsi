<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Session;

class HelperController extends BaseController
{
    /**
     * Render Pay page
     */
    public function payCreate($id, Request $request)
    {
        return view('pay', [
            'data' => \App\Param::all(),
            'menu' => \App\Cat::orderBy('sort', 'asc')->get(),
            'event' => \App\Event::findOrFail($id),
            'pay_type' => $request->pay_type
        ]);
    }

    /**
     * Payment callback page processing
     */
    public function payCallback($id, $hash, Request $request)
    {
        $event = \App\Event::findOrFail($id);
        if ($hash == md5($event->id .  Session::getId())) {
            \App\Tran::create([
                'event_id' => $event->id,
                'session' => Session::getId(),
                'to' => $event->ywallet,
                'amount' => $event->price
            ]);
            $event->cnt += 1;
            $event->save();
        }
        return redirect()->to('/site/event/' . $event->id);
    }

    /**
     *
     * Yandex Quick Pay Button generator
     * @url https://money.yandex.ru/quickpay/
     *
     * Old src: https://money.yandex.ru/embed/small.xml
     * echo '<iframe frameborder="0" allowtransparency="true" scrolling="no" '
     *    . 'src="https://money.yandex.ru/quickpay/button-widget?'
     *    . 'account=' . '<ACCOUNT>' . '&quickpay=small&'
     *    . '<any-card-payment-type|yamoney-payment-type>'
     *    . '=on&phone=on&mail=on&button-text=01&button-size=m&button-color=black'
     *    . '&targets=' . '<TITLE>'
     *    . '&label=' . '<LABEL>'
     *    . '&default-sum=' . '<SUM>'
     *    . '&successURL=' . '<CALLBACK_URL>' . '" width="186" height="42"></iframe>';
     *
     * Use in blade:
     * @php
     * echo App\Http\Controllers\HelperController::createButton($event_id);
     * @endphp
     *
     */
    public static function renderPayButton($event_id, $pay_type)
    {
        $event = \App\Event::findOrFail($event_id);
        $url = url('site/paycallback', ['id' => $event->id, 'hash' => md5($event->id .  Session::getId())]);
        echo '<iframe frameborder="0" allowtransparency="true" scrolling="no" '
            . 'src="https://money.yandex.ru/quickpay/button-widget?'
            . 'account=' . $event->ywallet . '&quickpay=small&'
            . $pay_type
            . '=on&phone=on&mail=on&button-text=01&button-size=m&button-color=black'
            . '&targets=' . $event->title . ' (' . $event->url . ')'
            . '&label=' . Session::getId()
            . '&default-sum=' . $event->price
            . '&successURL=' . $url . '" width="186" height="42"></iframe>';
    }

    /**
     * Render Robots.txt
     *
     * @return Robots.txt
     */
    public function createRobots()
    {
        return response('Not found.', 404);

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

    /**
     * Render Sitemap.xml
     *
     * @return Sitemap.xml
     */
    public function createSitemap()
    {
        return response('Not found.', 404);

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
