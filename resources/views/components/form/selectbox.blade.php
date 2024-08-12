<div class="form-group {{ $groupClass ?? '' }}">
    @if (!empty($labelName))
    <label for="{{ $name }}" class="{{ $required ?? '' }}">{{ $labelName }}</label>
    @endif
    <select name="{{ $name }}" @if(!empty($dataSearch)) data-live-search="{{ $dataSearch }}" @endif id="{{ $name }}" class="form-control form-control-sm {{ $class ?? '' }}" @if(!empty($onchange)) onchange="{{ $onchange }}" @endif>
        {{ $slot }}
    </select>
    @isset($error)
        @error($error)
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
    @endisset
</div>
