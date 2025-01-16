<?php

namespace App\Livewire\Components;

use Livewire\Component;

class SelectSearch extends Component
{
    public $query; // path model App\Models\NamaModel
    public $data; // menampung option select dari query
    public $wire_model; //nama wire:model nya
    public $label; //label untuk select search
    public $search = ''; // ini untuk pencarian
    public $colSearch; // ini untuk nama kolom di tabel database yang ingin dicari
    public $colValue; // ini untuk kolom di tabel database sebagai value apabila terselect
    public $selected; // menampung nilai yang terselect

    public function mount($query, $wire_model, $label, $colSearch = 'name', $colValue, $selected = null)
    {
        $this->query = $query;
        $this->wire_model = $wire_model;
        $this->colSearch = $colSearch;
        $this->colValue = $colValue;
        $this->label = $label;
        $this->data = app($this->query)::limit(10)
            ->orderBy($this->colSearch)
            ->get();
        $cek = app($this->query)::where($this->colValue, $selected)->first();
        if ($cek) {
            $colName = $this->colSearch;
            $this->selected = $cek->$colName;
        }
    }

    public function updatedSearch()
    {
        $this->data = app($this->query)::where($this->colSearch, 'like', '%' . $this->search . '%')
            ->limit(10)
            ->orderBy($this->colSearch)
            ->get();
    }

    public function selectValue($value)
    {
        $cek = app($this->query)::where($this->colValue, $value)->first();
        $colName = $this->colSearch;
        $this->selected = $cek->$colName;
        $this->dispatch('select-value', selected: ['model' => $this->wire_model, 'value' => $value]);
    }

    public function render()
    {
        $data = $this->data;
        $label = $this->label;
        $wire_model = $this->wire_model;
        $colSearch = $this->colSearch;
        $colValue = $this->colValue;
        return view('livewire.components.select-search', compact('data', 'label', 'wire_model', 'colSearch', 'colValue'));
    }
}
