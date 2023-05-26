<div>

    {{-- modal --}}
    <div class="modal" id="modalManual" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalScrollableTitle">{{ __('locale.Manual') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="tariff-infobox right">
                        <p>
                            Steps for create Client ID & Secret,
                        </p>

                        <br/>

                        <ol class="padding">
                            <li>
                                Open <a href="https://login.anydesk.com/nav/profile/apps"
                                        target="__blank">this</a> link and login.
                            </li>
                            <li>
                                Click on Create App button and insert App Name, Description (Optional) and
                                following Redirect URL,
                                <br/>
                                <code>{{ route('anydesk_callback', 'account.settings') }}</code>
                                <br/>
                                <a href="{{ asset('assets/images/anydesk/create-app.png') }}"
                                   target="__blank">
                                    <img src="{{ asset('assets/images/anydesk/create-app.png') }}"
                                         height="200"/>
                                </a>
                            </li>
                            <li>
                                Give following permissions. These permissions are MUST be give to app.

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>
                                                Module
                                            </th>
                                            <th>
                                                Permissions
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                User Management
                                            </td>
                                            <td>
                                                View Users
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Group Management
                                            </td>
                                            <td>
                                                Read Groups
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Connection reporting
                                            </td>
                                            <td>
                                                Read Connection Entries
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Computers & Contacts
                                            </td>
                                            <td>
                                                View Entries
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <a href="{{ asset('assets/images/anydesk/app-permission.png') }}"
                                       target="__blank">
                                        <img
                                            src="{{ asset('assets/images/anydesk/app-permission.png') }}"
                                            height="250"/>
                                    </a>
                                </div>
                            </li>
                            <li>Click on Create button it will create Client ID & Secret.</li>
                        </ol>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- step-1 --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border shadow-none">
                <div class="card-body">
                    <div class="row">
                        <div class="col-2 text-center mt-4">
                            <h2>1</h2>
                        </div>
                        <div class="col-10">
                            @lang('locale.TV Manual Instructions')
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a class="btn btn-sm btn-dark" style="width: {{app()->getLocale() == 'en' ? '100px' : '150px'}}" href="javascript:void(0);"
                       data-bs-toggle="modal" data-bs-target="#modalManual">
                        {{ __('locale.To Manual') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- step-2 --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border shadow-none">
                <div class="card-body">
                    <div class="row">
                        <div class="col-2 text-center mt-4">
                            <h2>2</h2>
                        </div>
                        <div class="col-10">
                            @livewire('tenant.anydesk-configuration-component')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- step-3 --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border shadow-none">
                <div class="card-body">
                    <div class="row">
                        <div class="col-2 text-center mt-4">
                            <h2>3</h2>
                        </div>
                        <div class="col-10">
                            <div class="d-flex">
                                <img src="{{asset('assets/images/ico/icon_anydesk_64.png')}}" width="60" height="60">
                                <div class="card-title mt-1">
                                    <div class="h4" style="margin-bottom: 0;">
                                        &nbsp;{{ __('locale.Teamviewer') }}&nbsp;{{ __('locale.API') }}
                                    </div>

                                    <div wire:key="connection-inform">
                                        @if (!$connected)
                                            &nbsp;<span style="color: #FF0000;">{{ __('locale.Disconnected') }}</span>
                                        @else
                                            &nbsp;<span class="badge"
                                                        style="background-color: #3E6258;">{{ __('locale.Connected') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center pt-0">
                                {{-- user --}}
                                <div wire:key="connection-buttons-user">
                                    @if (!$connected || $alertExpireToken)
                                        <a class="btn btn-outline-dark open-window"
                                           href="javascript:void(0);"
                                           data-win-url="{{ route('anydesk_redirect', ['route' => 'account.settings']) }}"
                                           data-win-name="{{ __('locale.Connection') }}"
                                           data-win-width="800"
                                           data-win-height="500"
                                           wire:loading.attr="disabled">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 fill="currentColor" class="bi bi-arrow-up-circle" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                      d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V11.5z"/>
                                            </svg>
                                            <span>{{ __('locale.Connection') }}</span>
                                        </a>
                                    @else
                                        <a href="{{ route('anydesk_revoke',['token' => $access_token]) }}"
                                           class="btn btn-outline-danger" wire:loading.attr="disabled">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 fill="currentColor" class="bi bi-exclamation-triangle"
                                                 viewBox="0 0 16 16">
                                                <path
                                                    d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                                                <path
                                                    d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                                            </svg>
                                            <span>{{ __('locale.Revoke') }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($alertExpireToken)
                    {{-- <div class="card-footer text-muted">
                        <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                            </svg>
                            <span>
                                &nbsp;{{ __('locale.Teamviewer access token expired.') }}
                            </span>
                            <div class="chip chip-warning ml-0">
                                <div class="chip-body">
                                    <span class="chip-text">
                                        &nbsp;
                                        <a href="{{ route('anydesk_refresh', ['refreshToken' => $refreshToken]) }}">
                                            {{ __('locale.Click here') }}
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <span>
                                &nbsp;{{ __('locale.to request for refresh token.') }}
                            </span>
                        </div>
                    </div> --}}
                @endif
            </div>
        </div>
    </div>
</div>
