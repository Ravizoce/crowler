<?php

namespace App\Http\Controllers;

use App\Models\Crowlers;
use App\Models\Scraper;
use Illuminate\Http\Request;
use App\Models\Url;
use DateTime;
use DOMDocument;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL as FacadesURL;
use PhpParser\Node\Stmt\Foreach_;

class ScrapingController extends Controller
{

    /**
     * Display a listing of the resource.
     */

    function Collector($main_url, $max_depth = 5)
    {
        ini_set('max_execution_time', 300);

        function follow_links($url, $domain)
        {
            list($url, $depth) = explode('|', $url);

            $already_crawled = [];
            $invalid_urls = [];
            $external_urls = [];
            $max_depth = 3;
            $new_depth = $depth + 1;

            // If the current depth exceeds the maximum depth, stop crawling
            if ($depth >= $max_depth) {
                return [$already_crawled, $invalid_urls, $external_urls];
            }

            $options = array('http' => array('method' => "GET", 'headers' => "User-Agent: howbot0.1\n"));
            $context = stream_context_create($options);

            if (empty($url) || $url == "") {
                return [$already_crawled, $invalid_urls, $external_urls];
            }

            $html = @file_get_contents($url, false, $context);

            if ($html === false) {
                $invalid_urls[] = $url;
                return [$already_crawled, $invalid_urls, $external_urls];
            }

            $doc = new DOMDocument();
            @$doc->loadHTML($html);

            $linklist = $doc->getElementsByTagName("a");
            foreach ($linklist as $link) {
                $href = $link->getAttribute("href");
                $parsed_url = parse_url($href);

                // Skip JavaScript links, phone numbers, and email addresses
                if (substr($href, 0, 11) == "javascript:" || preg_match('/^\+?\d{1,4}[\s-]?\d{1,4}[\s-]?\d{1,9}$/i', $href) || strpos($href, 'mailto:') === 0 || strpos($href, 'tel:') === 0) {
                    continue;
                }

                // Skip URLs with empty values but not truly empty
                if (trim($href) === '') {
                    continue;
                }

                // Handle relative URLs
                if (substr($href, 0, 1) == "/") {
                    $href = parse_url($url)["scheme"] . "://" . parse_url($url)["host"] . $href;
                } elseif (substr($href, 0, 2) == "//") {
                    $href = parse_url($url)["scheme"] . ":" . $href;
                } elseif (substr($href, 0, 2) == "./") {
                    $href = parse_url($url)["scheme"] . "://" . parse_url($url)["host"] . dirname(parse_url($url)["path"]) . substr($href, 2);
                } elseif (substr($href, 0, 1) == "#") {
                    $parsed_url = parse_url($url);
                    $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
                    $href = $parsed_url["scheme"] . "://" . $parsed_url["host"] . $path . $href;
                } elseif (substr($href, 0, 3) == "../") {
                    $href = $domain . "/" . $href;
                }

                if (isset($parsed_url['host']) && $parsed_url['host'] != $domain) {
                    // Skip links leading to external domains
                    $external_urls[] = $href;
                    continue;
                }

                if (!in_array($href, $already_crawled) && !in_array($href, $invalid_urls)) {
                    $href .= '|' . $new_depth;
                    $already_crawled[] = $href;
                }
            }

            return [$already_crawled, $invalid_urls, $external_urls];
        }

        $already_crawled = [];
        $main_url .= '|' . 1;
        $to_crawl = [$main_url];
        $domain = parse_url($main_url, PHP_URL_HOST);
        $invalid_urls = [];
        $external_urls = [];

        while (!empty($to_crawl)) {
            $current_url = array_shift($to_crawl);

            // Check if the URL has already been crawled
            if (in_array($current_url, $already_crawled)) {
                continue;
            }

            [$crawled_links, $invalid_links, $external_links] = follow_links($current_url, $domain);

            list($current_url, $depth) = explode('|', $current_url);
            if(!in_array($current_url,$invalid_links) && !in_array($current_url,$external_links)){
                $already_crawled[] = $current_url;
            }
            $invalid_urls = array_merge($invalid_links, $invalid_links);
            $external_urls = array_merge($external_urls, $external_links);

            foreach ($crawled_links as $link) {
                if (!in_array($link, $already_crawled) && !in_array($link, $to_crawl)) {
                    $to_crawl[] = $link;
                }
            }
        }

        $external_urls = collect($external_urls);
        $external_urls = $external_urls->unique()->values()->all();


        return compact('already_crawled', 'invalid_urls', 'external_urls');
    }

    public function dashboard($request)
    {  //work as the index

        session()->put('crowler_id', $request);
        $urls = Url::where('crowlers_id', $request)->get();
        $cr =Crowlers::where('id', $request)->get();
        $count = count($urls);
        // dd($cr['0']['start']);
        $startDateString = $cr['0']['start'];
        $endDateString = $cr['0']['end'];
        $differ = $cr['0']['diff'];
        $crowler_id = $request;

        return view('dashboard', compact('count', 'urls','crowler_id', 'startDateString', 'endDateString', 'differ'));;
    }

    public function store(Request $request)
    {
        //ini_set('max_execution_time', 400);
        $startDate = new \DateTime();
        $crowler = Crowlers::find($request->crowler_id);
        $crowler_id = $request->crowler_id;
        $urls = $this->Collector($crowler->url);

        $count = count($urls['already_crawled']) + count($urls['invalid_urls']) + count($urls['external_urls']);

        $this->url_store($urls, $crowler_id);
        $endDate = new \DateTime();

        // Format the DateTime objects as strings
        $startDateString = $startDate->format('Y-m-d H:i:s');
        $endDateString = $endDate->format('Y-m-d H:i:s');

        $diff = ($startDate->diff($endDate));
        $differ= strval($diff->i) . "m " . strval($diff->s) . "s " .strval($diff->f)."ms";
        Crowlers::where('id',$request->crowler_id)->update([
            'start' => $startDateString,
            'end'   =>$endDateString,
            'diff' => $differ,

        ]);



        return view('dashboard', compact('count','urls', 'crowler_id', 'startDateString', 'endDateString', 'differ'));
    }



    public function url_store($url, $crowler_id)
    {
        // dd($url);
        foreach ($url['already_crawled'] as $link) {
            // list($link, $depth) = explode('|', $link);
            $existingUrl = Url::where('urls', $link)->first();

            if (!$existingUrl) {
                Url::create([
                    'urls' => $link,
                    'status' => 'Internal',
                    'crowlers_id' => $crowler_id,
                ]);
            }
        }
        foreach ($url['invalid_urls'] as $link) {
            $existingUrl = Url::where('urls', $link)->first();

            if (!$existingUrl) {
                Url::create([
                    'urls' => $link,
                    'status' => 'invalid',
                    'crowlers_id' => $crowler_id,
                ]);
            }
        }
        foreach ($url['external_urls'] as $link) {
            $existingUrl = Url::where('urls', $link)->first();

            if (!$existingUrl) {
                Url::create([
                    'urls' => $link,
                    'status' => 'External',
                    'crowlers_id' => $crowler_id,
                ]);
            }
        }
    }
}
