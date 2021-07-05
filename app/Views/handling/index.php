<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="col-md-12" style="margin-bottom:  5px;">
    <label class="title_name" style="color: #00B14F;">HANDLING PROCESS</label>
</div>

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
                        <form role="form" action="<?= site_url('handling') ?>" method="post">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            <div class="form-body">
                                <div class="form-group col-md-4">
                                    <label>Date</label>
                                    <div class="input-group input-large date-picker input-daterange" data-date-format="dd-mm-yyyy">
                                        <input type="text" class="form-control" name="tanggal_awal" autocomplete="off" value="<?= @$filter['tanggal_awal']; ?>">
                                        <span class="input-group-addon"> to </span>
                                        <input type="text" class="form-control" name="tanggal_akhir" autocomplete="off" value="<?= @$filter['tanggal_akhir']; ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Service</label>
                                    <div class="input-group input-large">
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
                                </div>
                                <div class="form-group col-md-2">
                                    <label style="color: white;">Service</label>
                                    <a class="btn default " style="width : 100%;" href="<?= site_url('handling/reset_filter') ?>"><i class="fa fa-refresh"></i> Reset</a>
                                </div>
                                <div class="form-group col-md-2">
                                    <label style="color: white;">Service</label>
                                    <button type="submit" class="btn blue" style="border :0;background-color : #00B14F;width : 100%;"><i class="fa fa-search"></i> Apply</button>
                                </div>
                                <br /><br /><br />
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END SAMPLE FORM PORTLET-->
            </div>
        </div>
    </div>
    <div class="modal view_doc fade bs-modal-md preview" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" style="width : 90%">
            <div class="modal-content">
                <div class="modal-header"><br><br>
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
    <div class="modal fade bs-modal-dm finish" tabindex="-1" role="dialog" aria-hidden="true" style="margin-top: 68px;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <br><br>
                    <h4 id="myModalLabel" class="bold">Anda akan mengakhiri proses handling dengan DAX</h4>
                    <br>
                    <p class="no-margin">Tekan tombol <b>Disabled CSAT</b> untuk mengakhiri proses handling tanpa menampilkan CSAT atau tekan tombol <b>Enabled CSAT</b> untuk mengakhiri proses handling dengan menampilkan CSAT untuk DAX</p>
                    <br>
                </div>
                <div class="modal-footer">
                    <br>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-white" data-dismiss="modal" style="width: -webkit-fill-available">Batal</button>
                    </div>
                    <div class="col-md-5">
                        <a href="#" class="btn btn-primary-f2 tidak_tampil" style="width: -webkit-fill-available">Disabled CSAT</a>
                    </div>
                    <div class="col-md-5">
                        <a href="#" class="btn btn-primary-f gae_popup" style="width: -webkit-fill-available">Enabled CSAT</a>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="tab-content">
        <div class="col-md-12" style="margin-bottom : 35px;padding-left: 0; padding-right : 0;">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                    </div>
                </div><br><br>
                <div class="portlet-body form">
                    <div class="col-md-12 garisatas" style="margin-top: 5px"></div>
                    <div class="col-md-12 garisatas2"></div>
                    <table id="tabel" class="table table-striped table-bordered ">
                        <thead>
                            <tr style="background-color: #e4e4e4;height: 40px;border: 2px #e4e4e4 solid;">
                                <!-- <th class="tengah">No</th> -->
                                <th class="kiri" style="width: 100px;">Date</th>
                                <th class="kiri">Session ID</th>
                                <th class="kiri">DAX Name</th>
                                <th class="kiri">DAX Type</th>
                                <th class="kiri">DAX Status</th>
                                <th class="kiri">Service</th>
                                <th class="kiri">KiosK Location</th>
                                <th class="kiri">Start</th>
                                <th class="kiri">Finish</th>
                                <th class="kiri">Status</th>
                                <th class="kiri">Detail</th>
                                <th class="kiri">Action</th>
                            </tr>
                        </thead>
                        <tbody id="listData" class="list" style="color: black;font-weight: 800;text-align: left;border: 2px #e4e4e4 solid;">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    var loading = false;
    $(function() {
        var oTable = $('#tabel').dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?php echo site_url("handling/get_list_report"); ?>'
            },
            paginationType: "full_numbers",
            lengthMenu: [
                [15, 30, 75],
                [15, 30, 75]
            ],
            ordering: false,
            columns: [{
                    data: "tanggal",
                    render: function(data, type, row) {
                        var date = new Date(data);
                        yr = date.getFullYear(),
                            month = date.getMonth() + 1,
                            day = date.getDate(),
                            newDate = day + '-' + month + '-' + yr;
                        return newDate;

                    }
                },
                {
                    data: "sessionid"
                },
                {
                    data: "nm_driver"
                },
                {
                    data: "nm_jenis"
                },
                {
                    data: "nm_tipe"
                },
                {
                    data: "nm_layanan"
                },
                {
                    data: "lokasi"
                },
                {
                    data: "wkt_mulai"
                },
                {
                    data: "wkt_selesai"
                },
                {
                    data: "sts_trx",
                    render: function(data, type, row) {
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
                    render: function(data, type, row) {
                        if ('<?= $akses[2] ?>' != '') {
                            html = '<input class="id_trx" type="hidden" name="id_trx" value="' + row['id_trx'] + '"/><button class="view_document"  style="border : 0!important; background-color : transparent!important;" type="submit"><img src=' + "<?= base_url('assets/icon_mekar.png') ?>" + ' style="width: 30px;"></button></form>';
                            // html = row['id_trx'];

                        }
                        return html;
                    }
                },
                {
                    render: function(data, type, row) {
                        if (row.sts_trx == 2) {
                            html = "<input class='id_trx' type='hidden' name='id_trx' value='" + row['id_trx'] + "'/><button class='btn end_session' style='background-color : #009a3c;color :azure; border-radius : 5px;'>Selesai </button>";
                            return html;
                        } else {
                            return '<span class="text-danger">CLOSED</span>';
                        }
                    }
                },

            ],
            "oLanguage": {
                "sProcessing": '<img src="<?= base_url("assets/loading2.gif"); ?>"><br><p style="margin-top:-5px;">Loading</p>'
            },
        });
    })
