<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use Auth;

class NewsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $News = News::getModel();
        if(($data = $request->only(['title','state']))){ // have query
            if(isset($data['state'])){
                if(!is_string($data['state']) || !in_array($data['state'], $News->getStates(), true)){
                    return abort(403, 'Invalid query request');
                }

                $News = $News->where('state', $data['state']);
            }

            if(isset($data['title'])){
                if(!is_string($data['title']) || mb_strlen($data['title']) > 127){
                    return abort(403, 'Invalid query request');
                }
                $News = $News->where('title', 'LIKE', '%' .  $data['title'] . '%');
            }
        }

        return $News->orderBy('id', 'DESC')->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!($user = Auth::user())) { // or request->user()
            return abort(403);
        }

        $News = News::getModel();

        $data = $request->only($News->getFillable());
        $data['author_id'] = $user->id;
        $data = $this->validation($data, $News->getValidationRules())->validate();

        $article = $News::create($data);
        
        if ($article->wasRecentlyCreated) {
            return $article;
        } else {
            return abort(500);
        }
        
    }

    /**
     * Removed show & edit methods. 
     * Here must be only processing data without view
     *
     * @param  int  $id
     * @return App\Models\News $id
     */
    public function getById($id) {
        return News::findOrFail($id);
    }

    /**
     * Get possible state values for news 
     *
     * @return App\Models\News states
     */
    public function getStates() {
        return News::getModel()->getStates();
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
        if (!($user = Auth::user())) { // or request->user()
            return abort(403);
        }

        $News = News::getModel();

        $data = $request->only($News->getFillable());
        $data['author_id'] = $user->id;
        $data = $this->validation($data, $News->getValidationRules())->validate();

        $article = $this->getById($id);
        if (!$article->update($data)) {
            return abort(500);
        } else {
            return $article;
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
        $article = $this->getById($id);

        if (!$article->delete()) {
            return abort(500);
        } else {
            return $article;
        }
    }
}
