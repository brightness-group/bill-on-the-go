<?php

namespace App\Http\Livewire\Tenant\bdgo;

use App\Helpers\Helper;
use Livewire\WithPagination;
use Livewire\Component;

class DataProcessingAgreementComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;

    public $sortColumn = 'company';

    public $sortDirection = 'asc';

    public $search;

    public $dateFormat = 'Y-m-d';

    public $headers = [

    ];

    protected $listeners = [

    ];

    public function mount()
    {
        $this->dateFormat = config('bdgo.date_format');
    }

    public function render()
    {
        $container = 'container';

        return view('livewire.tenant.bdgo.data-processing-agreement')
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
        /* TODO to add model count */
        $total = 10;

        return Helper::tableFooterString($this->page, $this->perPage, $total);
    }
}
