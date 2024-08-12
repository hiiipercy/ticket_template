<div class="form-group {{ $groupClass ?? '' }}">
    <label for="{{ $name }}" class="{{ $required ?? '' }}">{{ $labelName }}</label>
    <textarea class="form-control form-control-sm {{ $class ?? '' }}" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder ?? '' }}" rows="{{ $rows ?? '3' }}">{{ $value ?? '' }}</textarea>
    @isset($helpText)
        <small  class="form-text text-muted d-block" style="background: #caff0170;color: #000;"> {{ $helpText }} </small>
    @endisset
    
    @isset($error)
        @error($error)
            <small class="text-danger d-block" >{{ $message }}</small>
        @enderror
    @endisset
</div>
