<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Tenant\Todo;
use Livewire\Component;

class TodoDeleteComponent extends Component
{
    public $todo_id;
    public function mount($id)
    {
        $this->todo_id = $id;
    }

    public function render()
    {
        return view('livewire.tenant.todo-delete-component');
    }

    public function destroy()
    {
        $todo = Todo::find($this->todo_id);
        if($todo && $todo->delete()){
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Task Deleted!')]);
        }else{
            $this->dispatchBrowserEvent('showToastrError', ['message' => __('locale.Error')]);
        }
        $this->emitTo('tenant.todo-app-component','initTodo');
        $this->emit('hideModal');
    }
}
