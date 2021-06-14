<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="col-md-12">
    <div class="tab-content">
        <div class="col-md-12" style="margin-bottom : 55px;padding-left: 0; padding-right : 0;">
            <!-- <label class="title_name" style="margin-bottom : 10px;">REPORT</label><br> -->
            <br>
            <div class="margin-top : 10px; float :left;">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-search"></i><?= ucwords('filter') ?>
                        </div>
                        <div class="tools">
                            <a href="" class="collapse"> </a>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form role="form" action="<?= site_url('dashboard/report') ?>" method="post">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                            <div class="form-body">
                                <div class="form-group col-md-6">
                                    <label>Tanggal</label>
                                    <div class="input-group input-large date-picker input-daterange" data-date-format="dd-mm-yyyy">
                                        <input type="text" class="form-control" name="tanggal_awal" autocomplete="off" value="<?= @$filter['tanggal_awal']; ?>">
                                        <span class="input-group-addon"> to </span>
                                        <input type="text" class="form-control" name="tanggal_akhir" autocomplete="off" value="<?= @$filter['tanggal_akhir']; ?>">
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Layanan</label>
                                    <div class="input-group">
                                        <select class="form-control select2" name="id_layanan">
                                            <option value=''>Pilih Layanan</option>
                                            <?php foreach ($layanan as $key => $c) :
                                                $selected = '';
                                                if (@$c['id_layanan'] == @$filter['id_layanan']) {
                                                    $selected = 'selected';
                                                }
                                            ?>
                                                <option value="<?= $c['id_layanan'] ?>" <?= $selected ?>><?= $c['nm_layanan'] ?></option>
                                            <?php endforeach ?>

                                        </select>
                                    </div>
                                    <br />
                                    <a class="btn default" style="width : 100%;" href="<?= site_url('dashboard/reset_filter') ?>"><i class="fa fa-refresh"></i> Reset</a>
                                    <br /><br />
                                    <button type="submit" class="btn blue" style="border :0;background-color : #007024;width : 100%;"><i class="fa fa-search"></i> Cari</button>
                                </div>
                                <br /><br /><br /><br /><br /><br /><br /><br />
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END SAMPLE FORM PORTLET-->
            </div>
        </div>

        <div class="modal view_doc fade bs-modal-md preview" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md" style="width : 90%">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <!-- <button id="btn" type="button" class="rotate" >rotate</button> -->
                        <button id="btnFullScreen" type="button" class="fullscreen">fullscreen</button>

                        <a href="javascript:void(0);" class="download_all">
                            <button id="btnDownload" class="download_file" type="submit">download</button>
                        </a>

                        <br>
                        <h4 class="bold preview_title"></h4>
                        <div class="content">
                            <!-- <img id="imageFull" src="" class="content_image" style="width : 100% ; height : 100%;" /> -->
                        </div>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- /.tabel -->
        <div class="col-md-12 garisatas" style="margin-top: 5px"></div>
        <div class="col-md-12 garisatas2"></div>
        <table id="tabel" class="table table-striped table-bordered ">
            <thead>
                <tr style="background-color: #e4e4e4;height: 40px;border: 2px #e4e4e4 solid;">
                    <!-- <th class="tengah">No</th> -->
                    <th class="tengah">Date</th>
                    <th class="tengah">Session ID</th>
                    <th class="tengah">DAX Name</th>
                    <th class="tengah">DAX Type</th>
                    <th class="tengah">DAX Status</th>
                    <th class="tengah">Service</th>
                    <th class="tengah">KiosK Location</th>
                    <th class="tengah">Start</th>
                    <th class="tengah">Finish</th>
                    <th class="tengah">Status</th>
                    <th class="tengah">Detail</th>
                    <th class="tengah">Action</th>
                </tr>
            </thead>
            <tbody id="listData" class="list" style="color: black;font-weight: 800;text-align: center;border: 2px #e4e4e4 solid;">
            </tbody>
        </table>
    </div>
</div>

