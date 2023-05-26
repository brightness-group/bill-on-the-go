<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tenant\ConnectionReport;
use App\Models\Tenant\Contact;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Device;
use App\Models\Tenant\File\FileConnection;
use App\Models\Tenant\SharedUser;
use App\Models\Tenant\User;
use App\Services\ManualActivityTariffApplying;
use App\Services\OverlapsEvaluation;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Tenancy\Facades\Tenancy;
use App\Models\Tenant\File\File;
use Exception;


class APIAuthController extends Controller
{
    use ApiResponser;

    public function revoke_token(Request $request)
    {
        $lang = $request->user()->locale;
        if ($lang != App::getLocale())
            App::setLocale($lang);

        if ($request->user()->tokens()->count()) {
            $request->user()->tokens()->delete();
            return $this->success($request->user(), __('locale.Token revoked!'), 200);
        } else return $this->success(null, __('locale.Not defined'), 200);
    }

    public function devices(Request $request)
    {
        $lang = $request->user()->locale;
        if ($lang != App::getLocale())
            App::setLocale($lang);

        if ($request->user()->tokenCan('read:devices')) {

            $devices = Device::with('contacts')->select(
                'id',
                'alias',
                'description',
                'bdgogid',
                'online_state',
            )->get();

            if (count($devices) > 0) {
                foreach ($devices as $device) {
                    $device->cont_id = count($device->contacts) > 0 ? collect($device->contacts)->first()->id : null;
                    unset($device->contacts);
                }
                return $this->success(
                    [
                        'devices' => $devices
                    ],
                    '', 200);
            } else {
                return $this->error(__('locale.No data available'), 200);
            }
        } else {
            return $this->error(__('locale.Token not have that ability'), 403);
        }
    }

    public function contacts(Request $request)
    {
        $lang = $request->user()->locale;
        if ($lang != App::getLocale())
            App::setLocale($lang);

        if ($request->user()->tokenCan('read:contacts')) {

            $contacts = Contact::with('devices')->get();

            if (count($contacts) > 0)
                return $this->success(
                    [
                        'contacts' => $contacts
                    ],
                    '', 200);
            else {
                return $this->error(__('locale.No data available'), 200);
            }
        } else
            return $this->error(__('locale.Token not have that ability'), 403);
    }

    public function customer_contacts(Request $request, $bdgogid = null)
    {
        $lang = $request->user()->locale;
        if ($lang != App::getLocale())
            App::setLocale($lang);

        if ($request->user()->tokenCan('read:customer_contacts')) {

            if (!$bdgogid) {
                $data = Customer::all()->map(function ($item) {
                    return $item->bdgogid . ' - ' . $item->customer_name;
                });
                return $this->error(__('locale.Customer identifier is missing'), 403, [
                    'group_list' => $data
                ]);
            }

            $customer = Customer::where('bdgogid', $bdgogid)->first();

            if ($customer) {
                $data = Contact::with('devices')->where('bdgo_gid', $bdgogid)->get();
                if (count($data) > 0) {
                    return $this->success(
                        [
                            'customer_name' => $customer->customer_name,
                            'contacts' => $data
                        ],
                        '', 200);
                } else {
                    return $this->error(__('locale.No data available'), 200);
                }
            } else {
                return $this->error(__('locale.No results found'), 404);
            }
        } else
            return $this->error(__('locale.Token not have that ability'), 403);
    }

