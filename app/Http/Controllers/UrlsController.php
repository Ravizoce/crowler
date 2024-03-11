<?php

namespace App\Http\Controllers;

use App\Models\Url;
use DOMDocument;
use Illuminate\Http\Request;

class UrlsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $crowler_id = session()->get('crowler_id');
        // dd($message);
        $urls = Url::where('crowlers_id', $crowler_id)->paginate(100);
        return view('UrlInspector', compact("urls"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Url $url)
    {
        $options = array('http' => array('method' => "GET", 'headers' => "User-Agent: htmlbot0.1\n"));

        $context = stream_context_create($options);
        $url = "https://www.onlinekhabar.com/";
        $doc = new DOMDocument();
        @$content = @file_get_contents($url, false, $context);


        // if ($content === false) {
        //     return '{"error": "URL not accessible or invalid"}';
        // }
        @$doc->loadHTML($content);
            // dd($doc);
        $title = $doc->getElementsByTagName("title");
        if ($title->length > 0) {
            $title = $title->item(0)->nodeValue;
        }

        $description = "";
        $Keyword = "";
        $metas = $doc->getElementsByTagName('meta');
        dd($metas);
        foreach ($metas as $meta) {
            if ($meta->getAttribute("name") == strtolower("description")) {
                $description = $meta->getAttribute("content");
            }
            if ($meta->getAttribute("name") == strtolower("$Keyword")) {
                $Keyword = $meta->getAttribute("keywords");
            }
        }

        $hello=[
            "Title"=> $title ,
            "Description"=> $description,
            "keywords"=> $Keyword ];
        return view("metadata",compact('hello') );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Url $url)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Url $url)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Url $url)
    {
        //
    }
}
