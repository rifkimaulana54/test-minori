$(function() {
    $(document).on('click', '#btnHapus', function(e, params)
    {
        if(confirm("Apakah yakin ingn menghapus?")){
            var id = $(this).data('id');
            console.log(id);
            var url = base_url+'/training/delete/'+id;
            $.ajax({
                headers:
                {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "POST",
                success: function (data) {
                    window.location.reload();
                },
                error: function(response) {
                    console.log(response);
                    return false
                }
            });
            return true
        } else
            return false
    })


    function reloadTable() {
        karyawanList.ajax.reload(null, false); //reload datatable ajax
    }
    $('input[type="search"]').on("keyup", function(event) {
        let search = $('input[type="search"]').val();
        request.searchkey = search;
        reloadTable();
    });

    if($('.btnSubmit').length)
    {
        $(document).on('click', '.btnSubmit', function(e, params)
        {
            if(confirm("Apakah yakin?")){
                $('.btnSubmit').attr('disabled', true)
                $('#form-training').submit();
                return true
            } else
                return false
        })
    }
});