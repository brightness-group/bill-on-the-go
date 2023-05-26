{{-- navabar  --}}
<div class="header-navbar-shadow"></div>

{{-- refresh-info  --}}
@livewire('tenant.refresh-info')

<nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu
@if(isset($configData['navbarType'])){{$configData['navbarClass']}} @endif"
data-bgcolor="@if(isset($configData['navbarBgColor'])){{$configData['navbarBgColor']}}@endif">
  <div class="navbar-wrapper box-shadow-1">
    <div class="navbar-container content">
      <div class="navbar-collapse" id="navbar-mobile">
        <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
{{--          <ul class="nav navbar-nav">--}}
{{--            <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon bx bx-menu"></i></a></li>--}}
{{--          </ul>--}}
{{--          <ul class="nav navbar-nav bookmark-icons">--}}
            @livewire('tenant.partials.retrieve-anydesk-navbar')
            <div class="d-flex justify-content-center align-items-center" style="margin-left: 10px;border: 2px solid #0544d3;border-radius: 50%;width: 22px;height: 22px;">
                <a class="btn btn-sm btn-icon p-0" title="{{ __('locale.Upload Connection File') }}" href="{{ route('file.upload') }}" >
                    <i class="bx bx-upload" style="color: #0e459e;font-size: 14px;font-weight: bolder;"></i>
                </a>
            </div>
{{--            <li class="nav-item d-none d-lg-block"><a class="nav-link" href="{{route('app-email')}}" data-toggle="tooltip" data-placement="top" title="{{ __('locale.Email') }}"><i class="ficon bx bx-envelope"></i></a></li>--}}
{{--            <li class="nav-item d-none d-lg-block"><a class="nav-link" href="{{route('app-chat')}}" data-toggle="tooltip" data-placement="top" title="{{ __('locale.Chat') }}"><i class="ficon bx bx-chat"></i></a></li>--}}
{{--              <li class="nav-item d-none d-lg-block"><a class="nav-link" href="{{route('app-todo')}}" data-toggle="tooltip" data-placement="top" title="{{ __('locale.Todo') }}"><i class="ficon bx bxs-spreadsheet"></i></a></li> --}}
{{--            <li class="nav-item d-none d-lg-block"><a class="nav-link" href="{{route('app-calendar')}}" data-toggle="tooltip" data-placement="top" title="{{ __('locale.Calendar') }}"><i class="ficon bx bx-calendar-alt"></i></a></li>--}}
{{--          </ul>--}}
        </div>
        <ul class="nav navbar-nav float-right">
          @php $locale = session()->get('locale'); @endphp
          <li class="dropdown dropdown-language nav-item">
            <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @switch($locale)
                    @case('de')
                    <i class="flag-icon flag-icon-de"></i><span class="selected-language">{{ __('locale.German') }}</span>
                    @break
                    @case('en')
                    <i class="flag-icon flag-icon-us"></i><span class="selected-language">{{ __('locale.English') }}</span>
                    @break
                    @default
                    <i class="flag-icon flag-icon-de"></i><span class="selected-language">{{ __('locale.German') }}</span>
                @endswitch
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                <a class="dropdown-item" href="{{url('lang/de')}}" data-language="de">
                    <i class="flag-icon flag-icon-de mr-50"></i> {{ __('locale.German') }}
                </a>
                <a class="dropdown-item" href="{{url('lang/en')}}" data-language="en">
                    <i class="flag-icon flag-icon-us mr-50"></i> {{ __('locale.English') }}
                </a>
            </div>
          </li>
          <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i class="ficon bx bx-fullscreen"></i></a></li>
          <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon bx bx-search"></i></a>
              <div class="search-input" id="search-input">
                @livewire('tenant.customer-select-component')
              </div>
          </li>
            <br>
          <li class="dropdown dropdown-notification nav-item">
            @livewire('tenant.user-notifications-navbar')
          </li>
          <li class="dropdown dropdown-user nav-item">
            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
              @livewire('navbar-profile-photo-component')
            </a>
            <div class="dropdown-menu dropdown-menu-right pb-0">
              <a class="dropdown-item" href="{{route('account.settings')}}">
                <i class="bx bx-user mr-50"></i> {{ __('locale.SETTINGS') }}
              </a>
              <a class="dropdown-item" href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('tenant_logout').submit();">
                  <i class="bx bx-power-off mr-50"></i> {{ __('locale.LOGOUT') }}
              </a>
                <form id="tenant_logout" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
              <div class="dropdown-divider mb-0"></div>
              <a class="dropdown-item disabled">
                 <i class="bx bx-git-repo-forked mr-50"></i> @version('version-only')
              </a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>

@section('page-scripts')
<script>

    var e = document.getElementById('app_content_overlay');
    var observer = new MutationObserver(function (event) {
        if(!$('#app_content_overlay').hasClass('show-overlay')) {
            Livewire.emit('closeSearchPanel');
        }
    })
    observer.observe(e, {
        attributes: true,
        attributeFilter: ['class'],
        childList: false,
        characterData: false
    })
    $(document).click(function (event){
        var $target = $(event.target);
        if(!$target.closest($('.nav-search')).length)
            if ($('#search-input').hasClass('open')) {
                $('#input-for-search').blur();
                $('#search-input').removeClass('open');
            }
    })

    window.addEventListener('showCollect', event => {
        $('#app_content_overlay').addClass('show-overlay');
        $('#searchCustomerList').addClass('show');
    });
    window.addEventListener('hideCollect', event => {
        $('#app_content_overlay').removeClass('show-overlay');
        $('#searchCustomerList').removeClass('show');
    });

</script>
@endsection
