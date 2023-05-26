<div>
    <div class="user-nav d-sm-flex d-none mt-sm-1 p-0">
        <span class="user-name">{{ auth()->user()->name }}</span>
    </div>
    @if(auth()->user()->profile_photo_path)
        <span><img class="round" src="{{ url(auth()->user()->profile_photo_path) }}" alt="avatar" height="40" width="40"></span>
    @else
        <span><img class="round" src="{{ asset('images/backgrounds/empty.jpg') }}" alt="avatar" height="40" width="40"></span>
    @endif
</div>
