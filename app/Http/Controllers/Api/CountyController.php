<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountyResource;
use App\Models\County;

class CountyController extends Controller
{
    /**
     * @api {get} /api/counties Megyék listája
     * @apiName GetCounties
     * @apiGroup Counties
     * @apiVersion 1.0.0
     *
     * @apiSuccess {Object[]} data Megyék.
     * @apiSuccess {Number} data.id Azonosító.
     * @apiSuccess {String} data.name Megye neve.
     * @apiSuccessExample {json} Válasz:
     *     HTTP/1.1 200 OK
     *     { "data": [ { "id": 1, "name": "Bács-Kiskun" } ] }
     */
    public function index()
    {
        return CountyResource::collection(County::orderBy('name')->get());
    }
}
