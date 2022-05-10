<?php

namespace App\Http\Livewire\Admin;

use App\Models\Department;
use Livewire\Component;

class DepartmentComponent extends Component
{
    public $departments, $department;

    public $createForm = [
        'name' => null
    ];

    public $editForm = [
        'open' => false,
        'name' => null
    ];

    protected $rules = [
        'createForm.name' => 'required'
    ];

    protected $validationAttributes = [
        'createForm.name' => 'nombre',
        'editForm.name' => 'nombre'
    ];

    protected $listeners = ['delete'];

    public function getDepartments()
    {
        $this->departments = Department::all();
    }

    public function save()
    {
        $this->validate();

        Department::create($this->createForm);

        $this->reset('createForm');
        $this->emit('saved');
        $this->getBrands();
    }

    public function edit(Department $department)
    {
        $this->department = $department;

        $this->editForm['open'] = true;
        $this->editForm['name'] = $department->name;
    }

    public function update()
    {
        $this->validate([
            'editForm.name' => 'required',
        ]);

        $this->department->update($this->editForm);
        $this->department->save();

        $this->reset('editForm');
        $this->getDepartments();
    }

    public function delete(Department $department)
    {
        $department->delete();
        $this->getDepartments();
    }

    public function mount()
    {
        $this->getDepartments();
    }

    public function render()
    {
        return view('livewire.admin.department-component')->layout('layouts.admin');
    }
}
