@extends('backend.master')

@section('title', 'Skills')

@section('content')
    <div class="container">
        <h1>Skills CMS</h1>
        <a class="btn btn-sm btn-success mb-2" href="javascript:void(0)" id="createSkills"> Create Skills</a>
        <table id="skillsTable" class="table table-striped table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Skill</th>
                    <th>Skill Detail</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

    @include('backend.skills.form')

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
                autoWidth: false,
                ajax: "{{ route('skills.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'skill',
                        name: 'skill'
                    },
                    {
                        data: 'skill_detail',
                        name: 'skill_detail'
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

            $('#createSkills').click(function() {
                $('#saveBtnNew').show();
                $('#saveBtnEdit').hide();
                $('#saveBtnNew').val("create-skills");
                $('#skills_id').val('');
                $('#skillsForm').trigger("reset");
                $('#modelHeading').html("Create New Skills");
                // Set the modal size to modal-xl
                $('#ajaxModel .modal-dialog');
                $('#ajaxModel').modal('show');
            });

            $('#saveBtnNew').click(function(e) {
                e.preventDefault();

                var formData = new FormData($("#skillsForm")[0]);
                // console.log(formData.get('image'));

                var url = "{{ route('skills.store') }}";
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
                        $('#skillsForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                        Swal.fire({
                            title: "Success!",
                            text: "The skills has been saved successfully.",
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

            $('body').on('click', '.editSkills', function() {
                var skills_id = $(this).data('id');
                $.get("{{ route('skills.index') }}" + '/' + skills_id + '/edit', function(data) {
                    $('#modelHeading').html("Edit Skills");
                    $('#saveBtnNew').hide();
                    $('#saveBtnEdit').show();
                    $('#saveBtnEdit').val("edit-skills");
                    // Set the modal size to modal-xl
                    $('#ajaxModel .modal-dialog');
                    $('#ajaxModel').modal('show');
                    $('#skills_id').val(data.id);
                    $('#skill').val(data.skill);
                    $('#skill_detail').val(data.skill_detail);
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

                var skills_id = $('#skills_id').val();
                var url = "{{ route('skills.update', ':id') }}".replace(':id', skills_id);
                var method = 'PUT';

                // Add validation checks here

                var formData = new FormData($('#skillsForm')[0]);
                formData.append('_method', method);

                $.ajax({
                    data: formData,
                    url: url,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#skillsForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                        Swal.fire({
                            title: "Success!",
                            text: "The skills has been updated successfully.",
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
