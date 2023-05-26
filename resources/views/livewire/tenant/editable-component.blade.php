<div wire:key="{{ $model_id }}">
    @if($input_type == 'text')
        <form wire:submit.prevent="submit">
            @csrf

            <div class="width-45-per">
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control timePicker"
                        style="width: unset"
                        id="{{'planned_operating_time_' . $model_id }}"
                        data-type="time"
                        placeholder="00:00"
                        wire:model.debounce.500ms="selected_field_value"
                        wire:click="setSelectedValue('planned_operating_time', '{{ $model_id }}', '{{ $selected_field_value }}')"
                        readonly
                    />
                    {{-- <div class="editable-actions">
                        <button title="{{ __('locale.Save') }}" class="btn btn-dark save-btn">
                            <i class="bx bx-check"></i>
                        </button>
                        {{--
                            <button type="button" title="{{ __('locale.Cancel') }}" class="btn btn-sm btn-danger cancel-btn"
                                    wire:click="showEditableInput('planned_operating_time','non_match_able_id','N/A')">
                                <i class="bx bx-x"></i>
                            </button>
                        --}}
                    {{--
                </div> --}}
                </div>
            </div>
            @error('planned_operating_time') <span class="error" style="color: #ff0000">{{ $message }}</span> @enderror
        </form>
    @else
        {{-- Other input type code --}}
    @endif
</div>
