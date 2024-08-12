@extends('layouts.app')
@section('title', $siteTitle)
@push('styles')

@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <div class="box-tools pull-right">
                    <a href="{{ route('app.branch.import') }}" class="btn btn-sm btn-info rounded-0"><i class="fa fa-upload fa-sm"></i> Import</a>
                    <button type="button" class="btn btn-sm btn-primary rounded-0" onclick="showFormModal('New Branch', 'Save')"><i class="fa fa-plus fa-sm"></i> Add Branch</button>
                </div>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-hover table-striped" id="branch-datatable">
                    <thead>
                        <th>
                            <div class="form-checkbox">
                                <input type="checkbox" class="form-check-input" id="select_all" onclick="select_all()">
                                <label class="form-check-label" for="select_all"></label>
                            </div>
                        </th>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created by</th>
                        <th>Created at</th>
                        <th class="text-right">Action</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('branch.update_or_store')
@endsection
@push('scripts')
    <script>
        table = $('#branch-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [], //Initial no order
            bInfo: true, //TO show the total number of data
            bFilter: false, //For datatable default search box show/hide
            ordering: false,
            lengthMenu: [
                [5, 10, 15, 25, 50, 100, 200],
                [5, 10, 15, 25, 50, 100, 200]
            ],
            pageLength: "{{ TABLE_PAGE_LENGTH }}", //number of data show per page
            ajax: {
                url: "{{ route('app.branch.index') }}",
                type: "GET",
                dataType: "JSON",
                data: function(d) {
                    d._token = _token;
                    d.search = $('input[name="search_here"]').val();
                },
            },
            columns: [
                {data: 'bulk_check'},
                {data: 'DT_RowIndex'},
                {data: 'name'},
                {data: 'status'},
                {data: 'created_by'},
                {data: 'created_at'},
                {data: 'action'}
            ],
            language: {
                processing: '<img src="{{ asset("img/table-loading.svg") }}">',
                emptyTable: '<strong class="text-danger">No Data Found</strong>',
                infoEmpty: '',
                zeroRecords: '<strong class="text-danger">No Data Found</strong>',
                oPaginate: {
                    sPrevious: "Previous", // This is the link to the previous page
                    sNext: "Next", // This is the link to the next page
                },
                lengthMenu: `<div class='d-flex align-items-center w-100 justify-content-between'>_MENU_
                        <button type='button' style='min-width: 110px;' class='btn btn-sm btn-danger d-none rounded-0 delete_btn ml-2 px-3' onclick='multi_delete()'>Bulk Delete</button>

                        <input name='search_here' class='form-control-sm form-control ml-2' placeholder="Search here..." autocomplete="off"/>
                    </div>`,
            }
        });

        // save 
        $(document).on('click', '#save-btn', function () {
            var form_data = document.getElementById('store_or_update_form');
            var form = new FormData(form_data);
            let url = "{{ route('app.branch.update-or-store') }}";
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
                            $('#store_or_update_modal').modal('hide');
                        }
                    }

                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        });

        // edit data
        $(document).on('click', '.edit_data', function () {
            let id = $(this).data('id');
            $('#store_or_update_form')[0].reset();
            $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
            $('#store_or_update_form').find('.error').remove();
            if (id) {
                $.ajax({
                    url: "{{ route('app.branch.edit') }}",
                    type: "POST",
                    data: { id: id,_token: _token},
                    dataType: "JSON",
                    success: function (data) {
                        $('#store_or_update_form #update_id').val(data.data.id);
                        $('#store_or_update_form #name').val(data.data.name);
                        $('#store_or_update_form #status').val(data.data.status);

                        $('#store_or_update_modal').modal({
                            keyboard: false,
                            backdrop: 'static',
                        });
                        $('#store_or_update_modal .modal-title').html(
                            '<span>Edit - ' + data.data.name + '</span>');
                        $('#store_or_update_modal #save-btn').html('<span></span> Update');
                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    }
                });
            }
        });

        // status changes
        $(document).on('click','.change_status', function(){
            var id = $(this).data('id');
            var name = $(this).data('name');
            var status = $(this).data('status');
            var url = "{{ route('app.branch.status-change') }}"
            change_status(id,status,name,url);
        });

        // single delete
        $(document).on('click', '.delete_data', function () {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let row = table.row($(this).parent('tr'));
            let url = "{{ route('app.branch.delete') }}";
            delete_data(id,url,row,name);
        });

        // multi delete
        function multi_delete(){
            let ids = [];
            let rows;
            $('.select_data:checked').each(function(){
                ids.push($(this).val());
                rows = table.rows($('.select_data:checked').parents('tr'));
            });

            if(ids.length == 0){
                Swal.fire({
                    type:'error',
                    title:'Error',
                    text:'Please checked at least one row of table!',
                    icon: 'warning',
                });
            }else{
                let url = "{{ route('app.branch.bulk-delete') }}";
                bulk_delete(ids,url,rows);
            }
        }
    </script>
@endpush
