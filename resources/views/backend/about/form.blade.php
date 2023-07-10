<div class="modal fade" id="ajaxModel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="aboutForm" name="aboutForm" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="about_id" id="about_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description" class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="description" name="description"
                                        placeholder="Description" value="" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="image" class="col-sm-2 control-label">Image</label>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" id="image" name="image"
                                        placeholder="Image" value="" required="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtnNew">Save</button>
                            <button type="submit" class="btn btn-primary" id="saveBtnEdit">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
