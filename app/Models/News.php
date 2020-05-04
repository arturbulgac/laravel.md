<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_id', 'title', 'text', 'state'
    ];

    public function getFillable() {
    	return $this->fillable;
    }

    private $validation_rules = [
    	'author_id' => ['required','int', 'min:1'],
        'title' => ['required', 'string', 'min:3', 'max:191'],
        'text' => ['required', 'string', 'min:10'],
        'state' => ['string', 'max:15', 'in:new,public'],
    ];

    public function getValidationRules() {
    	return $this->validation_rules;
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getStates(){
        return explode(
            ',', 
            substr(end(
                $this->validation_rules['state']
            ), 3)
        );
    }

    public function author(){
        return $this->hasOne('App\Models\User', 'id', 'author_id');
    }
}
