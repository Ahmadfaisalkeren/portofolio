@extends('backend.master')

@section('title', 'Contact')

@section('content')
    <div class="container">
        <h1>Contact CMS</h1>
        <a class="btn btn-sm btn-success mb-2" href="javascript:void(0)" id="createContact"> Create Contact</a>
        <table id="contactTable" class="table table-striped table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Contact</th>
                    <th>Link</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

    @include('backend.contact.form')

@endsection

@push('scripts')
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('contact.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'contact',
                        name: 'contact'
                    },
                    {
                        data: 'link',
                        name: 'link'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#createContact').click(function() {
                $('#saveBtnNew').show();
                $('#saveBtnEdit').hide();
                $('#saveBtnNew').val("create-contact");
                $('#contact_id').val('');
                $('#contactForm').trigger("reset");
                $('#modelHeading').html("Create New Contact");
                // Set the modal size to modal-xl
                $('#ajaxModel .modal-dialog');
                $('#ajaxModel').modal('show');
            });

            $('#saveBtnNew').click(function(e) {
                e.preventDefault();

                var formData = new FormData($("#contactForm")[0]);
                // console.log(formData.get('image'));

                var url = "{{ route('contact.store') }}";
                var method = 'POST';

                // Add validation checks here

                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    url: url,
                    type: method,
                    dataType: 'json',
                    success: function(data) {
                        $('#contactForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                        Swal.fire({
                            title: "Success!",
                            text: "The contact has been saved successfully.",
                            icon: "success",
                            timer: 3000
                        });
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtnNew').html('Save Changes');
                    }
                });
            });

            $('body').on('click', '.editContact', function() {
                var contact_id = $(this).data('id');
                $.get("{{ route('contact.index') }}" + '/' + contact_id + '/edit', function(data) {
                    $('#modelHeading').html("Edit Contact");
                    $('#saveBtnNew').hide();
                    $('#saveBtnEdit').show();
                    $('#saveBtnEdit').val("edit-contact");
                    // Set the modal size to modal-xl
                    $('#ajaxModel .modal-dialog');
                    $('#ajaxModel').modal('show');
                    $('#contact_id').val(data.id);
                    $('#contact').val(data.contact);
                    $('#link').val(data.link);
                    $('#image').val(data.image);
                });
            });

            $(document).ready(function() {
                var currentImage = $('#current-image');
                var imageInput = $('#image');

                // When the user selects a new image, show a preview of it
                imageInput.on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        currentImage.attr('src', e.target.result);
                        currentImage.show();
                    };

                    reader.readAsDataURL(file);
                });

                // When the page loads, show the current image if there is one
                if (currentImage.attr('src') !== '') {
                    currentImage.show();
                }
            });

            $('#saveBtnEdit').click(function(e) {
                e.preventDefault();

                var contact_id = $('#contact_id').val();
                var url = "{{ route('contact.update', ':id') }}".replace(':id', contact_id);
                var method = 'PUT';

                // Add validation checks here

                var formData = new FormData($('#contactForm')[0]);
                formData.append('_method', method);

                $.ajax({
                    data: formData,
                    url: url,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#contactForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                        Swal.fire({
                            title: "Success!",
                            text: "The contact has been updated successfully.",
                            icon: "success",
                            timer: 3000
                        });
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtnEdit').html('Save Changes');
                    }
                });
            });
        });

        function deleteData(url) {
            Swal.fire({
                title: 'Yakin ingin menghapus data terpilih?',
                text: 'Anda tidak dapat mengembalikan data yang telah dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                            '_token': $('[name=csrf-token]').attr('content'),
                            '_method': 'delete'
                        })
                        .done((response) => {
                            const dataTable = $('.data-table').DataTable();
                            dataTable.row(`[data-id="${response.id}"]`).remove().draw();
                            Swal.fire({
                                title: 'Data berhasil dihapus!',
                                icon: 'success',
                            });
                        })
                        .fail((errors) => {
                            alert('Tidak dapat menghapus data');
                            return;
                        });
                }
            });
        }
    </script>
@endpush
