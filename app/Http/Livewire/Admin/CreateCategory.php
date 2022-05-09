<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCategory extends Component
{
    use WithFileUploads;

    public $brands, $rand;
    public $categories;

    public $createForm = [
        'name' => null,
        'slug' => null,
        'icon' => null,
        'brands' => [],
        'image' => null,
    ];

    protected $rules = [
        'createForm.name' => 'required',
        'createForm.slug' => 'required|unique:categories,slug',
        'createForm.icon' => 'required',
        'createForm.brands' => 'required',
        'createForm.image' => 'required|image|max:1024',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'nombre',
        'createForm.slug' => 'slug',
        'createForm.icon' => 'icono',
        'createForm.brands' => 'marcas',
        'createForm.image' => 'imagen',
    ];

    protected $listeners = ['delete'];

    public function updatedCreateFormName($value)
    {
        $this->createForm['slug'] = Str::slug($value);
    }

    public function getBrands()
    {
        $this->brands = Brand::all();
    }

    public function getCategories()
    {
        $this->categories = Category::all();
    }

    public function save()
    {
        $this->validate();

        $image = $this->createForm['image']->store('categories', 'public'); //No hay error

        $category = Category::create([
            'name' => $this->createForm['name'],
            'slug' => $this->createForm['slug'],
            'icon' => $this->createForm['icon'],
            'image' => $image,
        ]);

        $category->brands()->attach($this->createForm['brands']);

        $this->rand = rand();
        $this->reset('createForm');

        $this->emit('saved');
        $this->getCategories();
    }

    public function delete(Category $category)
    {
        $category->delete();

        $this->getCategories();
    }

    public function mount()
    {
        $this->getBrands();
        $this->getCategories();

        $this->rand = rand();
    }

    public function render()
    {
        return view('livewire.admin.create-category');
    }
}
