<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CarModelController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $manufacturerId = (int) $request->get('manufacturer_id', 0);

        $carModels = CarModel::with('manufacturer')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%')
                      ->orWhere('model_code', 'like', '%'.$search.'%')
                      ->orWhere('body_type', 'like', '%'.$search.'%')
                      ->orWhere('fuel_type', 'like', '%'.$search.'%');
                });
            })
            ->when($manufacturerId > 0, function ($query) use ($manufacturerId) {
                $query->where('manufacturer_id', $manufacturerId);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $manufacturers = Manufacturer::orderBy('name')->get();
        $manufacturerId = $manufacturerId > 0 ? $manufacturerId : null;

        return view('car_models.index', compact('carModels', 'manufacturers', 'search', 'manufacturerId'));
    }

    public function create(Request $request)
    {
        $manufacturers = Manufacturer::orderBy('name')->get();
        $selectedManufacturerId = $request->integer('manufacturer_id') ?: null;

        return view('car_models.create', compact('manufacturers', 'selectedManufacturerId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        CarModel::create($data);

        return redirect()->route('car-models.index')->with('success', 'Modell létrehozva.');
    }

    public function show(CarModel $car_model)
    {
        $car_model->load('manufacturer');

        return view('car_models.show', compact('car_model'));
    }

    public function edit(CarModel $car_model)
    {
        $manufacturers = Manufacturer::orderBy('name')->get();

        return view('car_models.edit', compact('car_model', 'manufacturers'));
    }

    public function update(Request $request, CarModel $car_model)
    {
        $data = $request->validate($this->rules($car_model));

        $car_model->update($data);

        return redirect()->route('car-models.index')->with('success', 'Modell frissítve.');
    }

    public function destroy(CarModel $car_model)
    {
        $car_model->delete();

        return redirect()->route('car-models.index')->with('success', 'Modell törölve.');
    }

    private function rules(?CarModel $carModel = null): array
    {
        return [
            'manufacturer_id' => ['required', 'exists:manufacturers,id'],
            'name' => ['required', 'string', 'max:150'],
            'model_code' => ['required', 'string', 'max:30', Rule::unique('car_models', 'model_code')->ignore($carModel?->id)],
            'model_year' => ['required', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'body_type' => ['nullable', Rule::in(CarModel::BODY_TYPES)],
            'fuel_type' => ['nullable', Rule::in(CarModel::FUEL_TYPES)],
            'power_hp' => ['nullable', 'integer', 'min:1', 'max:3000'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
