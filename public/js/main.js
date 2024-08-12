// show modal
function showFormModal(modal_title, btn_text) {
    $('#store_or_update_modal').modal({
        keyboard: false,
        backdrop: 'static'
    });
    $('#store_or_update_form')[0].reset();
    $('#store_or_update_form').find('.disabled').attr('disabled', false);
    $('#store_or_update_form #update_id').val('');
    $('#store_or_update_form #schedule_id').val('');
    $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
    $('#store_or_update_form').find('.error').remove();
    $('#store_or_update_form').find('.schedule-error').remove();
    $('#store_or_update_modal .modal-title').html(modal_title);
    $('#store_or_update_modal #save-btn').html('<span></span> '+btn_text);
}

// update or create menu
function store_or_update_data(method, url, formData) {
    $.ajax({
        url: url,
        type: "POST",
        data: formData,
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
                    $('#store_or_update_form input#' + key).addClass('is-invalid');
                    $('#store_or_update_form textarea#' + key).addClass('is-invalid');
                    $('#store_or_update_form select#' + key).addClass('is-invalid');
                    $('#store_or_update_form #' + key).parent().append(
                        '<small class="error d-block text-left text-danger">' + value + '</small>');
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
}

// single delete
function delete_data(id,url,row,name) {
    Swal.fire({
        title: 'Are you sure to delete ' + name + ' data?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Confirm',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: url,
                type: "POST",
                data: {id:id,_token:_token},
                dataType: "JSON",
            }).done(function (response) {
                if (response.status == "success") {
                    Swal.fire("Deleted", response.message, "success").then(function () {
                        table.row(row).remove().draw(false);
                    });
                }

                if (response.status == "error") {
                    Swal.fire('Oops...', response.message, "error");
                }
            }).fail(function () {
                Swal.fire('Oops...', "Somthing went wrong with ajax!", "error");
            });
        }
    });
}

// bulk checked
function select_all() {
    if($('#select_all:checked').length == 1){
        $('.select_data').prop('checked',true);
        if($('.select_data:checked').length >= 1)
        {
            $('.delete_btn').removeClass('d-none');
        }
    }else{
        $('.select_data').prop('checked',false);
        $('.delete_btn').addClass('d-none');
    }
}

// single checkbox
function select_single_item(id){
    var total = $('.select_data').length; //count total checkbox
    var total_checked =  $('.select_data:checked').length;//count total checked checkbox
    (total == total_checked) ? $('#select_all').prop('checked',true) : $('#select_all').prop('checked',false);
    (total_checked > 0) ?  $('.delete_btn').removeClass('d-none') : $('.delete_btn').addClass('d-none');
}

// multi delete
function bulk_delete(ids,url,rows, title = 'Are you sure to delete all checked data?'){
    Swal.fire({
        title: title,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Confirm',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    ids: ids,
                    _token: _token
                },
                dataType: "JSON",
            }).done(function (response) {
                if(response.status == "success") {
                    Swal.fire("Deleted", response.message, "success").then(function () {
                        table.rows(rows).remove().draw(false);
                        $('#select_all').prop('checked',false);
                        $('.delete_btn').addClass('d-none');
                    });
                }
                if (response.status == "error") {
                    Swal.fire('Oops...', response.message, "error");
                }
            }).fail(function () {
                Swal.fire('Oops...', "Somthing went wrong with ajax!", "error");
            });
        }
    });
}

// multi action
function bulk_action(ids,action_type,assign_user = null,url,rows,title = 'Are you sure to delete all checked data?'){
    Swal.fire({
        title: title,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Confirm',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    ids: ids,
                    action_type: action_type,
                    assign_user: assign_user,
                    _token: _token
                },
                dataType: "JSON",
            }).done(function (response) {
                if(response.status == "success") {
                    Swal.fire("Deleted", response.message, "success").then(function () {
                        table.rows(rows).remove().draw(false);
                        $('#select_all').prop('checked',false);
                        $('select#action_type').val('');
                    });
                }
                if (response.status == "error") {
                    Swal.fire('Oops...', response.message, "error");
                }
            }).fail(function () {
                Swal.fire('Oops...', "Somthing went wrong with ajax!", "error");
            });
        }
    });
}

// delete item
function deleteItem(id){
    Swal.fire({
        title: 'Are you sure to delete?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Confirm',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            document.getElementById('delete_form_'+id).submit();
        }
    });
}

// change status
function change_status(id,status,name,url){
    Swal.fire({
        title: 'Are you sure to change ' + name + ' status?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Confirm',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: url,
                type: "POST",
                data: { id: id,status:status, _token: _token},
                dataType: "JSON",
            }).done(function (response) {
                if (response.status == "success") {
                    Swal.fire("Status Changed", response.message, "success").then(function () {
                        table.ajax.reload(null, false);
                    });
                }
                if (response.status == "error") {
                    Swal.fire('Oops...', response.message, "error");
                }
            }).fail(function () {
                Swal.fire('Oops...', "Somthing went wrong with ajax!", "error");
            });
        }
    });
}

// import modal
function showImportFormModal(modal_title,btn_title){
    $('#import_form').find('.error').remove();
    $('#import_form')[0].reset();
    $('#import_modal .modal-title').text(modal_title);
    $('#import_modal #import-btn').html('<span></span> '+btn_title);
    $('#import_modal').modal({
        keyboard: false,
        backdrop: 'static'
    });
}

// import data
function import_form_data(url,form_data){
    $.ajax({
        type: "POST",
        url: url,
        data: form_data,
        dataType: "json",
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function(){
            $('button#import-btn span').addClass('spinner-border spinner-border-sm text-light');
        },
        complete: function(){
            $('button#import-btn span').removeClass('spinner-border spinner-border-sm text-light');
        },
        success: function (response) {
            $('#import_form').find('.error').remove();
            if (response.status == false) {
                $.each(response.errors, function (key, value) {
                    $('#import_form input#' + key).parent().append('<small class="text-danger error d-block">'+value+'</small>');
                });
            }else{
                if (response.status == 'success') {
                    $('#import_modal').modal('hide');
                    table.ajax.reload();
                    notification(response.status,response.message);
                }else{
                    notification(response.status,response.message);
                }
            }
        }
    });
}
