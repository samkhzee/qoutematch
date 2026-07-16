<div class="form-group">
    <label for="disputeType" class="form--label">@lang('Issue Type')</label>
    <select class="form--control form-select" id="disputeType" name="dispute_type" required>
        @foreach (App\Models\Dispute::TYPES as $value => $label)
            <option value="{{ $value }}" @selected(old('dispute_type') === $value)>@lang($label)</option>
        @endforeach
    </select>
</div>
