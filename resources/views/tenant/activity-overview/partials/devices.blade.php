<label for="devices" class="form-label">{{ __('locale.Device') }}</label>
<select class="form-select form-select-sm print-view" id="devices" aria-label="Select device">
    <option value="">{{ __('locale.All') }}</option>
    @if(!empty($devices))
        @foreach($devices as $key => $value)
            <option class="option-chars-limited" value="{{ $key }}" {{ $selectedDevice == $key ? 'selected="true"' : '' }}>
                <p>{{ trim($value) != '' ? ucwords(strtolower($value)) : __('locale.Not named').'('.$key.')' }}</p>
            </option>
        @endforeach
    @endif
</select>
