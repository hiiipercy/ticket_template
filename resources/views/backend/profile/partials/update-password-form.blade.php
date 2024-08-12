<section>
    <header>
        <h3 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h3>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        

        <div class="pull-right mt-5">
            <button type="submit">{{ __('Save') }}</button>
        </div>
    </form>
</section>
