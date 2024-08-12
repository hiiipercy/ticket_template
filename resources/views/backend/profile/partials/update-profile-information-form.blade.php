<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        {{-- <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p> --}}
    </header>

    {{-- <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form> --}}

    <form method="post" action="{{ route('app.profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')
        
        <div>
            <label for="name" >Name</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name')
                <small class="mt-2 text-danger">{{ $message }}</small>
            @enderror
            
        </div>

        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email')
                <small class="mt-2 text-danger">{{ $message }}</small>
            @enderror

            
        </div>

        <div>
            <label for="current_password">Current Password</label>
            <input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
            @error(('current_password'))
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password">New Password</label>
            <input id="password" name="password" type="password" class="form-control" autocomplete="new-password" />
            @error(('password'))
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
            @error(('password_confirmation'))
                <p class="text-danger">{{ $message }}</p>
            @enderror
                
        </div>

        <div class="pull-right mt-5">
            <button type="submit">{{ __('Save') }}</button>
        </div>
    </form>
</section>
