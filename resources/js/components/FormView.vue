<template>

  <div class="modal" tabindex="-1" role="dialog" ref="viewModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">View Form</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-11">
              Name: {{ this.form.name }} <br>
              Created: {{ this.form.created_at }} <br>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              Files:<br>
              <ul>
                <li v-for="file in this.form.files">
                  <a :href="fileUrl(file.id)">{{ file.filename }} ({{ file.mime }})</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
  export default {
    data() {
      return {
        form: {
          created_at: null,
          name: null,
          files: []
        }
      }
    },
    name: "FormView",
    methods: {
      fileUrl(id) {
        return '/file/' + id
      },
      show(form) {
        let _this = this
        window.axios.get('/form/' + form.id)
          .then((resp) => {
            console.log('show', resp.data)
            Object.assign(_this.form, resp.data)
            $(this.$refs['viewModal']).modal('show')
          })
      }
    }
  }
</script>

<style scoped>

</style>
