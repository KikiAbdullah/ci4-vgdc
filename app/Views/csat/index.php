<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="col-md-12" style="margin-bottom:  25px;">
    <label class="title_name" style="color: #00B14F;">CSAT REPORT</label><br>
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
                        <form role="form" action="<?= site_url('csat/report_csat') ?>" method="post">
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
                                                if ($c['id_layanan'] == @$filter['id_layanan']) {
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
                                    <a class="btn default" style="width : 100%;" href="<?= site_url('csat/reset_filter_csat') ?>"><i class="fa fa-refresh"></i> Reset</a>
                                </div>
                                <div class="form-group col-md-2">
                                    <label style="color: white;">Service</label>
                                    <button type="submit" class="btn blue" style="border :0;background-color : #007024; width : 100%;"><i class="fa fa-search"></i> Apply</button>
                                </div><br />
                                <div class="form-group col-md-8">
                                </div>
                                <div class="form-group col-md-4">
                                    <?php if (!empty($this->akses[4])) { ?>
                                        <a class="btn blue" style="width : 100%;" href="<?= site_url('csat/export_excel_csat') ?>"><i class="fa fa-file-excel-o"></i> Export</a>
                                    <?php } ?>
                                </div>
                                <br /><br /><br /><br />
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
                    <th class="tengah">Date</th>
                    <th class="tengah">Session ID</th>
                    <th class="tengah">DAX Name</th>
                    <th class="tengah">DAX Type</th>
                    <th class="tengah">DAX Status</th>
                    <th class="tengah">Service</th>
                    <th class="tengah">KiosK Location</th>
                    <th class="tengah">Start</th>
                    <th class="tengah">Finish</th>
                    <th class="tengah">CSAT</th>
                    <th class="tengah">Detail</th>
                </tr>
            </thead>
            <tbody id="listData" class="list" style="color: black;font-weight: 800;text-align: center;border: 2px #e4e4e4 solid;">
            </tbody>
        </table>
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
                url: '<?php echo site_url("service/get_list_report"); ?>'
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
                    data: "id_csat",
                    render: function(data, type, row) {
                        if (data == 1) {
                            return '<img src="<?= base_url('assets/upload/tidak-puas.png'); ?> "style="width: 30px;">';
                        }
                        if (data == 2) {
                            return '<img src="<?= base_url('assets/upload/kurang-puas.png'); ?>" style="width: 30px;">';
                        } else if (data == 3) {
                            return '<img src="<?= base_url('assets/upload/puas.png'); ?>" style="width: 30px;">';
                        } else if (data == 4) {
                            return '<img src="<?= base_url('assets/upload/cukup-puas.png'); ?>" style="width: 30px;">';
                        } else if (data == 5) {
                            return '<img src="<?= base_url('assets/upload/sangat-puas.png'); ?>" style="width: 30px;">';
                        } else {
                            return '';
                        }
                    }
                },
                {
                    data: "id_csat"
                },
            ],
            language: {
                processing: '<img src="<?php echo base_url("assets/loading2.gif"); ?>"><br><p style="margin-top:-5px;">Loading</p>'
            }
        });
        $('#tabel').on('draw.dt', function() {
            // reloadbutton();
            $('.make-switch').bootstrapSwitch();
        });
    })
</script>
<?= $this->endSection() ?>