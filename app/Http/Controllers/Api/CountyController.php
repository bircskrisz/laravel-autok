<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountyResource;
use App\Http\Resources\PostalCodeResource;
use App\Models\County;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CountyController extends Controller
{
    /**
     * @api {get} /api/counties Megyék listája
     * @apiName GetCounties
     * @apiGroup Counties
     * @apiVersion 1.0.0
     * @apiDescription Az összes megye ábécérendben, a hozzájuk tartozó irányítószámok számával.
     *
     * @apiQuery {String} [search] Szűrés a megye nevének részletére.
     *
     * @apiExample {curl} Példa:
     *     curl -H "Accept: application/json" "http://localhost:8000/api/counties?search=bor"
     *
     * @apiSuccess {Object[]} data Megyék.
     * @apiSuccess {Number} data.id Azonosító.
     * @apiSuccess {String} data.name Megye neve.
     * @apiSuccess {Number} data.postal_codes_count Irányítószámok száma a megyében.
     * @apiSuccessExample {json} 200:
     *     HTTP/1.1 200 OK
     *     { "data": [ { "id": 4, "name": "Borsod-Abaúj-Zemplén", "postal_codes_count": 12 } ] }
     */
    public function index(Request $request)
    {
        $counties = County::query()
            ->withCount('postalCodes')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->input('search').'%'))
            ->orderBy('name')
            ->get();

        return CountyResource::collection($counties);
    }

    /**
     * @api {get} /api/counties/:id Egy megye
     * @apiName GetCounty
     * @apiGroup Counties
     * @apiVersion 1.0.0
     *
     * @apiParam {Number} id A megye azonosítója.
     *
     * @apiSuccessExample {json} 200:
     *     HTTP/1.1 200 OK
     *     { "data": { "id": 1, "name": "Bács-Kiskun", "postal_codes_count": 10 } }
     *
     * @apiUse NotFound
     */
    public function show(County $county)
    {
        return new CountyResource($county->loadCount('postalCodes'));
    }

    /**
     * @api {get} /api/counties/:id/postal-codes Egy megye irányítószámai
     * @apiName GetCountyPostalCodes
     * @apiGroup Counties
     * @apiVersion 1.0.0
     * @apiDescription Megyék szerinti szűrés: a megadott megye összes irányítószáma
     * (ugyanaz, mint <code>GET /api/postal-codes?county_id=:id</code>, de lapozás nélkül).
     *
     * @apiParam {Number} id A megye azonosítója.
     *
     * @apiSuccessExample {json} 200:
     *     HTTP/1.1 200 OK
     *     { "data": [ { "id": 31, "postal_code": "6000", "city": "Kecskemét" } ] }
     *
     * @apiUse NotFound
     */
    public function postalCodes(County $county)
    {
        return PostalCodeResource::collection(
            $county->postalCodes()->orderBy('postal_code')->orderBy('city')->get()
        );
    }

    /**
     * @api {post} /api/counties Új megye
     * @apiName CreateCounty
     * @apiGroup Counties
     * @apiVersion 1.0.0
     * @apiUse TokenAuth
     *
     * @apiBody {String{..50}} name Megye neve (egyedi).
     *
     * @apiSuccessExample {json} 201:
     *     HTTP/1.1 201 Created
     *     { "data": { "id": 21, "name": "Teszt megye" } }
     *
     * @apiUse ValidationError
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:counties,name'],
        ]);

        return (new CountyResource(County::create($data)))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @api {put} /api/counties/:id Megye módosítása
     * @apiName UpdateCounty
     * @apiGroup Counties
     * @apiVersion 1.0.0
     * @apiUse TokenAuth
     *
     * @apiParam {Number} id A megye azonosítója.
     * @apiBody {String{..50}} name Megye új neve.
     *
     * @apiSuccessExample {json} 200:
     *     HTTP/1.1 200 OK
     *     { "data": { "id": 21, "name": "Átnevezett megye" } }
     *
     * @apiUse NotFound
     * @apiUse ValidationError
     */
    public function update(Request $request, County $county)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('counties', 'name')->ignore($county->id)],
        ]);

        $county->update($data);

        return new CountyResource($county);
    }

    /**
     * @api {delete} /api/counties/:id Megye törlése
     * @apiName DeleteCounty
     * @apiGroup Counties
     * @apiVersion 1.0.0
     * @apiDescription A megyével együtt a hozzá tartozó irányítószámok is törlődnek.
     * @apiUse TokenAuth
     *
     * @apiParam {Number} id A megye azonosítója.
     *
     * @apiSuccessExample {json} 200:
     *     HTTP/1.1 200 OK
     *     { "message": "A megye törölve." }
     *
     * @apiUse NotFound
     */
    public function destroy(County $county)
    {
        $county->delete();

        return response()->json(['message' => 'A megye törölve.']);
    }
}
