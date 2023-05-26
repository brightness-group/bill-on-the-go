<div>
    @foreach($list as $item)
        @if($item['isUnread'])
            <div class="d-flex justify-content-between read-notification cursor-pointer" wire:key="unread-{{$item['type']}}">
                <div class="media d-flex align-items-center" wire:click="markAsRead('{{!empty($item['id']) ? $item['id'] : ''}}')">
                    <div class="media-left pr-0">
                        <div class="avatar mr-1 m-0"><img src="{{asset($item['imgUrl'])}}" alt="avatar" height="39" width="39"></div>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading text-justify"><span class="text-bold-500">{{ __('locale.Notification') }}:</span>@if(key_exists('email',$item)&&key_exists('username',$item)) {{ __('locale.' . $item['message'],['email'=>$item['email'],'username'=>$item['username']]) }} @endif</h6><small class="notification-text">You have {{$item['count']}} unread messages</small>
                    </div>
                </div>
            </div>
        @else
            <div class="d-flex justify-content-between cursor-pointer" wire:key="read-{{$item['type']}}">
                <div class="media d-flex align-items-center">
                    <div class="media-left pr-0">
                        <div class="avatar mr-1 m-0"><img src="{{asset($item['imgUrl'])}}" alt="avatar" height="39" width="39"></div>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading text-justify"><span class="text-bold-500">{{ __('locale.Notification') }}:</span>@if(key_exists('email',$item)&&key_exists('username',$item)) {{ __('locale.' . $item['message'],['email'=>$item['email'],'username'=>$item['username']]) }} @endif</h6><small class="notification-text">You have {{$item['count']}} unread messages</small>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
