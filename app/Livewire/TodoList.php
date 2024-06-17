<?php

namespace App\Livewire;

use Livewire\Component;

class TodoList extends Component
{
    #[Rule('required|min:3|max:50')]
    public $name;

    public $search;

    public function create() {
        // validate
        $validated = $this->validateOnly('name');

        // create
        Todo::create($validated);

        // clear input

        // send flash message
    }

    public function render()
    {
        return view('livewire.todo-list');
    }
}
