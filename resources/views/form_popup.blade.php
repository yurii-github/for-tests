<div class="row mt-3">
    <div class="col-12">
        <a class="btn btn-primary btn-block" data-toggle="modal" data-target="#formAddModal">Add form</a>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="formAddModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" placeholder="Name" value="" required="">
                            <div class="invalid-feedback">
                                Valid name is required.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="custom-file">
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                <input type="file" class="form-control" id="inputGroupFile01">
                                <div class="invalid-feedback">
                                    Valid last name is required.
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" type="submit">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{--<script>--}}
{{--$('#myModal').on('shown.bs.modal', function () {--}}
{{--$('#myInput').trigger('focus')--}}
{{--})--}}
{{--</script>--}}
