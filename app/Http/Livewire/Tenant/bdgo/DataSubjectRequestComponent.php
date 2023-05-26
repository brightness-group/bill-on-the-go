<?php

namespace App\Http\Livewire\Tenant\bdgo;

use App\Helpers\Helper;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Bdgo\Request;
use App\Models\Bdgo\RequestType;
use App\Models\Bdgo\StatusType;
use Illuminate\Support\Facades\Crypt;
use Livewire\WithPagination;
use Livewire\Component;

class DataSubjectRequestComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;

    public $sortColumn = 'updated_at';

    public $sortDirection = 'desc';

    public $search;

    public $statusTypes, $requestTypes;

    public $selectedCustomer;

    public $dateFormat = 'Y-m-d';

    public $headers = [
        'request_id',
        'customer_name',
        'status_type_id',
        'request_type_id',
        'name',
        'email',
        'date_of_receipt',
        'deadline',
        'updated_at'
    ];

    protected $listeners = [
        'refreshParent' => '$refresh',
        'searchUpdate',
        'destroy',
        'showToaster',
        'limitUpdate'
    ];

    public function mount()
    {
        $this->statusTypes  = StatusType::all();

        $this->requestTypes = RequestType::all();

        // Check get request for column sorting.
        $sort = request()->get('sort', null);
        if (!empty($sort) && in_array($sort, $this->headers)) {
            $direction = request()->get('direction', null);

            $direction = (in_array($direction, ['asc', 'desc'])) ? $direction : $this->sortDirection;

            $this->sort($sort, $direction);
        }

        // Check and append search values.
        $search = request()->get('search', null);
        if (!empty($search)) {
            $this->search = $search;
        }

        $this->selectedCustomer = Helper::getSelectedCustomerId();

        $this->dateFormat       = config('bdgo.date_format');
    }

    public function showToaster($data)
    {
        // Check session messages.
        $type = !empty($data['type']) ? $data['type'] : '';
        $msg  = !empty($data['msg']) ? $data['msg'] : '';

        if (!empty($type) && !empty($msg)) {
            switch ($type) {
                case 'success':
                    $this->dispatchBrowserEvent('showToastrSuccess', ['message' => $msg]);
                    break;
                case 'error':
                    $this->dispatchBrowserEvent('showToastrError', ['message' => $msg]);
                    break;
                default:
                    break;
            }
        }
    }

    public function render()
    {
        $container = 'container';

        $customer  = new Customer();

        $request   = new Request();

        $query     = $request::from($request->getTable() . ' as requests')->select('requests.*', 'c.customer_name');

        // Search
        if (!empty($this->search)) {
            $query->where(function($q) {
                return $q->where('requests.name', 'LIKE', "%$this->search%")
                         ->orWhere('requests.email', 'LIKE', "%$this->search%")
                         ->orWhere('requests.request_id', 'LIKE', "%$this->search%");
            });
        }

        // Order
        if (!empty($this->sortColumn) && in_array($this->sortColumn, $this->headers)) {
            $this->sortDirection = (in_array($this->sortDirection, ['asc', 'desc'])) ? $this->sortDirection : 'asc';

            if ($this->sortColumn == 'customer_name') {
                $query->orderBy('c.' . $this->sortColumn, $this->sortDirection);
            } else {
                $query->orderBy('requests.' . $this->sortColumn, $this->sortDirection);
            }
        }

        $requests = $query->leftJoin($customer->getTable() . ' as c', 'requests.customer_id', '=', 'c.id')->paginate($this->perPage);

        return view('livewire.tenant.bdgo.data-subject-request', compact('requests'))
                    ->extends('tenant.theme-new.layouts.layoutMaster', compact('container'))
                    ->section('content');
    }

    public function sort($key, $dir = null)
    {
        $sortDirection = ($this->sortColumn == $key && $this->sortDirection == 'asc') ? 'desc' : 'asc';

        if ($dir) {
            $sortDirection = $dir;
        }

        $this->sortDirection = $sortDirection;

        $this->sortColumn = $key;

        $this->dispatchBrowserEvent('addQueryParams', ['sort' => $this->sortColumn, 'direction' => $this->sortDirection]);
    }

    public function triggerDelete($id)
    {
        $this->dispatchBrowserEvent('triggerDelete', ['id' => $id, 'text' => __('locale.Request will be deleted')]);
    }

    public function destroy($id)
    {
        $id      = (int)Crypt::decrypt($id);

        $request = Request::find($id);

        if (!empty($request)) {
            $request->delete();
        }

        $this->dispatchBrowserEvent('showToastrDelete', ['message' => __('locale.Request Deleted')]);
    }

    public function searchUpdate($search)
    {
        $this->search = $search;

        $this->dispatchBrowserEvent('searchUpdate', ['search' => $this->search]);
    }

    public function setSort($field)
    {
        $sortDirection = $this->sortDirection;

        $sortColumn    = $this->sortColumn;

        $activeUp      = $activeDown = '';

        if ($field == $sortColumn) {
            if ($sortDirection == 'asc') {
                $activeUp = 'active';
            } else {
                $activeDown = 'active';
            }
        }

        return <<<HTML
                    <span class="top-row">
                        <i class="bx bx-chevron-up {$activeUp}"></i>
                    </span>
                    <span class="bottom-row">
                        <i class="bx bx-chevron-down {$activeDown}"></i>
                    </span>
               HTML;
    }

    public function limitUpdate($limit)
    {
        $this->perPage = $limit;

        $this->dispatchBrowserEvent('limitUpdate', ['limit' => $this->perPage]);
    }

    public function getFooterEntries()
    {
        $total = Request::count();

        return Helper::tableFooterString($this->page, $this->perPage, $total);
    }
}
