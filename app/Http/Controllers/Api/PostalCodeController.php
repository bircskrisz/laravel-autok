<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostalCodeResource;
use App\Models\PostalCode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostalCodeController extends Controller
{
    /**
     * @api {get} /api/postal-codes Irányítószámok listája, keresés, szűrés
     * @apiName GetPostalCodes
     * @apiGroup PostalCodes
     * @apiVersion 1.0.0
     * @apiDescription Lapozott lista. A <code>search</code> az irányítószám elejére
     * és a település nevének részletére is keres; a megyére azonosító vagy név alapján lehet szűrni.
     * A szűrők kombinálhatók.
     *
     * @apiQuery {String} [search] Keresés irányítószámra (eleje) vagy településnévre (részlet).
     * @apiQuery {Number} [county_id] Szűrés megye azonosítóra.
     * @apiQuery {String} [county] Szűrés megyenévre (pontos egyezés, pl. <code>Pest</code>).
     * @apiQuery {String="postal_code","city"} [sort=postal_code] Rendezés mezője.
     * @apiQuery {String="asc","desc"} [direction=asc] Rendezés iránya.
     * @apiQuery {Number{1-100}} [per_page=20] Találat oldalanként.
     * @apiQuery {Number} [page=1] Oldalszám.
     *
     * @apiExample {curl} Keresés:
     *     curl -H "Accept: application/json" "http://localhost:8000/api/postal-codes?search=debr"
     * @apiExample {curl} Megye szerinti szűrés:
     *     curl -H "Accept: application/json" "http://localhost:8000/api/postal-codes?county=Pest&per_page=50"
     *
     * @apiSuccess {Object[]} data Irányítószámok.
     * @apiSuccess {Number} data.id Azonosító.
     * @apiSuccess {String} data.postal_code Négyjegyű irányítószám.
     * @apiSuccess {String} data.city Település.
     * @apiSuccess {Object} data.county Megye (<code>id</code>, <code>name</code>).
     * @apiSuccess {Object} links Lapozási linkek.
     * @apiSuccess {Object} meta Lapozási adatok (<code>current_page</code>, <code>total</code>, ...).
     * @apiSuccessExample {json} 200:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": [
     *         { "id": 45, "postal_code": "4024", "city": "Debrecen", "county": { "id": 9, "name": "Hajdú-Bihar" } }
     *       ],
     *       "links": { "first": "...?page=1", "last": "...?page=1", "prev": null, "next": null },
     *       "meta": { "current_page": 1, "per_page": 20, "total": 1, "last_page": 1 }
     *     }
     *
     * @apiUse ValidationError
     */
    public function index(Request $request)
    {
        $request->validate([
            'county_id' => ['nullable', 'integer'],
            'sort' => ['nullable', Rule::in(['postal_code', 'city'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $search = trim((string) $request->input('search'));
        $sort = $request->input('sort', 'postal_code');

        $postalCodes = PostalCode::query()
            ->with('county')
            ->when($search !== '', function ($q) use ($search) {
                $q->where(fn ($w) => $w
                    ->where('postal_code', 'like', $search.'%')
                    ->orWhere('city', 'like', '%'.$search.'%'));
            })
            ->when($request->filled('county_id'), fn ($q) => $q->where('county_id', $request->integer('county_id')))
            ->when($request->filled('county'), fn ($q) => $q->whereHas('county', fn ($c) => $c->where('name', $request->input('county'))))
            ->orderBy($sort, $request->input('direction', 'asc'))
            ->orderBy($sort === 'city' ? 'postal_code' : 'city')
            ->paginate($request->integer('per_page', 20))
            ->withQueryString();

        return PostalCodeResource::collection($postalCodes);
    }

    /**
     * @api {get} /api/postal-codes/:id Egy irányítószám
     * @apiName GetPostalCode
     * @apiGroup PostalCodes
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id Az irányítószám-rekord azonosítója.
     *
     * @apiSuccessExample {json} 200:
     *     HTTP/1.1 200 OK
     *     { "data": { "id": 1, "postal_code": "1011", "city": "Budapest", "county": { "id": 1, "name": "Budapest" } } }
     *
     * @apiUse NotFound
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
     * @apiUse TokenAuth
     *
     * @apiBody {String{4}} postal_code Négyjegyű irányítószám (1000–9999).
     * @apiBody {String{..100}} city Település neve.
     * @apiBody {Number} county_id Létező megye azonosítója.
     *
     * @apiExample {json} Kérés:
     *     { "postal_code": "2459", "city": "Rácalmás", "county_id": 7 }
     *
     * @apiSuccessExample {json} 201:
     *     HTTP/1.1 201 Created
     *     { "data": { "id": 151, "postal_code": "2459", "city": "Rácalmás", "county": { "id": 7, "name": "Fejér" } } }
     *
     * @apiUse ValidationError
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
     * @apiDescription Teljes módosítás: mindhárom mezőt meg kell adni.
     * @apiUse TokenAuth
     *
     * @apiParam {Number} id Az irányítószám-rekord azonosítója.
     * @apiBody {String{4}} postal_code Négyjegyű irányítószám.
     * @apiBody {String{..100}} city Település neve.
     * @apiBody {Number} county_id Létező megye azonosítója.
     *
     * @apiSuccessExample {json} 200:
     *     HTTP/1.1 200 OK
     *     { "data": { "id": 151, "postal_code": "2459", "city": "Rácalmás", "county": { "id": 7, "name": "Fejér" } } }
     *
     * @apiUse NotFound
     * @apiUse ValidationError
     */
    public function update(Request $request, PostalCode $postalCode)
    {
        $postalCode->update($this->validated($request, $postalCode));

        return new PostalCodeResource($postalCode->load('county'));
    }

    /**
     * @api {delete} /api/postal-codes/:id Irányítószám törlése
     * @apiName DeletePostalCode
     * @apiGroup PostalCodes
     * @apiVersion 1.0.0
     * @apiUse TokenAuth
     *
     * @apiParam {Number} id Az irányítószám-rekord azonosítója.
     *
     * @apiSuccessExample {json} 200:
     *     HTTP/1.1 200 OK
     *     { "message": "Az irányítószám törölve." }
     *
     * @apiUse NotFound
     */
    public function destroy(PostalCode $postalCode)
    {
        $postalCode->delete();

        return response()->json(['message' => 'Az irányítószám törölve.']);
    }

    private function validated(Request $request, ?PostalCode $postalCode = null): array
    {
        return $request->validate([
            'postal_code' => ['required', 'digits:4', 'regex:/^[1-9]/'],
            'city' => [
                'required', 'string', 'max:100',
                Rule::unique('postal_codes', 'city')
                    ->where('postal_code', $request->input('postal_code'))
                    ->ignore($postalCode?->id),
            ],
            'county_id' => ['required', 'integer', 'exists:counties,id'],
        ], [
            'city.unique' => 'Ez a település már szerepel ezzel az irányítószámmal.',
        ]);
    }
}
