<label for="tariffs" class="form-label">{{ __('locale.Tariff') }}</label>
<select class="form-select form-select-sm print-view" id="tariffs" aria-label="Select tariff">
    <option value="">{{ __('locale.All') }}</option>
    @if(!empty($tariffs))
        @foreach($tariffs as $item)
            <option class="option-chars-limited" value="{{ $item['id'] }}" {{ $selectedTariff == $item['id'] ? 'selected="true"' : '' }}>
                <p>{{ ucwords(strtolower($item['tariff_name'])) }}</p></option>
        @endforeach
    @endif
</select>
