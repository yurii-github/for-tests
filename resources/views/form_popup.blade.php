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
                                <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" style="width: 0;"></div>
                                <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" style="width: 0"></div>
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
      let formClone = $('.form-instance', $('#formAddModal form')).first().clone()
      let originalModal = $('#formAddModal').clone()

      $(document).delegate('#openForm', 'click', function (e) {
        $('#formAddModal').modal('show')
      })

      //
      //
      //
      $(document).delegate('#formAddModal', 'hidden.bs.modal', function () {
        console.log('HIDE')
        $('#formAddModal').remove()
        let myClone = originalModal.clone()
        $('body').append(myClone)
      })


      //
      //
      //
      function addNewFormLine($formInstance) {
        let newForm = formClone.clone()
        let newFileId = 'file_' + Date.now()
        $('input[name="file"]', newForm).attr('id', newFileId)
        $('input[name="file"]', newForm).siblings('label').attr('for', newFileId)
        $formInstance.after(newForm)
      }


      //
      //
      //
      $(document).delegate('#formAddModal form input[name="file"]', 'change', function (e) {
        console.log('FILE CHANGE', this.files)
        let filesCount = this.files.length
        let formInstance = $(this).closest('.form-instance')
        $(this).siblings('label').text(filesCount + ' files selected')
        addNewFormLine(formInstance)
      })


      $(document).on('x.form.added', function (e) {
        $('#formAddModal').modal('hide')
      })


      //
      //
      //
      $(document).delegate('#formAddModal form', 'submit', function (e) {
        console.log('SUBMIT')
        e.preventDefault()

        let $form = $('#formAddModal form')
        let $progressOK = $('.progress-bar.bg-success', $form)
        let $progressDue = $('.progress-bar.bg-info', $form)
        let formCount = $('.form-instance', this).length
        let step = 100 / formCount
        let currentStep = 0
        let currentDue = 0

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
          await fetch('{{ route('form.create') }}', {
            method: "POST",
            body: formData,
            cache: 'no-cache',
            headers: {
              'Accept': 'application/json'
            }
          })
            .then(function (resp) {
              if (resp.status !== 200 && resp.status !== 201) {
                console.log('SAVE FAILED')
                console.log(err)
              }
              console.log('SAVED')
              currentStep += step
              currentDue -= step
              if (currentStep > 100) {
                currentStep = 100
                currentDue = 0
              }
              $progressDue.css('width', currentDue + '%')
              $progressOK.css('width', currentStep + '%')
              if (currentStep === 100) {
                $(document).trigger('x.form.added')
              }
            }).catch(function (err) {
              console.log('SAVE FAILED', err)
            })
        }

        $('.form-instance', this).each(function (idx, formEl) {
          currentDue += step
          $progressDue.css('width', currentDue + '%')
          save(formEl)
        })
      })

    </script>
@endsection