    public function store_customer_contact(Request $request)
    {
        $lang = $request->user()->locale;
        if ($lang != App::getLocale())
            App::setLocale($lang);

        if ($request->user()->tokenCan('write:post_customer_contact')) {

            $request->validate([
                'bdgo_gid' => ['required'],
                'salutation' => ['boolean'],
                'firstname' => ['required', 'string', 'min:2'],
                'lastname' => ['required', 'string', 'min:2'],
                'c_department' => ['nullable'],
                'c_function' => ['nullable', 'string'],
                's_email' => ['nullable', 'regex:/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,64})$/'],
                'p_email' => ['nullable', 'regex:/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,64})$/'],
                'b_number' => ['nullable'],
                'm_number' => ['nullable'],
                'h_number' => ['nullable'],
                'devices' => ['nullable', 'array'],
                'devices.*' => ['exists:App\Models\Tenant\Device,id'],
                'mobile_id' => ['nullable', 'unique:App\Models\Tenant\Contact']
            ]);
            $contact = Contact::create([
                'bdgo_gid' => $request->bdgo_gid,
                'salutation' => $request->salutation == 1,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'c_department' => $request->c_department,
                'c_function' => $request->c_function,
                's_email' => $request->s_email,
                'p_email' => $request->p_email,
                'b_number' => $request->b_number,
                'm_number' => $request->m_number,
                'h_number' => $request->h_number,
                'mobile_id' => $request->mobile_id
            ]);
            if ($request->has('devices') && count($request->devices) > 0) {
                $contact->devices()->attach($request->devices);
            }
            $contact = Contact::with('devices')->where('id', $contact->id)->first();
            return $this->success($contact, __('locale.Contact Created!'), 200);
        } else
            return $this->error(__('locale.Token not have that ability'), 403);
    }

    public function connections(Request $request)
    {
        $lang = $request->user()->locale;
        if ($lang != App::getLocale())
            App::setLocale($lang);

        if ($request->user()->tokenCan('read:connections')) {
            $connections = ConnectionReport::all();
            if (!count($connections)) {
                return $this->error(__('locale.No data available'), 200);
            } else
                return $this->success($connections, '', 200);
        } else
            return $this->error(__('locale.Token not have that ability'), 403);
    }

    public function store_device(Request $request)
    {
        $lang = $request->user()->locale;
        if ($lang != App::getLocale())
            App::setLocale($lang);

        if ($request->user()->tokenCan('write:post_devices')) {

            $fields = $request->validate([
                'alias' => ['required', 'unique:App\Models\Tenant\Device', 'string', 'min:2'],
                'description' => ['nullable', 'string', 'min:2', 'max:250'],
                'bdgogid' => ['required', 'string', 'exists:App\Models\Tenant\Customer,bdgogid'],
                'online_state' => ['nullable', 'in:Online,Offline'],
                'mobile_id' => ['nullable', 'unique:App\Models\Tenant\Device']
            ]);

            $data = [
                'id' => $this->generateRandomDeviceId(),
                'alias' => $fields['alias'],
                'description' => $fields['description'] ?? null,
                'bdgogid' => $fields['bdgogid'],
                'online_state' => $fields['online_state'] ?? 'Online',
                'mobile_id' => $fields['mobile_id'] ?? null
            ];

            $device = Device::create($data);
            return $this->success($device, '', 200);
        } else
            return $this->error(__('locale.Token not have that ability'), 403);
    }

