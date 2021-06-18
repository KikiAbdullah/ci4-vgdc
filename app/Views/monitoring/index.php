<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="col-md-12" style="margin-bottom:  25px;">
    <label class="title_name" style="color: #00B14F;">MONITORING GDC KIOSK</label><br>
</div>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat putih">
            <div class="visual">
                <img src="<?= base_url('assets/total-handling.png'); ?>" style="width: 50px;">
            </div>
            <div class="details">
                <div class="number gdc_on">
                    0
                </div>
                <div class="desc"> Total GDC ON</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat putih4">
            <div class="visual">
                <img src="<?= base_url('assets/average-time.png'); ?>" style="width: 50px;">
            </div>
            <div class="details">
                <div class="number gdc_off"> 0 </div>
                <div class="desc"> Total GDC OFF</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat putih">
            <div class="visual">
                <img src="<?= base_url('assets/csat-point.png'); ?>" style="width: 50px;">
            </div>
            <div class="details">
                <div class="number gdc_tot">
                    0
                </div>
                <div class="desc"> Total GDC</div>
            </div>
        </div>
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
                            <form role="form" action="<?= site_url('monitoring/index') ?>" method="post">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                                <div class="form-body">
                                    <div class="form-group col-md-8">
                                        <label>Date</label>
                                        <div class="input-group input-large date-picker input-daterange" data-date-format="dd-mm-yyyy">
                                            <input type="text" class="form-control" name="tanggal_awal" autocomplete="off" value="<?= @$filter['tanggal_awal']; ?>">
                                            <span class="input-group-addon"> to </span>
                                            <input type="text" class="form-control" name="tanggal_akhir" autocomplete="off" value="<?= @$filter['tanggal_akhir']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label style="color: white;">-</label>
                                        <a class="btn default" style="width : 100%;" href="<?= site_url('monitoring/reset_filter') ?>"><i class="fa fa-refresh"></i> Reset</a>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label style="color: white;">-</label>
                                        <button type="submit" class="btn blue" style="border :0;background-color : #007024; width : 100%;"><i class="fa fa-search"></i> Cari</button>
                                    </div>
                                    <!-- <?php if (!empty($this->akses[4])) { ?>
                                        <a class="btn blue" style="width : 100%;"href="<?= site_url('monitoring/export_excel_csat') ?>"><i class="fa fa-file-excel-o"></i> Export</a>
                                    <?php } ?> -->
                                </div>
                                <br /><br /><br />
                        </div>
                        </form>
                    </div>
                </div>
                <!-- END SAMPLE FORM PORTLET-->
            </div>


        </div>
        <div class="col-md-12 garisatas" style="margin-top: 5px"></div>
        <div class="col-md-12 garisatas2"></div>
        <table id="tabel" class="table table-striped table-bordered ">
            <thead>
                <tr style="background-color: #e4e4e4;height: 40px;border: 2px #e4e4e4 solid;">
                    <!-- <th class="tengah">No</th> -->
                    <th class="kiri">Date</th>
                    <th class="kiri">Time</th>
                    <th class="kiri">GDC Name</th>
                    <th class="kiri">Activity</th>
                    <th class="kiri">Description</th>
                </tr>
            </thead>

        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    var loading = false;
    $(function() {
        // get_list();
        // setInterval(() => {
        //     get_list();
        // }, 3000);

        var oTable = $('#tabel').dataTable({
            "bProcessing": false,
            "bServerSide": false,
            "sAjaxSource": '<?php echo site_url("monitoring/get_data"); ?>', //mengambil data ke controller datatable fungsi getdata
            "sPaginationType": "full_numbers",
            "aLengthMenu": [
                [15, 30, 75, -1],
                [15, 30, 75, "All"]
            ],
            "iDisplayStart ": 15,
            "ordering": false,
            "columns": [
                // { "data": "no_antrian"},
                {
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
                // { "data": ""},
                {
                    "data": "waktu"
                },
                {
                    "data": "nm_gdc"
                },
                {
                    "data": "deskripsi"
                },
                {
                    "data": "keterangan"
                }

            ],
            "oLanguage": {
                "sProcessing": '<img src="<?php echo base_url("assets/loading2.gif"); ?>"><br><p style="margin-top:-5px;">Loading</p>'
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

                var csrf = {
                    "name": 'hayo',
                    "value": '<?= encode(hayo()) ?>',
                };
                aoData.push(csrf);
                $.ajax({
                    'dataType': 'json',
                    'type': 'GET',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });

                console.log(sSource);
            }

        });
        $('#tabel').on('draw.dt', function() {
            // reloadbutton();
            $('.make-switch').bootstrapSwitch();
        });
        get_statistik();
        setInterval(() => {
            get_statistik();
        }, 3000);

        function get_statistik() {
            $.post('<?= site_url() ?>' + '/service/get_statistik2', {
                hayo: '<?= encode(hayo()) ?>',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }, function(respon) {

                if (respon.status == 'sukses') {
                    var data = respon.data;

                    $('.gdc_on').text(data.gdc_on);
                    $('.gdc_off').text(data.gdc_off);
                    $('.gdc_tot').text(data.gdc_tot);
                }
            }, 'json');
        }

    })
</script>
<?= $this->endSection() ?>