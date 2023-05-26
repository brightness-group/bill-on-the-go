<?php

namespace App\Http\Livewire\Tenant\bdgo;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Customer;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

class AjaxController extends Controller
{
    public function ProcessorsData(HttpRequest $request)
    {
        $query = $request->get('q', '');

        /* [
                'id' => 1,
                'text' => 'Test'
            ],
            [
                'id' => 2,
                'text' => 'Test - 2'
            ] */

        return response()->json([

        ]);
    }

    public function Customers(HttpRequest $request)
    {
        $query     = $request->get('q', '');

        $customers = Customer::query();

        $customers->select('id', 'customer_name as text');

        if (!empty($query)) {
            $customers->whereLike(['customer_name'], $query);
        }

        $customers = $customers->limit(15)->get();

        return response()->json($customers->toArray());
    }

    public function RemoveCookie($name)
    {
        return Cookie::queue(Cookie::forget($name));
    }

    public function SetCookie(HttpRequest $request)
    {
        $name    = $request->get('name', '');
        $value   = $request->get('value', '');
        $minutes = $request->get('minutes', '');

        if (empty($value)) {
            $cookie = $this->RemoveCookie($name);
        } else {
            $cookie = Cookie::queue($name, Crypt::encrypt($value), $minutes);
        }

        return $cookie;
    }

    public function ComputeDashboardWidgets()
    {
        Helper::computeAndStoreAllDashboardWidgets();
    }
}
