<label for="users" class="form-label">{{ __('locale.User') }}</label>
<select class="form-select form-select-sm print-view" id="users" aria-label="Select user">
    <option value="">{{ __('locale.All') }}</option>
    @if(!empty($users))
        @foreach($users as $value => $key)
            @if($key)
                <option value="{{ $key }}" {{ $selectedUser == $key ? 'selected="true"' : '' }}>
                    <p>{{ trim($value) != '' ? $value : __('locale.Not named').'('.$key.')' }}</p>
                </option>
            @endif
        @endforeach
    @endif
</select>
