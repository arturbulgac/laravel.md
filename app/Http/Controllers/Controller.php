<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Utility. Validator $data by specified $rules
     *
     * @param $data		$request->all()
     * @param $rules	SomeModel->getValidationRules() -- custom method
     * @return array with valid $data
     */
    protected function validation($data, $rules) {
    	return Validator::make($data, $rules);
    }

    /**
     * Utility. Remove unfillable params from request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $request
     */
    protected function requestFill($request, $fillable)
    {
        $keys = array_keys($request->except($fillable));

        foreach ($keys as $key) {
            $request->request->remove($key);
        }

        return $request;
    }
}
