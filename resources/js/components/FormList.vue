<template>
  <div class="row mt-3">
    <div class="col-12">
      <div class="table-responsive">
        <table ref="form-table" class="table table-striped table-sm">
          <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Deleted</th>
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

    beforeMount() {
      console.log('beforeMount')
      let _this = this

      window.axios.get(this.listUrl).then((resp) => {
        console.log('form list')
        let $tbl = this.$refs['form-table']
        let tbody = $('tbody', $(this.$refs['form-table']))


        if (resp.data) {
          resp.data.forEach(function(item, id) {
            tbody.append($('<tr>')
              .append($('<td>').text(item.id))
              .append($('<td>').text(item.name))
              .append($('<td>').text(item.created_at))
              .append($('<td>').text(item.updated_at))
              .append($('<td>').text(item.deleted_at))
            )
          })
        }
      })
    },

    mounted() {
      console.log('mounted')
    }

  }
</script>
