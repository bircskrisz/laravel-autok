<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostalCodeResource;
use App\Models\PostalCode;
use Illuminate\Http\Request;

class PostalCodeController extends Controller
{
    /**
     * @api {get} /api/postal-codes Irányítószámok listája
     * @apiName GetPostalCodes
     * @apiGroup PostalCodes
     * @apiVersion 1.0.0
     *
     * @apiQuery {String} [search] Keresés irányítószámra vagy településnévre.
     * @apiQuery {Number} [county_id] Szűrés megyére.
     *
     * @apiSuccess {Object[]} data Irányítószámok.
     * @apiSuccess {Number} data.id Azonosító.
     * @apiSuccess {String} data.postal_code Irányítószám.
     * @apiSuccess {String} data.city Település.
     * @apiSuccess {Object} data.county Megye.
     * @apiSuccessExample {json} Válasz:
     *     HTTP/1.1 200 OK
     *     { "data": [ { "id": 65, "postal_code": "4024", "city": "Debrecen", "county": { "id": 9, "name": "Hajdú-Bihar" } } ] }
     */
    public function index(Request $request)
    {
        $postalCodes = PostalCode::with('county')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->where(fn ($w) => $w
                    ->where('postal_code', 'like', $search.'%')
                    ->orWhere('city', 'like', '%'.$search.'%'));
            })
            ->when($request->filled('county_id'), fn ($q) => $q->where('county_id', $request->input('county_id')))
            ->orderBy('postal_code')
            ->get();

        return PostalCodeResource::collection($postalCodes);
    }

    /**
     * @api {get} /api/postal-codes/:id Egy irányítószám
     * @apiName GetPostalCode
     * @apiGroup PostalCodes
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Azonosító.
     *
     * @apiSuccessExample {json} Válasz:
     *     HTTP/1.1 200 OK
     *     { "data": { "id": 65, "postal_code": "4024", "city": "Debrecen", "county": { "id": 9, "name": "Hajdú-Bihar" } } }
     */
    public function show(PostalCode $postalCode)
    {
        return new PostalCodeResource($postalCode->load('county'));
    }

    /**
     * @api {post} /api/postal-codes Új irányítószám
     * @apiName CreatePostalCode
     * @apiGroup PostalCodes
     * @apiVersion 1.0.0
     *
     * @apiHeader {String} Authorization Bearer token.
     *
     * @apiBody {String} postal_code Irányítószám (4 számjegy).
     * @apiBody {String} city Település.
     * @apiBody {Number} county_id Megye azonosító.
     *
     * @apiSuccessExample {json} Válasz:
     *     HTTP/1.1 201 Created
     *     { "data": { "id": 151, "postal_code": "2465", "city": "Ráckeresztúr", "county": { "id": 7, "name": "Fejér" } } }
     */
    public function store(Request $request)
    {
        $postalCode = PostalCode::create($this->validated($request));

        return (new PostalCodeResource($postalCode->load('county')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @api {put} /api/postal-codes/:id Irányítószám módosítása
     * @apiName UpdatePostalCode
     * @apiGroup PostalCodes
     * @apiVersion 1.0.0
     *
     * @apiHeader {String} Authorization Bearer token.
     *
     * @apiParam {Number} id Azonosító.
     * @apiBody {String} postal_code Irányítószám (4 számjegy).
     * @apiBody {String} city Település.
     * @apiBody {Number} county_id Megye azonosító.
     *
     * @apiSuccessExample {json} Válasz:
     *     HTTP/1.1 200 OK
     *     { "data": { "id": 151, "postal_code": "2465", "city": "Ráckeresztúr", "county": { "id": 7, "name": "Fejér" } } }
     */
    public function update(Request $request, PostalCode $postalCode)
    {
        $postalCode->update($this->validated($request));

        return new PostalCodeResource($postalCode->load('county'));
    }

    /**
     * @api {delete} /api/postal-codes/:id Irányítószám törlése
     * @apiName DeletePostalCode
     * @apiGroup PostalCodes
     * @apiVersion 1.0.0
     *
     * @apiHeader {String} Authorization Bearer token.
     *
     * @apiParam {Number} id Azonosító.
     *
     * @apiSuccessExample {json} Válasz:
     *     HTTP/1.1 204 No Content
     */
    public function destroy(PostalCode $postalCode)
    {
        $postalCode->delete();

        return response()->noContent();
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'postal_code' => ['required', 'digits:4'],
            'city' => ['required', 'string', 'max:100'],
            'county_id' => ['required', 'exists:counties,id'],
        ]);
    }
}
