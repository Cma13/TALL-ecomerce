<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCategory extends Component
{
    use WithFileUploads;

    public $brands, $rand;
    public $category, $categories;
    public $editImage;

    public $createForm = [
        'name' => null,
        'slug' => null,
        'icon' => null,
        'brands' => [],
        'image' => null,
    ];

    public $editForm = [
        'open' => false,
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
        'editForm.name' => 'nombre',
        'editForm.slug' => 'slug',
        'editForm.icon' => 'icono',
        'editForm.brands' => 'marcas',
        'editImage' => 'imagen',
    ];

    protected $listeners = ['delete'];

    public function updatedCreateFormName($value)
    {
        $this->createForm['slug'] = Str::slug($value);
    }

    public function updatedEditFormName($value)
    {
        $this->editForm['slug'] = Str::slug($value);
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

    public function edit(Category $category)
    {
        $this->reset('editImage');
        $this->resetValidation();

        $this->category = $category;

        $this->editForm['open'] = true;
        $this->editForm['name'] = $category->name;
        $this->editForm['slug'] = $category->slug;
        $this->editForm['icon'] = $category->icon;
        $this->editForm['brands'] = $category->brands->pluck('id');
        $this->editForm['image'] = $category->image;
    }

    public function update()
    {
        $rules = [
            'editForm.name' => 'required',
            'editForm.slug' => 'required|unique:categories,slug,' . $this->category->id,
            'editForm.icon' => 'required',
            'editForm.brands' => 'required',
        ];

        if ($this->editImage) {
            $rules['editImage'] = 'image|max:1024';
        }

        $this->validate($rules);

        if ($this->editImage) {
            Storage::delete($this->editForm['image']);
            $this->editForm['image'] = $this->editImage->store('categories');
        }

        $this->category->update($this->editForm);

        $this->category->brands()->sync($this->editForm['brands']);

        $this->reset(['editForm', 'editImage']);
        $this->getCategories();
        $this->emit('updated');
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
