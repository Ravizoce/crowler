<?php

namespace App\Http\Controllers;

use App\Models\Crowlers;
use App\Models\Url;
use Illuminate\Http\Request;

class   CrowlersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trace = debug_backtrace();
        // dd($trace);
        $user = auth()->user()->id;
        $crowlers = Crowlers::where('user_id', $user)->get();
        // dd($user);
        // dd($crowlers);
        if (isset($trace[1]['function']) && ($trace[1]['function'] === 'store' || $trace[1]['function'] === 'update')) {
            return $crowlers;
        } else {
            return view('addcrowler', compact('crowlers'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('layouts.forms.crowler-form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $user = auth()->user()->id;
        $crowlers = Crowlers::where('user_id', $user)->get();
        $count = count($crowlers);
        if ($count < 5) {
            Crowlers::create([
                'name'      => $request->name,
                'url'       => $request->url,
                'user_id'   => $request->user_id,
                'author'    => $request->author,
            ]);
        } else {
            $errorMessage = 'Sorry you cannot create more then 5 crowlers at same time!'; // Your error message
            return view('layouts.forms.crowler-form', compact('errorMessage'));
        }
        // $crowlers = $this->index();
        // return view("addcrowler", compact('crowlers'));

        return redirect()->route('crowler.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($crowlers)
    {
        //
        $crowler = Crowlers::where('id', $crowlers)->get();
        // dd($crowler[0]['id']);
        return view('editcrowler', compact('crowler'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Crowlers $crowlers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $crowlers)
    {
        //
        $crowler = Crowlers::where('id', $crowlers);
        $crowler->update([
            'name'=>$request->name,
            'url'=>$request->url,
            'author'=>$request->author,
        ]);

        Url::where('crowlers_id',$crowlers)->delete();

        $crowlers = $this->index();
        return view("addcrowler", compact('crowlers'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($crowlers)
    {
        //
        // dd($crowlers);
        Url::where('crowlers_id',$crowlers)->delete();
        Crowlers::where('id',$crowlers)->delete();
        return redirect()->route('crowler.index');
    }
}
