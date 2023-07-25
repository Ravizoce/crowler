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
    public function Collector($main_url)
    {
        function follow_links($url, $domain)
        {
            $already_crawled = [];
            $invalid_urls = []; // Array to store invalid URLs
            $external_urls = []; // Array to store external links

            $options = array('http' => array('method' => "GET", 'headers' => "User-Agent: howbot0.1\n"));
            $context = stream_context_create($options);

            if (empty($url) || $url == "") {

                return [$already_crawled, $invalid_urls, $external_urls];
            }

            $html = @file_get_contents($url, false, $context);
            // dd($html);
            if ($html === false) {
                $invalid_urls[] = $url;
                return [$already_crawled, $invalid_urls, $external_urls];
            }
            $doc = new DOMDocument();
            @$doc->loadHTML(@file_get_contents($url, false, $context));

            $linklist = $doc->getElementsByTagName("a");
            foreach ($linklist as $link) {
                $href = $link->getAttribute("href");
                $parsed_url = parse_url($href);

                // Skip JavaScript links
                if (substr($href, 0, 11) == "javascript:") {
                    continue;
                }

                if (preg_match('/^\+?\d{1,4}[\s-]?\d{1,4}[\s-]?\d{1,9}$/i', $href)) {
                    continue;
                }
                if (strpos($href, 'mailto:') === 0 || strpos($href, 'tel:') === 0) {
                    continue;
                }

                // Skip URLs with empty values but not truly empty
                if (trim($href) === '') {
                    continue;
                }
                if (filter_var($href, FILTER_VALIDATE_EMAIL)) {
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
                    $href = parse_url($url)["scheme"] . "://" . parse_url($url)["host"] . dirname(parse_url($url)["path"]) . $href;
                } elseif (substr($href, 0, 3) == "../") {
                    $href = $domain . "/" . $href;
                }

                if (isset($parsed_url['host']) && $parsed_url['host'] != $domain) {
                    // Skip links leading to external domains
                    $external_urls[] = $href;
                    continue;
                }

                if (!in_array($href, $already_crawled) && !in_array($href, $invalid_urls)) {
                    $already_crawled[] = $href;
                }
            }

            return [$already_crawled, $invalid_urls, $external_urls]; // Return both crawled and invalid URLs
        }

        $already_crawled = [];
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

            //  dd(follow_links($current_url, $domain));
            [$crawled_links, $invalid_links, $external_links] = follow_links($current_url, $domain);
            $invalid_urls = array_merge($invalid_urls, $invalid_links); // Merge invalid URLs with the existing ones
            $invalid_urls = array_merge($external_urls, $invalid_links); // Merge invalid URLs with the existing ones

            foreach ($crawled_links as $link) {
                if (!in_array($link, $already_crawled) && !in_array($link, $to_crawl)) {
                    // Add the link to the already crawled array if it leads to a different page
                    // if (parse_url($link, PHP_URL_PATH) !== parse_url($current_url, PHP_URL_PATH)) {
                    //     $already_crawled[] = $link;
                    // }
                    $to_crawl[] = $link;
                }
            }

            $already_crawled[] = $current_url;
        }

        return (Compact('already_crawled', 'invalid_urls', 'external_urls')); // Return both crawled and invalid URLs

    }

    public function dashboard(Request $request)
    {  //work as the index

        $crowler_id = $request->crowler_id;
        // dd($crowler_id);

        session()->put('crowler_id', $crowler_id);

        $urls = Url::where('crowlers_id', $request->crowler_id)->get();
        $count=count($urls);
        $startDate='today';
        $endDate="endDate";
        $diff="2sec";

        return view('dashboard', $urls,compact('count', 'crowler_id', 'startDate', 'endDate', 'diff'));;
    }

    public function store(Request $request)
    {
        ini_set('max_execution_time', 400);
        $startDate = new \DateTime();
        $crowler = Crowlers::find($request->crowler_id);
        $crowler_id = $request->crowler_id;
        $url = $this->Collector($crowler->url);


        $count= count($url['already_crawled']) + count($url['invalid_urls']) + count($url['invalid_urls']);

        $this->url_store($url , $crowler_id);
        $endDate = new \DateTime();

        $diff = $startDate->diff($endDate);
        // dd($startDate);
        return view('dashboard', compact('count', 'crowler_id', 'startDate', 'endDate', 'diff'));
    }

    public function url_store($url,$crowler_id)
    {
        foreach ($url['already_crawled'] as $link) {
            $existingUrl = Url::where('urls', $link)->first();

            if (!$existingUrl) {
                Url::create([
                    'urls' => $link,
                    'status' => 'Success',
                    'crowlers_id' => $crowler_id,
                ]);
            }
        }

    }
}
