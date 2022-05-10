<?php

namespace App\Http\Livewire\Admin;

use App\Models\City;
use App\Models\District;
use Livewire\Component;

class ShowCity extends Component
{
    public $districts, $district;
    public $city;

    public $createForm = [
        'name' => null,
    ];

    public $editForm = [
        'open' => false,
        'name' => null,
    ];

    protected $rules = [
        'createForm.name' => 'required',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'nombre',
        'editForm.name' => 'nombre',
    ];

    protected $listeners = ['delete'];

    public function getDistricts()
    {
        $this->districts = District::where('city_id', $this->city->id)->get();
    }

    public function save()
    {
        $this->validate();

        $this->city->districts()->create($this->createForm);

        $this->reset('createForm');
        $this->emit('saved');

        $this->getDistricts();
    }

    public function edit(District $district)
    {
        $this->district = $district;

        $this->editForm['open'] = true;
        $this->editForm['name'] = $district->name;
        $this->editForm['cost'] = $district->cost;
    }

    public function update()
    {
        $this->validate([
            'editForm.name' => 'required',
        ]);

        $this->district->update($this->editForm);
        $this->district->save();

        $this->reset('editForm');
        $this->getDistricts();
    }

    public function delete(District $district)
    {
        $district->delete();
        $this->getDistricts();
    }

    public function mount(City $city)
    {
        $this->city = $city;

        $this->getDistricts();
    }

    public function render()
    {
        return view('livewire.admin.show-city')->layout('layouts.admin');
    }
}
