@extends('backend.layouts.master')
@section('page_title', 'Profile')
@section('page_sub_title', 'Edit')

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-5">
            <div class="box box-solid">
                <div class="box-body">
                    <div class="max-w-xl">
                        @include('backend.profile.partials.update-profile-information-form')
                        @include('backend.profile.partials.update-password-form')
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if (session('msg'))
@push('js')
    <script>
        Swal.fire({
            position: "top-end",
            icon: "{{ session('cls') }}",
            toast: true,
            title: "{{ session('msg') }}",
            showConfirmButton: false,
            timer: 5000
        });
    </script>
@endpush
@endif

{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
