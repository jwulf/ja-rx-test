<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * A simple method to test whether the API is working
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json('success');
    }
}
