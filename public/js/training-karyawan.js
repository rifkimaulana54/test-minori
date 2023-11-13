$(function() {
    let request = {
        start: 0,
        length: 10
    };
    image_loading = "<center class='image-loading'><img src='" + base_url + "/assets/img/loading.gif' style='width: 64px;' /></center>";
    var karyawanList = $('#karyawanList').DataTable({
        processing: true,
        "language": {
            "paginate": {
                "next": '<i class="next"></i>',
                "previous": '<i class="previous"></i>',
            },
            processing: image_loading + 'Memuat data...',
        },
        "aaSorting": [],
        "ordering": false,
        "responsive": true,
        "serverSide": true,
        "searching": true,
        "lengthMenu": [
            [10, 15, 25, 50, -1],
            [10, 15, 25, 50, "All"]
        ],
        "ajax": {
            "url": base_url+'/training-karyawan/getKaryawanList',
            "type": "POST",
            "headers": {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            },
            "beforeSend": function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + $('#secret').val());
            },
            "Content-Type": "application/json",
            "data": function(data) {
                request.draw = data.draw;
                request.start = data.start;
                request.length = data.length;
                return (request);
            },
        },
        "columns": [{
                "data": null,
                "width": '5%',
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                "data": "id",
                "width": '25%',
                "defaultContent": "-",
                render: function(data, type, row) {
                    return "<div class='text-wrap'>" + row.training.jenis + "</div>";
                },
            },
            {
                "data": "id",
                "width": '25%',
                "defaultContent": "-",
                render: function(data, type, row) {
                    console.log(row)
                    return "<div class='text-wrap'>" + moment(row.training.tgl_sertifikat).format("DD MMMM YYYY") + "</div>";
                },
            },
            {
                "data": "id",
                "width": '10%',
                "defaultContent": "-",
                render: function(data, type, row) {
                    return "<div class='text-wrap'>" + row.pegawai.nip + "</div>";
                },
            },
            {
                "data": "id",
                "width": "10%",
                render: function(data, type, row) {
                    let btnEdit = "";
                    let btnHapus = "";
                    
                    btnEdit += '<a href="'+base_url+'/training-karyawan/'+data+'" name="btnEdit" data-id="' + data +
                        '" type="button" class="btn btn-success btn-icon btn-sm btnEdit" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>';
                    btnHapus += '<button id="btnHapus" name="btnHapus" data-id="' + data +
                        '" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><span class="fa fa-trash"></span></button>';

                    return btnEdit + " " + btnHapus;
                },
            },
        ]
    });

    $(document).on('click', '#btnHapus', function(e, params)
    {
        if(confirm("Apakah yakin ingn menghapus?")){
            var id = $(this).data('id');
            console.log(id);
            var url = base_url+'/training-karyawan/delete/'+id;
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

    if($('.multi-select-jenis').length)
    {
        $('.multi-select-jenis').multiSelect({
            selectableOptgroup: true,
            selectableHeader: '<div class="custom-header-search"><input type="text" class="search-input input-sm form-control" autocomplete="off" placeholder="Available Action..."></div>',
            selectionHeader: '<div class="custom-header-search"><input type="text" class="search-input input-sm form-control" autocomplete="off" placeholder="Selected Action..."></div>',
            afterInit: function(ms){
                var that = this,
                $selectableSearch = that.$selectableUl.prev('div').children('input'),
                $selectionSearch = that.$selectionUl.prev('div').children('input'),
                selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';
                
                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                    if (e.which === 40){
                        that.$selectableUl.focus();
                        return false;
                    }
                });
                
                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                    if (e.which == 40){
                        that.$selectionUl.focus();
                        return false;
                    }
                });
            },
            afterSelect: function(){
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function(){
                this.qs1.cache();
                this.qs2.cache();
            }
        }); 

        $(document).on('click', '.str_select_all', function(e) {
            e.preventDefault();
            $('.multi-select-jenis').multiSelect('select_all');
            return false;
        });
        $(document).on('click', '.str_deselect_all', function(e) {
            e.preventDefault();
            $('.multi-select-jenis').multiSelect('deselect_all');
            return false;
        });
    }

    if($('.btnSubmit').length)
    {
        $(document).on('click', '.btnSubmit', function(e, params)
        {
            if(confirm("Apakah yakin?")){
                $('.btnSubmit').attr('disabled', true)
                $('#form-karyawan').submit();
                return true
            } else
                return false
        })
    }
});