    public function update_device(Request $request, $deviceid)
    {
        $lang = $request->user()->locale;
        if ($lang != App::getLocale())
            App::setLocale($lang);

        if ($request->user()->tokenCan('write:put_devices')) {

            if (!$deviceid) {
                return $this->error(__('locale.Device identifier is missing'), 403);
            }

            $device = Device::where('id', $deviceid)->first();

            if (!$device) {
                return $this->error(__('locale.No results found'), 404);
            }

            $fields = [];
            $validateFields = [];

            if ($request->has('alias')) {
                if ($request->alias != $device->alias) {
                    $fields = array_merge($fields, ['alias' => $request->alias]);
                    $validateFields = array_merge($validateFields, [
                        'alias' => ['string', 'min:2', Rule::unique('App\Models\Tenant\Device')->ignore($deviceid, 'id')]
                    ]);
                }
            }
            if ($request->has('description')) {
                if ($request->description != $device->description) {
                    $fields = array_merge($fields, ['description' => $request->description]);
                    $validateFields = array_merge($validateFields, [
                        'description' => ['nullable', 'string', 'min:2', 'max:250']
                    ]);
                }
            }
            if ($request->has('bdgogid')) {
                if ($request->bdgogid != $device->bdgogid) {
                    $fields = array_merge($fields, ['bdgogid' => $request->bdgogid]);
                    $validateFields = array_merge($validateFields, [
                        'bdgogid' => ['required', 'string', 'exists:App\Models\Tenant\Customer,bdgogid']
                    ]);
                }
            }
            if ($request->has('online_state')) {
                if ($request->online_state != $device->online_state) {
                    $fields = array_merge($fields, ['online_state' => $request->online_state]);
                    $validateFields = array_merge($validateFields, [
                        'online_state' => ['nullable', 'in:Online,Offline']
                    ]);
                }
            }
            if ($request->has('mobile_id')) {
                if ($request->mobile_id != $device->mobile_id) {
                    $fields = array_merge($fields, ['mobile_id' => $request->mobile_id]);
                    $validateFields = array_merge($validateFields, [
                        'mobile_id' => ['nullable', 'exists:App\Models\Tenant\Device,mobile_id']
                    ]);
                }
            }

            $validation = Validator::make($fields, $validateFields)->validate();

            if ($validation) {
                $device->update($validation);
                return $this->success($device, __('locale.Device Updated!'), 200);
            } else
                return $this->success($device, __('locale.Nothing to update!'), 200);
        } else
            return $this->error(__('locale.Token not have that ability'), 403);
    }

    public function upload_connection_file(Request $request)
    {
        $lang = $request->user()->locale;
        if ($lang != App::getLocale())
            App::setLocale($lang);

        if ($request->user()->tokenCan('upload:upload_file')) {
            if ($request->has('file')) {
                $file = $request->file('file');
                $validator = Validator::make(['file' => $file], ['file' => ['file', 'mimes:txt']]);
                if ($validator->fails()) {
                    return $this->error($validator->getMessageBag(), 401, [
                        'current_ext_file' => $file->getClientOriginalExtension()
                    ]);
                } else {
                    if ($request->user()->file()->exists()) {
                        $delete_file = $request->user()->file()->first();
                        Storage::delete('public/' . $delete_file->path_to_file);
                        $delete_file->delete();
                    }
                    $model = $this->fileUploaded($file, $request->user());

                    return $this->success([
                        'name' => $model->original_name,
                        'path' => $model->path_to_file,
                        'user' => $model->user_id
                    ], __('locale.File Uploaded Successfully!'), 200);
                }
            }
        } else
            return $this->error(__('locale.Token not have that ability'), 403);
    }

    public function list_uploaded_connections(Request $request)
    {
        $lang = $request->user()->locale;
        if ($lang != App::getLocale())
            App::setLocale($lang);

        if ($request->user()->tokenCan('upload:list_uploaded_connections')) {
            $file = $request->user()->file()->exists() ? $request->user()->file()->first() : null;
            if ($file) {
                $connections = $file->uploaded_connections()->exists() ? $file->uploaded_connections()->get() : null;
                if (count($connections)) {
                    return $this->success($connections, '', 200);
                } else {
                    return $this->error(__('locale.No data available'), 200);
                }
            } else {
                return $this->success([], __('locale.No data available'), 200);
            }
        } else
            return $this->error(__('locale.Token not have that ability'), 403);
    }

