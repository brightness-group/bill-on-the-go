<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;

class GlobalSearchComponent extends Component
{
    const LIMIT_RECORDS = '10';

    public $menuHorizontal;

    public $containerNav;

    public function render()
    {
        return view('livewire.global-search-component')
            ->extends('theme-new.layouts.layoutMaster');
    }

    public function search()
    {
        $term = trim(request()->get('term', ''));

        $companies = Company::select('id', 'name', 'subdomain', 'address', 'zip', 'city', 'country', 'email', 'contact', 'contact_email')
            ->whereLike(['name', 'subdomain', 'address', 'zip', 'city', 'country', 'email', 'contact', 'contact_email'], $term)
            ->limit(self::LIMIT_RECORDS)
            ->get();

        if (!empty($companies) && !$companies->isEmpty()) {
            $companies->each(function ($row) use ($term) {
                $companyRoute = route('list.companies', ['id' => $row->id]);

                $needle = strtolower($term);

                $subdomain = strtolower($row->subdomain);
                $address = strtolower($row->address);
                $zip = strtolower($row->zip);
                $city = strtolower($row->city);
                $country = strtolower($row->country);
                $email = strtolower($row->email);
                $contact = strtolower($row->contact);
                $contactEmail = strtolower($row->contact_email);

                if (!str_contains($subdomain, $needle)) {
                    unset($row->subdomain);
                }

                if (!str_contains($address, $needle)) {
                    unset($row->address);
                }

                if (!str_contains($zip, $needle)) {
                    unset($row->zip);
                }

                if (!str_contains($city, $needle)) {
                    unset($row->city);
                }

                if (!str_contains($country, $needle)) {
                    unset($row->country);
                }

                if (!str_contains($email, $needle)) {
                    unset($row->email);
                }

                if (!str_contains($contact, $needle)) {
                    unset($row->contact);
                }

                if (!str_contains($contactEmail, $needle)) {
                    unset($row->contact_email);
                }

                $row->url = $companyRoute;

                unset($row->id);
            });
        }

        return response()->json(
            [
                'companies' => $companies,
                'term' => $term,
                'lang' => [
                    'no_records_msg' => __('locale.No results found'),
                    'menu' => [
                        'companies' => __('locale.Companies')
                    ],
                    'subdomain' => __('locale.Subdomain'),
                    'address' => __('locale.Address'),
                    'zip' => __('locale.ZIP'),
                    'city' => __('locale.City'),
                    'country' => __('locale.Country'),
                    'company_email' => __('locale.Company Email'),
                    'contact' => __('locale.Contact'),
                    'contact_email' => __('locale.Contact Email')
                ]
            ]
        );
    }
}
