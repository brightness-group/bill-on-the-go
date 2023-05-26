<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Tenant\Todo;
use Livewire\Component;

class TodoAppComponent extends Component
{
    public $todo = [];
    public $pending_todos = [];
    public $important_todos = [];

    protected $listeners = [
        'initTodo'
    ];

    public function mount()
    {
        $this->initTodo();
        $this->pending_todos = collect($this->todo)->pluck('is_completed', 'id')->toArray();
        $this->important_todos = collect($this->todo)->pluck('is_important', 'id')->toArray();
    }

    public function initTodo()
    {
        $this->todo = Todo::where('user_id', auth()->id())->orderBy('sort_order')->get();
    }

    public function render()
    {
        return view('livewire.tenant.todo-app-component')
            ->extends('tenant.theme-new.layouts.layoutMaster')
            ->section('content');
    }

    public function toggleComplete(int $id, bool $value)
    {
        Todo::where('id', $id)->where('user_id', auth()->id())->update(['is_completed' => $value]);
        $this->dispatchBrowserEvent('showToastrSuccess', ['message' => $value ? __('locale.Task marked as completed!') : __('locale.Task marked as pending!')]);
    }

    public function updatedPendingTodos($value, $id)
    {
        $this->toggleComplete($id, $value);
    }

    public function toggleImportant($id)
    {
        $todo = Todo::where('user_id', auth()->id())->find($id);
        if ($todo && $todo->update(['is_important' => !$todo->is_important])) {
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => $todo->is_important ? __('locale.Task marked as important!') : __('locale.Task removed from important!')]);
        } else {
            $this->dispatchBrowserEvent('showToastrError', ['message' => __('locale.Error')]);
        }
        $this->initTodo();
    }

    public function gotoTodoAction($type)
    {
        session(['todo_view_action_type' => $type]);
        if (in_array($type, ['time-overlapping', 'tariff-overlapping', 'recover-pending-connection'])) {
            return $this->redirectRoute('customer.connections');
        }
    }
}
