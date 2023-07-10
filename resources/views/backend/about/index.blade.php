@extends('backend.master')

@section('title', 'About')

@section('content')
    <div class="container">
        <h1>About CMS</h1>
        <a class="btn btn-sm btn-success mb-2" href="javascript:void(0)" id="createAbout"> Create About</a>
        <table id="aboutTable" class="table table-striped table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

    @include('backend.about.form')

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
                ajax: "{{ route('about.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'description',
                        name: 'description'
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

            $('#createAbout').click(function() {
                $('#saveBtnNew').show();
                $('#saveBtnEdit').hide();
                $('#saveBtnNew').val("create-about");
                $('#about_id').val('');
                $('#aboutForm').trigger("reset");
                $('#modelHeading').html("Create New About");
                // Set the modal size to modal-xl
                $('#ajaxModel .modal-dialog');
                $('#ajaxModel').modal('show');
            });

            $('#saveBtnNew').click(function(e) {
                e.preventDefault();

                var formData = new FormData($("#aboutForm")[0]);
                // console.log(formData.get('image'));

                var url = "{{ route('about.store') }}";
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
                        $('#aboutForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                        Swal.fire({
                            title: "Success!",
                            text: "The about has been saved successfully.",
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

            $('body').on('click', '.editAbout', function() {
                var about_id = $(this).data('id');
                $.get("{{ route('about.index') }}" + '/' + about_id + '/edit', function(data) {
                    $('#modelHeading').html("Edit About");
                    $('#saveBtnNew').hide();
                    $('#saveBtnEdit').show();
                    $('#saveBtnEdit').val("edit-about");
                    // Set the modal size to modal-xl
                    $('#ajaxModel .modal-dialog');
                    $('#ajaxModel').modal('show');
                    $('#about_id').val(data.id);
                    $('#description').val(data.description);
                    $('#description_2').val(data.description_2);
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

                var about_id = $('#about_id').val();
                var url = "{{ route('about.update', ':id') }}".replace(':id', about_id);
                var method = 'PUT';

                // Add validation checks here

                var formData = new FormData($('#aboutForm')[0]);
                formData.append('_method', method);

                $.ajax({
                    data: formData,
                    url: url,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#aboutForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                        Swal.fire({
                            title: "Success!",
                            text: "The about has been updated successfully.",
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
