<div class="starter-template">
    <h1>Cron Runner</h1>

    <form method="post" action="<?=url::base();?>cron-run">

        Date: <input id="date"
                type="text"
                name="date"
                placeholder="dd/yy/yyyy"
                onkeyup="
        var v = this.value;
        if (v.match(/^\d{2}$/) !== null) {
            this.value = v + '/';
        } else if (v.match(/^\d{2}\/\d{2}$/) !== null) {
            this.value = v + '/';
        }"
                maxlength="10"
        >

        <input type="hidden" id="secretkey" name="secretkey" value="ZzZ">
        <button type="submit">run cron</button>
    </form>

    <div id="result"></div>
    <script>
        jQuery('form').on('submit', function(e){
            e.preventDefault();

            $.post($(this).attr('action'), {date: $('#date', this).val(), secretkey: $('#secretkey', this).val()}, function(resp){
                console.log(resp);

                if(resp.data !== undefined) {
                    $('#result').html(JSON.stringify(resp.data));
                } else {
                    $('#result').html('something went wrong');
                }
            }, 'json');

            }
        );

    </script>
</div>