</script>

<script>
    var loading = false;
    $(function() {

        $('body').on('click', '.end_session', function() {
            //console.log($(this).closest('tr').find('.sts_trx').val());
            if ($(this).closest('tr').find('.sts_trx').val() == 2) {

                muncul($(this).closest('td').find('.id_trx').val());
                // end_session($(this).closest('td').find('.id_trx').val());
            } else {
                alert('transaksi sudah selesai.');
            }
        })

        $('body').on('click', '.view_document', function() {
            //console.log($(this).closest('tr').find('.id_trx').val());
            view_dokumen($(this).closest('tr').find('.id_trx').val());
        })

        function muncul(id_trx) {
            var link = '<?= site_url('handling/updates_selesai') ?>';

            $('.gae_popup').attr('href', link + '/' + id_trx + '/' + 'Y');
            $('.tidak_tampil').attr('href', link + '/' + id_trx + '/' + 'N');

            $('.finish').modal();
        }

        function end_session(id_trx) {

            $.get('handling' + '/updates_selesai', {
                id_trx: id_trx
            }, function(respon) {
                var str = '';
                if (respon.status == 'sukses') {
                    $('#tabel').DataTable().ajax.reload();
                } else {
                    alert(respon.message);
                }
            }, 'json');
        }

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


        function view_dokumen(id_trx) {
            $.get('<?= base_url() ?>' + '/service/view_dokumen/' + id_trx, {}, function(respon) {
                var str = '';
                count = 1;
                if (respon.status == '200') {
                    $.each(respon.data, function(index, row) {
                        // console.log(row)
                        str += '<div class="row tekan" style="cursor : pointer;" data-file="' + row.image + '"  >' +
                            // '<div class="col-md-10">'+
                            '<input type="hidden" class="id_trx" value="id_trx">' +
                            '<img src="' + row.image + '" style="width : 100% ; height : 100%;" />' +

                            '<a href="' + row.image + '" class="ijen" download>' +
                            '</a>' +
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