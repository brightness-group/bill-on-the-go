@section('title',__('locale.Todo'))
{{-- vendor styles --}}
@section('vendor-style')
@endsection
{{-- page style --}}
@section('page-style')
@endsection
@section('custom_css')
@endsection
<div>
    <div class="card">
        <h5 class="card-header"> @lang('locale.Todo')</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th></th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @if(count($todo) > 0)
                    @foreach($todo as $item)
                        <tr>
                            <td>
                                <p class="todo-title mx-50 m-0 truncate cursor-pointer"
                                   title="{{__('locale.'.$item->todo)}}"
                                   @if($item->type == 'tariff-overview-conflicts')
                                       onclick="window.location='{{route('account.settings').'#account-vertical-tariff'}}'"
                                   @elseif(!empty($item->type))
                                       wire:click="gotoTodoAction('{{$item->type}}')"
                                    @endif >
                                    {{__('locale.'.$item->todo)}}
                                </p>
                            </td>
                            <td>
                                @if(!empty($item->tags))
                                    @foreach($item->tags as $tag)
                                        <span class="badge rounded-pill bg-{{$tag['type']}} me-1">
                                            {{__('locale.'.$tag['tag'])}}
                                        </span>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else

                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@section('vendor-script')
@endsection

@section('custom_scripts')
    <script>
        {{-- Toastr notification listener --}}
        window.addEventListener('showToastrSuccess', event => {
            toastr.success('', event.detail.message).css("width", "fit-content")
        });
        window.addEventListener('showToastrError', event => {
            toastr.error('', event.detail.message).css("width", "fit-content")
        });
    </script>
@endsection
