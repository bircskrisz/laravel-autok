<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ManufacturerController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $manufacturers = Manufacturer::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%')
                      ->orWhere('country', 'like', '%'.$search.'%');
                });
            })
            ->withCount('carModels')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('manufacturers.index', compact('manufacturers', 'search'));
    }

    public function create()
    {
        return view('manufacturers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        Manufacturer::create($data);

        return redirect()->route('manufacturers.index')->with('success', 'Gyártó létrehozva.');
    }

    public function show(Manufacturer $manufacturer)
    {
        $carModels = $manufacturer->carModels()->orderBy('name')->get();

        return view('manufacturers.show', compact('manufacturer', 'carModels'));
    }

    public function edit(Manufacturer $manufacturer)
    {
        return view('manufacturers.edit', compact('manufacturer'));
    }

    public function update(Request $request, Manufacturer $manufacturer)
    {
        $data = $request->validate($this->rules($manufacturer));

        $manufacturer->update($data);

        return redirect()->route('manufacturers.index')->with('success', 'Gyártó frissítve.');
    }

    public function destroy(Manufacturer $manufacturer)
    {
        $manufacturer->delete();

        return redirect()->route('manufacturers.index')->with('success', 'Gyártó és modelljei törölve.');
    }

    private function rules(?Manufacturer $manufacturer = null): array
    {
        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('manufacturers', 'name')->ignore($manufacturer?->id)],
            'country' => ['nullable', 'string', 'max:100'],
            'founded_year' => ['nullable', 'integer', 'min:1800', 'max:'.date('Y')],
            'website' => ['nullable', 'url', 'max:255'],
            'logo_url' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