<script>
    var loading = false;
    $(function() {

        $('body').on('click', '.end_session', function() {
            console.log($(this).closest('tr').find('.sts_trx').val());
            if ($(this).closest('tr').find('.sts_trx').val() == 2) {

                end_session($(this).closest('td').find('.sessionid').val());
            } else {
                alert('transaksi sudah selesai.');
            }
        })

        $('body').on('click', '.view_document', function() {
            // console.log($(this).closest('tr').find('.sessionid').val());
            view_dokumen($(this).closest('tr').find('.sessionid').val());
        })

        function end_session(sessionid) {

            $.get('<?= site_url() ?>' + '/service/updates_selesai', {
                sessionid: sessionid
            }, function(respon) {
                var str = '';
                if (respon.status == 'sukses') {
                    $('#tabel').DataTable().ajax.reload();
                } else {
                    alert(respon.message);
                }
            }, 'json');
        }

        var oTable = $('#tabel').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": '<?= site_url("service/get_list_report"); ?>', //mengambil data ke controller datatable fungsi getdata
            "sPaginationType": "full_numbers",
            "aLengthMenu": [
                [15, 30, 75, -1],
                [15, 30, 75, "All"]
            ],
            "iDisplayStart ": 15,
            "ordering": false,
            "columns": [{
                    "data": "tanggal",
                    "render": function(data, type, row) {
                        var date = new Date(data);
                        yr = date.getFullYear(),
                            month = date.getMonth() + 1,
                            day = date.getDate(),
                            newDate = day + '-' + month + '-' + yr;
                        return newDate;

                    }
                },
                {
                    "data": "sessionid"
                },
                {
                    "data": "nm_driver"
                },
                {
                    "data": "nm_jenis"
                },
                {
                    "data": "nm_tipe"
                },
                {
                    "data": "nm_layanan"
                },
                {
                    "data": "lokasi"
                },
                {
                    "data": "wkt_mulai"
                },
                {
                    "data": "wkt_selesai"
                },
                {
                    "data": "sts_trx",
                    "render": function(data, type, row) {
                        if (data == 2) {

                            return '<input type="hidden" class="sts_trx" value="' + data + '"/>On process'
                        }

                        if (data == 3) {

                            return '<input type="hidden" class="sts_trx" value="' + data + '"/>Finish'
                        }

                        if (data == 1) {

                            return '<input type="hidden" class="sts_trx" value="' + data + '"/>Waiting'
                        }

                        if (data == 4) {

                            return '<input type="hidden" class="sts_trx" value="' + data + '"/>Cancel'
                        }
                    }
                },
                {
                    "data": "action"
                },
                {
                    "data": "actions",

                    "render": function(data, type, row) {
                        if (row.sts_trx == 2) {
                            return data;
                        } else {
                            return '<span class="text-danger">CLOSED</span>';
                        }
                    }
                },

            ],
            "oLanguage": {
                "sProcessing": '<img src="<?= base_url("assets/loading2.gif"); ?>"><br><p style="margin-top:-5px;">Loading</p>'
            },
            "fnInitComplete": function() {
                //oTable.fnAdjustColumnSizing();
                $('#tabel_length').css('margin-top', -60);
                $('#tabel_filter').css('margin-top', -60);
            },
            'fnServerData': function(sSource, aoData, fnCallback) {
                var csrf = {
                    "name": '<?= csrf_token() ?>',
                    "value": '<?= csrf_hash() ?>',
                };
                aoData.push(csrf);

                // var csrf = {"name": 'hayo', "value": '<?= encode(hayo()) ?>',};
                aoData.push(csrf);
                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            }

        });

        console.log(oTable);

        $('.download_all').click(function() {
            var a = $(".ijen");
            console.log(a);
            a.each(function(e) {
                this.click()

            })
        });

        var i = 0;
        $("#btn").click(function(event) {
            event.preventDefault();
            i = 90 + i;

            if (i == 360) {
                i = 0;
            }
            rotateImage(i);
        });

        function fullScreen(elem) {
            elem = elem || document.documentElement;
            if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                } else if (elem.mozRequestFullScreen) {
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) {
                    elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
            }
        }

        document.getElementById('btnFullScreen').addEventListener('click', function() {
            fullScreen();
        });

        // document.getElementById('imageFull').addEventListener('click', function() {
        //     fullScreen(this);
        // });


        function view_dokumen(sessionid) {
            $.post('<?= site_url() ?>' + '/service/view_dokumen', {
                sessionid: sessionid,
                // hayo : '<?= encode(hayo()) ?>',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }, function(respon) {
                var str = '';
                count = 1;
                if (respon.status == '200') {
                    $.each(respon.data, function(index, row) {
                        // console.log(row)
                        str += '<div class="row tekan" style="cursor : pointer;" data-file="' + row.image + '"  >' +
                            // '<div class="col-md-10">'+
                            '<input type="hidden" class="sessionid" value="sessionid">' +
                            '<img src="' + row.image + '" />' +

                            '<a href="' + row.image + '" class="ijen" download>' +
                            '<img src="' + row.image + '"  data-file="' + row.image + '" /></a>' +
                            '</div>';
                    })


                    console.log(str);
                    $('.content').html(str);
                    $('.view_doc').modal();
                }
            }, 'json');
        }
    })
</script>

<?= $this->endSection() ?>