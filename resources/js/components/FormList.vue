<template>
  <div>
    <div class="row mt-3">
      <div class="col-12">
        <div class="table-responsive">
          <table ref="form-table" class="table table-striped table">
            <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Created</th>
              <th>Updated</th>
              <th>Deleted</th>
              <th></th>
            </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
    <form-view ref="formViewModal"></form-view>
  </div>
</template>

<script>

  import FormView from "./FormView.vue"

  export default {
    components: {FormView},
    props: {
      'listUrl': {
        required: true,
        type: String
      }
    },

    data() {
      return {
        viewForm: {
          name: null
        }
      }
    },

    methods: {
      renderForms() {
        console.log('renderForms()')
        let _this = this
        let $tbl = $(this.$refs['form-table'])
        let $tbody = $('tbody', $tbl)
        console.log(this.$refs['form-table'], $tbl)

        window.axios.get(this.listUrl)
          .then((resp) => {
            $tbody.empty()
            console.log('aaaaaaaa')
            resp.data.forEach(function (item, id) {
              let $btn = $('<button type="submit" data-id="' + item.id + '" class="btn btn-sm btn-primary btn-block">View</button>')
              $btn.on('click', function (e) {
                _this.$refs['formViewModal'].show(item)
              })
              $tbody.append($('<tr>')
                .append($('<td>').text(item.id))
                .append($('<td>').text(item.name))
                .append($('<td>').text(item.created_at))
                .append($('<td>').text(item.updated_at))
                .append($('<td>').text(item.deleted_at))
                .append($('<td>').append($btn))
              )
            })
          })
      }
    },

    mounted() {
      console.log('beforeMount')

      let _this = this
      $(document).on('x.form.added', function (e) {
        console.log('FORCE UPDATE')
        _this.renderForms()
      })

      _this.renderForms()
    }

  }
</script>