    public function process_uploaded_connection(Request $request, $connectionid)
    {
        $lang = $request->user()->locale;
        if ($lang != App::getLocale())
            App::setLocale($lang);

        if ($request->user()->tokenCan('upload:process_uploaded_connection')) {
            if (!$connectionid) {
                return $this->error(__('locale.Connection identifier is missing'), 403);
            }
            $to_process = FileConnection::query()->where('connection_id', $connectionid)->first();
            if ($to_process) {
                $connection_id = $to_process->connection_id;
                $bdgogid = null;
                $groupname = null;
                $userid = null;
                $username = null;
                $device_id = null;
                $devicename = null;
                $start_date = $to_process->start_date->format('d.m.Y H:i:s');
                $end_date = $to_process->end_date->format('d.m.Y H:i:s');
                $billing_state = null;
                $tariff_id = null;
                $notes = null;
                $activity_report = null;
                if ($request->has('start_date')) {
                    if ((Carbon::createFromFormat('d-m-Y H:i:s', $request->has('start_date')) !== false)) {
                        $start_date = Carbon::createFromFormat('d.m.Y H:i:s', $request->get('start_date'), config('site.default_timezone'))->setTimezone('UTC')->format('d.m.Y H:i:s');
                    } else {
                        return $this->error(__("locale.Date don't exist or isn't well formatted"), 403);
                    }
                }
                if ($request->has('end_date')) {
                    if ((Carbon::createFromFormat('d-m-Y H:i:s', $request->has('end_date')) !== false)) {
                        $end_date = Carbon::createFromFormat('d.m.Y H:i:s', $request->get('end_date'), config('site.default_timezone'))->setTimezone('UTC')->format('d.m.Y H:i:s');
                    } else {
                        return $this->error(__("locale.Date don't exist or isn't well formatted"), 403);
                    }
                }
                if ($request->has('bdgogid')) {
                    $bdgogid = $request->get('bdgogid');
                    $group = Customer::query()->where('bdgogid', $bdgogid)->first();
                    $groupname = $group->customer_name;
                } else {
                    return $this->error(__("locale.Customer identifier is missing"), 403);
                }
                if ($request->has('userid')) {
                    $userid = $request->get('userid');
                    $user = SharedUser::query()->where('id', $userid)->first();
                    $username = $user->name;
                } else {
                    return $this->error(__("locale.User identifier is missing"), 403);
                }
                if ($request->has('device_id')) {
                    $device_id = $request->get('device_id');
                    $device = Device::query()->where('id', $device_id)->first();
                    $devicename = $device->alias;
                } else {
                    return $this->error(__("locale.Device identifier is missing"), 403);
                }
                $billing_values = ['Bill', 'DoNotBill', 'Hide'];
                if ($request->has('billing_state')) {
                    $billing_state = $request->get('billing_state');
                } else {
                    return $this->error(__("locale.Status selection is missing"), 403);
                }
                if ($request->has('tariff_id')) {
                    $tariff_id = $request->get('tariff_id');
                }
                if ($request->has('activity_report')) {
                    $activity_report = $request->get('activity_report');
                }
                if ($request->has('notes')) {
                    $notes = $request->get('notes');
                }
                $data = [
                    'id' => $connection_id,
                    'bdgogid' => $bdgogid,
                    'groupname' => $groupname,
                    'userid' => $userid,
                    'username' => $username,
                    'device_id' => $device_id,
                    'devicename' => $devicename,
                    'support_session_type' => 1,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'billing_state' => $billing_state,
                    'tariff_id' => $tariff_id,
                    'activity_report' => $activity_report,
                    'notes' => $notes,
                ];
                $validatedData = [
                    'id' => ['required'],
                    'bdgogid' => ['required'],
                    'groupname' => ['required'],
                    'userid' => ['required'],
                    'username' => ['required'],
                    'device_id' => ['required'],
                    'devicename' => ['required'],
                    'support_session_type' => ['required'],
                    'start_date' => ['date_format:d.m.Y H:i:s'],
                    'end_date' => ['date_format:d.m.Y H:i:s'],
                    'billing_state' => ['required'],
                    'tariff_id' => ['nullable'],
                    'activity_report' => ['nullable'],
                    'notes' => ['nullable'],
                ];
                $validation = Validator::make($data, $validatedData)->validate();
                if (count($validation)) {
                    $connection = ConnectionReport::create($validation);
                    if ($tariff_id) {
                        $price = $connection->calculatePrice();
                        $connection->update(['price' => $price]);
                    }
                    if ($connection->billing_state == 'Hide') {
                        $connection->delete();
                    }
                    if ($connection->billing_state == 'Bill') {
                        $exe = new OverlapsEvaluation($connection);
                        $exe::overlaps_check();
                    }
                    $to_process->delete();
                    return $this->success($connection, __('locale.Connection Created!'), 200);
                }
            } else {
                return $this->error(__('locale.No data available'), 404);
            }
        } else {
            return $this->error(__('locale.Token not have that ability'), 403);
        }
    }

