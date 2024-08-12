@extends('backend.layouts.app')
@section('title', $siteTitle)
@push('styles')

@endpush
@section('content')
<div class="row d-flex justify-content-center">
    <div class="col-md-6">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h4 class="d-flex my-0 align-items-center justify-content-between">Why Us
                    <a href="{{ route('app.index') }}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i>
                        Back</a>
                </h4>
            </div>
            <div class="box-body">
                <form id="store_or_update_form" method="POST">
                    @csrf
                    <input type="hidden" name="update_id" id="update_id" value="{{ $whyus->id ?? '' }}" >
                    <x-form.inputbox name="title" labelName="Title" value="{{ $whyus->title ?? '' }}" required="required"/>
                    <x-form.inputbox name="subtitle" labelName="Sub Title" value="{{ $whyus->subtitle ?? '' }}" required="required"/>
                    <x-form.inputbox name="list_1" labelName="List Number" placeholder="01" value="{{ $whyus->list_1 ?? '' }}" required="required"/>
                    <x-form.inputbox name="list_title_1" labelName="List Title" value="{{ $whyus->list_title_1 ?? '' }}" required="required"/>
                    <x-form.inputbox name="list_description_1" labelName="Description Title" value="{{ $whyus->list_description_1 ?? '' }}" required="required"/>
                    <x-form.inputbox name="list_2" labelName="List Number" placeholder="01" value="{{ $whyus->list_2 ?? '' }}" required="required"/>
                    <x-form.inputbox name="list_title_2" labelName="List Title" value="{{ $whyus->list_title_2 ?? '' }}" required="required"/>
                    <x-form.inputbox name="list_description_2" labelName="Description Title" value="{{ $whyus->list_description_2 ?? '' }}" required="required"/>
                    <x-form.inputbox name="list_3" labelName="List Number" placeholder="01" value="{{ $whyus->list_3 ?? '' }}" required="required"/>
                    <x-form.inputbox name="list_title_3" labelName="List Title" value="{{ $whyus->list_title_3 ?? '' }}" required="required"/>
                    <x-form.inputbox name="list_description_3" labelName="Description Title" value="{{ $whyus->list_description_3 ?? '' }}" required="required"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" id="save-btn">Save</button>
            </div>
        </div>
    </div>
</div>


@endsection
@push('scripts')
    <script>

        // save 
        $(document).on('click', '#save-btn', function () {
            var form_data = document.getElementById('store_or_update_form');
            var form = new FormData(form_data);
            let url = "{{ route('app.whyus.update-or-store') }}";
            let id = $('#update_id').val();
            let method;
            if (id) {
                method = 'update';
            } else {
                method = 'add';
            }
            $.ajax({
                url: url,
                type: "POST",
                data: form,
                dataType: "JSON",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function(){
                    $('#save-btn span').addClass('spinner-border spinner-border-sm text-light');
                },
                complete: function(){
                    $('#save-btn span').removeClass('spinner-border spinner-border-sm text-light');
                },
                success: function (data) {
                    $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                    $('#store_or_update_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            $('#store_or_update_form #' + key).addClass('is-invalid');
                            $('#store_or_update_form #' + key).parent().append('<small class="text-danger d-block error">'+value+'</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            if (method == 'update') {
                                table.ajax.reload(null, false);
                            } else {
                                table.ajax.reload();
                            }
                            // $('#store_or_update_modal').modal('hide');
                        }
                    }

                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        });
    </script>
@endpush
