<div class="form-group {{ $groupClass ?? '' }}">
    @if (!empty($labelName))
    <label for="{{ $name }}" class="{{ $required ?? '' }}">{{ $labelName }}</label>
    @endif
    <input type="{{ $type ?? 'text' }}" class="form-control form-control-sm {{ $class ?? '' }}" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder ?? '' }}" value="{{ $value ?? '' }}"  autocomplete="off">
    @if (!empty($optional))
        <p class="optional-text mb-0">{{ $optional }}</p>
    @endif
    @isset($error)
        @error($error)
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
    @endisset
</div>
