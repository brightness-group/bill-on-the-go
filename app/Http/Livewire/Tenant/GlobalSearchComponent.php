<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Tenant\Customer;
use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Contact;
use App\Models\Tenant\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class GlobalSearchComponent extends Component
{
    const LIMIT_RECORDS = '10';

    public $term = '';

    public $menuHorizontal;

    public $containerNav;

    public function boot()
    {
        $this->term = request()->get('gs', '');
    }

    public function render()
    {
        $container = 'container';

        return view('livewire.tenant.global-search-component')
                    ->extends('tenant.theme-new.layouts.layoutMaster', compact('container'))
                    ->section('content');
    }

    public function search()
    {
        $term = trim(request()->get('term', ''));
        $currentRouteName = request()->get('current-route-name', '');

        $isAORoute = ('customer.connections' == $currentRouteName);

        if (!empty($term)) {
            // AO
            $connectionReport = new ConnectionReport();
            $connections = $connectionReport->withTrashed();
            $connections = $connections->select('groupname as name', 'bdgogid', 'notes')
                            ->where(function ($query) use ($term) {
                                $query->whereLike(['username', 'devicename', 'notes'], $term);
                            })
                            ->limit(self::LIMIT_RECORDS)
                            ->get();

            if (!empty($connections) && !$connections->isEmpty()) {
                $customerListRoute = route('customer.connections');

                $connections->each(function($row) use($term, $customerListRoute) {
                    $row->url = $customerListRoute . "?search_term=" . $term . "&selected_calendar=all&gs=" . $term;

                    $row->term = $term;
                });
            }

            // Customers
            $customers = Customer::select('id', 'customer_name as name')
                        ->where('customer_name', 'like' ,'%' . $term . '%')
                        ->orWhere('email', 'like', '%' . $term . '%')
                        ->orWhere('address', 'like', '%' . $term . '%')
                        ->orWhere('phone', 'like', '%' . $term . '%')
                        ->limit(self::LIMIT_RECORDS)
                        ->get();

            if (!empty($customers) && !$customers->isEmpty()) {
                $customers->each(function($row) use($term) {
                    $customerListRoute = route('customers.show', $row->id);

                    $row->url = $customerListRoute . '?gs=' . $term;
                });
            }

            // Users
            $users = User::select('name')
                ->where(function ($query) use ($term) {
                    $query->where('name', 'like', '%' . $term . '%')
                        ->orWhere('email', 'like', '%' . $term . '%')
                        ->orWhere('two_factor_secret', 'like', '%' . $term . '%');
                })
                ->limit(self::LIMIT_RECORDS)
                ->get();

            if (!empty($users) && !$users->isEmpty()) {
                $companyListRoute = route('account.settings');

                $users->each(function($row) use($term, $companyListRoute) {
                    $row->url = $companyListRoute . "?search=" . $term . '&gs=' . $term . '&contentShow=user-management';
                });
            }

            // Contacts
            $contacts = Contact::select('bdgo_gid', 'firstname', 'lastname')
                        ->where(function ($query) use ($term) {
                            $query->where('firstname', 'like', '%' . $term . '%')
                                ->orWhere('lastname', 'like', '%' . $term . '%')
                                ->orWhere(DB::raw("CONCAT('firstname', ' ', 'lastname')"), 'like', '%' . $term . '%')
                                ->orWhere('c_department', 'like', '%' . $term . '%')
                                ->orWhere('c_function', 'like', '%' . $term . '%')
                                ->orWhere('s_email', 'like', '%' . $term . '%')
                                ->orWhere('p_email', 'like', '%' . $term . '%')
                                ->orWhere('b_number', 'like', '%' . $term . '%')
                                ->orWhere('m_number', 'like', '%' . $term . '%')
                                ->orWhere('h_number', 'like', '%' . $term . '%');
                        })
                        ->has('customer')
                        ->with('customer')
                        ->limit(self::LIMIT_RECORDS)
                        ->get();

            if (!empty($contacts) && !$contacts->isEmpty()) {
                $contacts->each(function($row) use($term) {
                    $contactListRoute = route('customers.show', $row->customer->id);

                    $row->url = $contactListRoute . '?gs=' . $term . '&tab_selected=contact';
                });
            }
        } else {
            $connections = $customers = $users = $contacts = collect([]);
        }

        return response()->json(
            [
                'ao' => $connections->toArray(),
                'customers' => $customers->toArray(),
                'users' => $users->toArray(),
                'contacts' => $contacts->toArray(),
                'term' => $term,
                'isAORoute' => $isAORoute,
                'lang' => [
                    'no_records_msg' => __('locale.No results found'),
                    'job_description' => __('locale.Job Description'),
                    'company' => __('locale.Company'),
                    'group' => __('locale.Group'),
                    'menu' => [
                        'activity_overview' => __('locale.Activity Overview'),
                        'customers' => __('locale.Customers'),
                        'users' => __('locale.Users'),
                        'contacts' => __('locale.Contacts')
                    ]
                ]
            ]
        );
    }
}