    public function generateRandomConnectionId(): string
    {
        return 'tenant' . Tenancy::getTenant()->getTenantKey() . '-' .
            strtolower($this->generateRandomString(4) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(4)
                . '-' . $this->generateRandomString(12));
    }

    public function generateRandomString($length)
    {
        return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $length);
    }

    public function generateRandomDeviceId(): string
    {
        return 'tenant' . Tenancy::getTenant()->getTenantKey() . '-d' . strtolower($this->generateRandomNumber(10));
    }

    public function generateRandomNumber($length)
    {
        return substr(str_shuffle('0123456789'), 1, $length);
    }

    public function fileUploaded($file, $user)
    {
        Storage::disk('tenant')->putFileAs('/connection/user/' . $user->id . '/file/', $file, $file->getClientOriginalName());
        $path = 'tenants/' . Tenancy::getTenant()->getTenantKey() . '/connection/user/' . $user->id . '/file/' . $file->getClientOriginalName();
        $model = File::create([
            'original_name' => $file->getClientOriginalName(),
            'path_to_file' => $path,
            'user_id' => $user->id,
        ]);

        try {
            if ($this->validateFileFormat($model)) {
                $this->processedFile($model);
                return $model;
            }
        } catch (Exception $e) {
            if ($e) {
                $file_error = null;
                $data = ['file_error' => $file_error];
                $validate = Validator::make($data, ['file_error' => ['required']], [
                    'required' => __('locale.wrong file format')
                ]);
                if ($validate->fails()) {
                    Storage::delete('public/' . $model->path_to_file);
                    $model->delete();
                    $validate->validate();
                }
            }
        }
        return null;
    }

    public function validateFileFormat($model)
    {
        $fp = fopen(Storage::path('public/' . $model->path_to_file), "r");
        $flag = [];
        while (!feof($fp)) {
            $line = fgets($fp);
            $list = explode('/', preg_replace('/\s+/', '/', str_replace(array('{', '}'), '', $line)));

            if (count($list) > 6) {
                if (array_key_exists(1, $list) && array_key_exists(2, $list) && array_key_exists(3, $list) && array_key_exists(4, $list)) {
                    if (Carbon::createFromFormat('d-m-Y H:i:s', $list[1] . ' ' . $list[2]) !== false) {
                        $start_date = Carbon::createFromFormat('d-m-Y H:i:s', $list[1] . ' ' . $list[2]);
                        $flag['start_date'] = true;
                    }
                    if (Carbon::createFromFormat('d-m-Y H:i:s', $list[3] . ' ' . $list[4]) !== false) {
                        $end_date = Carbon::createFromFormat('d-m-Y H:i:s', $list[3] . ' ' . $list[4]);
                        $flag['end_date'] = true;
                    }
                }
            }
        }
        if (array_values($flag) != true) {
            throw new Exception(__('locale.Something wrong happen with your request.'));
        }
        return true;
    }

    public function processedFile($file)
    {
        if (Storage::exists('public/' . $file->path_to_file)) {
            $fp = fopen(Storage::path('public/' . $file->path_to_file), "r");
            while (!feof($fp)) {
                $line = fgets($fp);
                $list = explode('/', preg_replace('/\s+/', '/', str_replace(array('{', '}'), '', $line)));
                if (count($list) > 6) {
                    $id = $list[7];
                    $start_date = Carbon::createFromFormat('d-m-Y H:i:s', $list[1] . ' ' . $list[2]);
                    $end_date = Carbon::createFromFormat('d-m-Y H:i:s', $list[3] . ' ' . $list[4]);
                    $user_short_name = $list[5];
                    $user = SharedUser::query()->where('name', 'LIKE', '%' . $user_short_name . '%')->get();
                    $user_id = null;
                    $username = null;
                    if (count($user) == 1) {
                        $user_id = $user[0]->id;
                        $username = $user[0]->name;
                    }
                    if (!ConnectionReport::query()->whereKey($id)->exists()) {
                        $file->uploaded_connections()->create([
                            'connection_id' => $id,
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                            'userid' => $user_id,
                            'username' => $username,
                        ]);
                        $flag = true;
                    }
                }
            }
            fclose($fp);
        }
    }

    public function contact_types(Request $request)
    {
        $lang = $request->user()->locale;
        if ($lang != App::getLocale())
            App::setLocale($lang);

        if ($request->user()->tokenCan('read:contact_types')) {
            return $this->success([
                [
                    'id' => 1,
                    'type' => __('locale.Email'),
                ],
                [
                    'id' => 2,
                    'type' => __('locale.Phone Call'),
                ],
                [
                    'id' => 3,
                    'type' => __('locale.Video Call'),
                ],
                [
                    'id' => 4,
                    'type' => __('locale.On Site'),
                ],
                [
                    'id' => 5,
                    'type' => __('locale.VPN'),
                ],
            ], '', 200);
        } else
            return $this->error(__('locale.Token not have that ability'), 403);
    }

    public function users(Request $request)
    {
        $lang = $request->user()->locale;
        if ($lang != App::getLocale())
            App::setLocale($lang);

        if ($request->user()->tokenCan('read:users')) {
            $users = SharedUser::with('users')->active(true)->orderBy('name')->get([
                'id', 'name', 'email', 'active', 'isTv'
            ]);
            if (!count($users)) {
                return $this->error(__('locale.No data available'), 200);
            } else {
                foreach ($users as $user) {
                    $user->api_user_name = $user->name;
                    $user->short_name = null;
                    $user->profile_photo_url = null;
                    $user->is_allow_api = null;
                    $user->locale = null;
                    $user->user_id = null;
                    $user->is_api_user_linked = false;
                    $user->current_team_id = null;
                    $user->profile_photo_path = null;
                    $user->customer_data_notification = 0;
                    $user->name = null;
                    if (!empty($user->users[0])) {
                        $user->is_api_user_linked = true;
                        $user->user_id = $user->users[0]->id;
                        $user->email = $user->users[0]->email;
                        $user->name = $user->users[0]->name;
                        $user->short_name = $user->users[0]->short_name;
                        $user->profile_photo_url = $user->users[0]->profile_photo_url;
                        $user->is_allow_api = $user->users[0]->is_allow_api;
                        $user->locale = $user->users[0]->locale;
                        $user->current_team_id = $user->users[0]->current_team_id;
                        $user->profile_photo_path = $user->users[0]->profile_photo_path;
                        $user->customer_data_notification = $user->users[0]->customer_data_notification;
                    }
                    unset($user->users);
                }
                return $this->success($users, '', 200);
            }
        } else
            return $this->error(__('locale.Token not have that ability'), 403);
    }

    public function getGroupIdGenerated(): string
    {
        $randomGroupId = $this->generateRandomOwnGroupId();
        if (Customer::query()->where('bdgogid', $randomGroupId)->exists())
            $this->getGroupIdGenerated();
        else
            return $randomGroupId;
    }

    public function generateRandomOwnGroupId(): string
    {
        return 't' . Tenancy::getTenant()->getTenantKey() . '-g' . strtolower($this->generateRandomStringByNumber(9));
    }

    public function generateRandomStringByNumber($length)
    {
        return substr(str_shuffle('123456789'), 1, $length);
    }
}
