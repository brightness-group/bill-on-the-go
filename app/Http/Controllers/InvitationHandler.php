<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Tenant\User;
use App\Notifications\InviteUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tenancy\Facades\Tenancy;

class InvitationHandler extends Controller
{
    use PasswordValidationRules;

    public $user = null;
    public $tenant = null;

    public function initController($id, $token)
    {
        if ($this->tenant = Tenancy::getTenant()) {
            $this->user = User::find($id);
        } else {
            $this->user = \App\Models\User::find($id);
        }

        if (is_null($this->user))
            return redirect()->route('login')->with('unable', __('locale.Unable to find the given credentials!'));
        $user_token = '';

        foreach ($this->user->unreadNotifications as $notification) {
            if (is_a(InviteUser::class, $notification->type, true)) {
                if ($notification->active) {
                    return redirect()->route('login')->with('already', __('locale.Already activated!'));
                } else {
                    $user_token = $notification->data['invitation_token'];
                }
            }
        }
        if (!empty($user_token) && $token == $user_token) {
            return view('auth.invitation.invitation-receive', ['user' => $this->user]);
//            if (!is_null($this->tenant))
//                return view('tenant.invitation.invitation-receive',['tenant' => Tenancy::getTenant(),'user' => $this->user]);
//            else
//                return view('auth.invitation.invitation-receive',['user'=>$this->user]);
        } else {
            return redirect()->route('login')->with('unable', __('locale.Something is wrong with your request. Please contact your administrator!'));
        }
    }

    public function update(Request $request)
    {
        $this->user = Tenancy::getTenant() ? User::find($request->get('user_id')) :
            \App\Models\User::find($request->get('user_id'));

        $this->reset($this->user, $request->all());
        foreach ($this->user->unreadNotifications as $notification) {
            if (is_a(InviteUser::class, $notification->type, true)) {
                $notification->active = true;
                $notification->save();
            }
        }
        return redirect()->route('login')->with('success', __('locale.Activation successfully!'));
    }

    public function reset($user, array $input)
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $data = [
            'password' => $input['password'],
        ];

        // update password time for backoffice users
        if (!Tenancy::getTenant()) {
            $data['password_updated_at'] = now();
            $data['password_update_remind_at'] = now();
        }

        $user->forceFill($data)->save();
    }
}
