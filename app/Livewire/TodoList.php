<?php

namespace App\Livewire;

use Livewire\Attributes\Rule;
use Livewire\Component;
use App\Models\Todo;
use Livewire\WithPagination;
use Exception;

class TodoList extends Component
{

    use WithPagination;

    #[Rule('required|min:3|max:50')]
    public $name;
    public $search;

    public $editingTodoId;
    #[Rule('required|min:3|max:50')]
    public $editingName;

    public function create()
    {
        // validate
        $validated = $this->validateOnly('name');

        // create
        Todo::create($validated);

        // clear input
        $this->reset('name');

        // send flash message
        session()->flash('success', 'Saved');
        $this->resetPage();
    }

    public function delete($todoId)
    {
        try {
            Todo::findOrFail($todoId)->delete();
        } catch (Exception $e) {
            session()->flash('error', 'Failed to delete todo!');
            return;
        }
    }

    public function toggle(Todo $todo)
    {
        $todo->completed = !$todo->completed;
        $todo->save();
    }

    public function edit(Todo $todo)
    {
        $this->editingTodoId = $todo->id;
        $this->editingName = $todo->name;
    }

    public function cancelEdit()
    {
        $this->reset('editingTodoId', 'editingName');
    }

    public function update()
    {
        $this->validateOnly('editingName');

        Todo::find($this->editingTodoId)->update(
            ['name' => $this->editingName]
        );

        $this->cancelEdit();
    }

    public function render()
    {
        return view('livewire.todo-list', [
            'todos' => Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5)
        ]);
    }
}
