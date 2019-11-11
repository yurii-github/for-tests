<div class="row mt-3">
    <div class="col-12">
        <a id="openForm" class="btn btn-primary btn-block">Add form</a>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="formAddModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <div class="row form-instance">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" placeholder="Form Name" name="name" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="custom-file">
                                <label class="custom-file-label" for="file">Choose file</label>
                                <input type="file" class="form-control" id="file" name="file" multiple="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar progress-bar-striped bg-success progress-bar-animated"
                                     role="progressbar" style="width: 0;" aria-valuenow="25" aria-valuemin="0"
                                     aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@section('script')
    <script>
      let form = $('#formAddModal form')
      let formClone = $('.form-instance', form).first().clone()
      let originalModal = $('#formAddModal').clone();

      $(document).delegate('#openForm', 'click', function(e) {
        $('#formAddModal').modal('show')
      })


      //
      //
      //
      $(document).delegate('#formAddModal', 'shown.bs.modal', function () {
        console.log('OPEN')
      })


      //
      //
      //
      $(document).delegate('#formAddModal', 'hidden.bs.modal', function () {
        console.log('HIDE')
        $('#formAddModal').remove();
        let myClone = originalModal.clone();
        $('body').append(myClone);
      });


      //
      //
      //
      function addNewFormLine() {
        let newForm = formClone.clone()
        let newFileId = 'file_' + Date.now()
        $('input[name="file"]', newForm).attr('id', newFileId)
        $('input[name="file"]', newForm).siblings('label').attr('for', newFileId)
        $('.form-instance', form).last().after(newForm)
      }


      //
      //
      //
      $(form).delegate('input[name="file"]', 'change', function (e) {
        console.log('FILE CHANGE', this.files)
        let filesCount = this.files.length
        $(this).siblings('label').text(filesCount + ' files selected')
        addNewFormLine()
      })


      $(document).on('x.form.added', function(e){
        $('#formAddModal').modal('hide')
      })


      //
      //
      //
      $(document).delegate('#formAddModal form', 'submit', function (e) {
        console.log('SUBMIT')
        e.preventDefault()

        let progress = $('.progress-bar', form)
        let formCount = $('.form-instance', this).length
        let step = Math.round(100 / formCount)
        let currentStep = 0

        let save = async function (formInstanceEl) {
          console.log('SAVING')
          let _formInstance = $(formInstanceEl)
          let formName = $('input[name="name"]', _formInstance).val()
          let files = $('input[name="file"]', _formInstance).first()[0].files

          let formData = new FormData()
          formData.append('name', formName)
          Array.prototype.forEach.call(files, function (file) {
            formData.append("file[]", file)
          })
          await fetch('/', {
            method: "POST",
            body: formData,
            cache: 'no-cache',
            headers: {
              'Accept': 'application/json'
            },
          })
            .then(function (resp) {
              if(resp.status !== 200 && resp.status !== 201) {
                _formInstance.addClass('bg-danger')
                console.log('SAVE FAILED')
                console.log(err)
              }

              _formInstance.removeClass('bg-danger').addClass('bg-success')
              console.log('SAVED')
              currentStep += step
              if (currentStep > 100) {
                currentStep = 100
              }
              progress.css('width', currentStep + '%')
              if (currentStep === 100) {
                $(document).trigger('x.form.added')
              }
            }).catch(function (err) {
              _formInstance.addClass('bg-danger')
              console.log('SAVE FAILED', err)
            })
        }

        $('.form-instance', this).each(function (idx, formEl) {
          save(formEl)
        })
      })

    </script>
@endsection
