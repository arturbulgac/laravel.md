<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Main\NewsController as MainNewsController;
use Illuminate\Http\Request;

class NewsController extends MainNewsController
{
    protected $fillable = [
        'title', 'text'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [
            'articles' => parent::index($request),
            'states' => parent::getStates()
        ];

        if ($request->ajax()) {
            return view('news/index-list', $data);
        } else {
            return view('news/index', $data);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('news.create', ['states' => parent::getStates()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = parent::store($this->requestFill($request, $this->fillable));

        if ($request->ajax()) {
            return $data;
        } else {
            return redirect('news');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('news.show', ['article' => parent::getById($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $data = [
            'article' => parent::getById($id),
            'states' => parent::getStates()
        ];

        if ($request->ajax()) {
            return view('news.ajax-edit', $data);
        } else {
            return view('news.edit', $data);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (true) { // ONLY FOR TEST!!! -- REMOVE ME LATER
            $request = $this->requestFill($request, array_merge($this->fillable, ['state']));
        } else { // Normal mode
            $request = $this->requestFill($request, $this->fillable); 
        }

        $data = parent::update($request, $id);
        if ($request->ajax()) {
            return $data;
        } else {
            //return view('news.show', ['article' => $data]);
            return redirect(route('news.show',$id));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (!$request->user()) {
            return abort(403);
        }
        $article = parent::destroy($request, $id);
        if ($request->ajax()) {
            return $article;
        } else {
            return redirect(route('news.index'));
        }
    }
}
