<?php

namespace App\Http\Livewire\Admin;

use App\Models\City;
use App\Models\Department;
use Livewire\Component;

class ShowDepartment extends Component
{
    public $department;
    public $cities, $city;

    public $createForm = [
        'name' => null,
        'cost' => null,
    ];

    public $editForm = [
        'open' => false,
        'name' => null,
        'cost' => null,
    ];

    protected $rules = [
        'createForm.name' => 'required',
        'createForm.cost' => 'required|numeric|min:1|max:100'
    ];

    protected $validationAttributes = [
        'createForm.name' => 'nombre',
        'createForm.cost' => 'coste',
        'editForm.name' => 'nombre',
        'editForm.cost' => 'coste',
    ];

    protected $listeners = ['delete'];

    public function getCities()
    {
        $this->cities = City::where('department_id', $this->department->id)->get();
    }

    public function save()
    {
        $this->validate();

        $this->department->cities()->create($this->createForm);

        $this->reset('createForm');
        $this->emit('saved');

        $this->getCities();
    }

    public function edit(City $city)
    {
        $this->city = $city;

        $this->editForm['open'] = true;
        $this->editForm['name'] = $city->name;
        $this->editForm['cost'] = $city->cost;
    }

    public function update()
    {
        $this->validate([
            'editForm.name' => 'required',
            'editForm.cost' => 'required',
        ]);

        $this->city->update($this->editForm);
        $this->city->save();

        $this->reset('editForm');
        $this->getCities();
    }

    public function delete(City $city)
    {
        $city->delete();
        $this->getCities();
    }

    public function mount(Department $department)
    {
        $this->department = $department;

        $this->getCities();
    }

    public function render()
    {
        return view('livewire.admin.show-department')->layout('layouts.admin');
    }
}
