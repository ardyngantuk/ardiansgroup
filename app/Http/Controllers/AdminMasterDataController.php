<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Unit;
use App\Models\ProductUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminMasterDataController extends Controller
{
    private const CONFIG = [
        'kategori' => [
            'title' => 'Kategori',
            'section' => 'Produk',
            'model' => Category::class,
            'columns' => ['name', 'slug', 'status'],
        ],
        'brand' => [
            'title' => 'Brand',
            'section' => 'Produk',
            'model' => Brand::class,
            'columns' => ['name'],
        ],
        'satuan' => [
            'title' => 'Satuan',
            'section' => 'Produk',
            'model' => Unit::class,
            'columns' => ['name', 'abbreviation'],
        ],
    ];

    public function index(string $module): View
    {
        $config = $this->config($module);
        $model = $config['model'];
        $records = $model::query()->when(request('q'), function ($query, $search): void {
            $query->where(function ($query) use ($search): void {
                $query->where('name', 'like', '%' . trim($search) . '%');
                if (request()->route('module') === 'kategori') {
                    $query->orWhere('slug', 'like', '%' . trim($search) . '%');
                }
            });
        })->latest('id')->paginate(20)->withQueryString();

        if ($module === 'satuan') {
            $records->getCollection()->transform(function (Unit $unit): Unit {
                $unit->usage_count = ProductUnit::where('unit_name', $unit->name)->count();

                return $unit;
            });
        }

        return view('admin.master-data.index', compact('module', 'config', 'records'));
    }

    public function store(Request $request, string $module): RedirectResponse
    {
        $config = $this->config($module);
        $data = $this->validateData($request, $module);
        $config['model']::create($data);

        return back()->with('success', $config['title'] . ' berhasil ditambahkan.');
    }

    public function update(Request $request, string $module, int $record): RedirectResponse
    {
        $config = $this->config($module);
        $model = $config['model']::findOrFail($record);
        $data = $this->validateData($request, $module, $model->id);
        $model->update($data);

        return back()->with('success', $config['title'] . ' berhasil diperbarui.');
    }

    public function destroy(string $module, int $record): RedirectResponse
    {
        $config = $this->config($module);
        $model = $config['model']::findOrFail($record);

        if ($module === 'kategori' && $model->products()->exists()) {
            return back()->with('error', 'Kategori masih dipakai produk dan tidak dapat dihapus. Nonaktifkan atau pindahkan produknya terlebih dahulu.');
        }

        if ($module === 'brand' && $model->products()->exists()) {
            return back()->with('error', 'Brand masih dipakai produk dan tidak dapat dihapus.');
        }

        if ($module === 'satuan' && ProductUnit::where('unit_name', $model->name)->exists()) {
            return back()->with('error', 'Satuan masih dipakai produk dan tidak dapat dihapus.');
        }

        $model->delete();

        return back()->with('success', $config['title'] . ' berhasil dihapus.');
    }

    private function config(string $module): array
    {
        abort_unless(isset(self::CONFIG[$module]), 404);

        return self::CONFIG[$module];
    }

    private function validateData(Request $request, string $module, ?int $ignoreId = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:120'],
        ];


        if ($module === 'kategori') {
            $rules['slug'] = ['nullable', 'string', 'max:150', Rule::unique('categories', 'slug')->ignore($ignoreId)];
            $rules['description'] = ['nullable', 'string'];
            $rules['status'] = ['required', 'in:active,inactive'];
        }

        if ($module === 'brand') {
            $rules['name'][] = Rule::unique('brands', 'name')->ignore($ignoreId);
        }

        if ($module === 'satuan') {
            $rules['name'][] = Rule::unique('units', 'name')->ignore($ignoreId);
            $rules['abbreviation'] = ['required', 'string', 'max:30', Rule::unique('units', 'abbreviation')->ignore($ignoreId)];
        }

        $data = $request->validate($rules);
        if ($module === 'kategori') {
            $data['slug'] = Str::slug($data['slug'] ?: $data['name']);
        }

        return $data;
    }
}
