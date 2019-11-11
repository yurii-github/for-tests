<template>
  <div class="row mt-3">
    <div class="col-12">
      <div class="table-responsive">
        <table :key="uniqueKey" ref="form-table" class="table table-striped table">
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
</template>

<script>
  export default {
    props: {
      'listUrl': {
        required: true,
        type: String
      }
    },

    data() {
      return {
        'uniqueKey': 1
      }
    },

    methods: {
      renderForms() {
        window.axios.get(this.listUrl).then((resp) => {
          let $tbl = this.$refs['form-table']
          let tbody = $('tbody', $(this.$refs['form-table']))
          resp.data.forEach(function (item, id) {
            tbody.append($('<tr>')
              .append($('<td>').text(item.id))
              .append($('<td>').text(item.name))
              .append($('<td>').text(item.created_at))
              .append($('<td>').text(item.updated_at))
              .append($('<td>').text(item.deleted_at))
              .append('<td><button type="submit" data-id="'+item.id+'" class="btn btn-sm btn-primary btn-block">View</button></td>')
            )
          })
        })
      }
    },

    beforeUpdate() {
      console.log('beforeUpdate')
      this.renderForms()
    },

    beforeMount() {
      console.log('beforeMount')

      let _this = this
      $(document).on('x.form.added', function (e) {
        console.log('FORCE UPDATE')
        _this.uniqueKey = Date.now()
      })

      this.renderForms()
    },

    mounted() {
      console.log('mounted')
    }

  }
</script>
