@extends('layouts.app')
@section('title', $siteTitle)
@push('styles')
<style>
    .row .col-md-6.mx-auto {
        float: inherit !important;
    }
    .column,
    .validation{
        font-weight: 600;
    }
</style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="box box-solid">
                <div class="box-body">
                    <form method="POST" enctype="multipart/form-data" id="import_form">
                        @csrf

                        <x-form.inputbox name="import_file" type="file" labelName="Import File" optional="Choose file with mime: (xlsx)" required="required" />

                        <button type="button" class="btn btn-sm btn-block btn-primary" id="save-btn"><span></span> Import</button>
                        <a href="https://docs.google.com/spreadsheets/d/1tBfL51l5hS9EieEs-7yTOOyo3zzXi779z-3RgQ0cGKI/edit?usp=sharing" class="btn btn-sm btn-block bg-secondary" target="_blank"><i class="fa fa-download fa-sm"></i> Demo xlsx Tempate</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h4 class="box-title">Rules</h4>
                </div>
                <div class="box-body">
                    <table class="table-bordered table table-sm">
                        <tr class="bg-info">
                            <td class="column text-light">Column Name</td>
                            <td class="validation text-light">Validation</td>
                        </tr>
                        <tr>
                            <td>Branch Name</td>
                            <td>required|string</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>required|in:1,2|default:1|unique:branchs,name</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).on('click','button#save-btn',function(e){
            e.preventDefault();
            var form = document.getElementById('import_form');
            var form_data = new FormData(form);

            $.ajax({
                type: "POST",
                url: "{{ route('app.branch.import-data') }}",
                data: form_data,
                dataType: "JSON",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function(){
                    $('button#save-btn span').addClass('spinner-border spinner-border-sm text-light');
                },
                complete: function(){
                    $('button#save-btn span').removeClass('spinner-border spinner-border-sm text-light');
                },
                success: function (data) {
                    $('#import_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            $('#import_form #' + key).parent().append('<small class="text-danger error d-block">'+value+'</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            setInterval(() => {
                                window.location.href = "{{ route('app.branch.index') }}";
                            }, 1000);
                        }
                    }
                }
            });
        });
    </script>
@endpush
