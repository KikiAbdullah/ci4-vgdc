<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="col-md-12" style="margin-bottom:  25px;">
    <label class="title_name" style="color: #00B14F;">LOG USER</label><br>
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
                        <form role="form" action="<?= site_url('log') ?>" method="post">
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
                                    <a class="btn default" style="width : 100%;" href="<?= site_url('log/reset_filter') ?>"><i class="fa fa-refresh"></i> Reset</a>
                                </div>
                                <div class="form-group col-md-2">
                                    <label style="color: white;">-</label>
                                    <button type="submit" class="btn blue" style="border :0;background-color : #007024; width : 100%;"><i class="fa fa-search"></i> Cari</button>
                                </div>
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
                <th class="kiri">User</th>
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
        var oTable = $('#tabel').dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?php echo site_url("log/get_data"); ?>'
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
                    data: "waktu"
                },
                {
                    data: "nama"
                },
                {
                    data: "deskripsi"
                },
                {
                    data: "keterangan"
                }

            ],
            "oLanguage": {
                "sProcessing": '<img src="<?php echo base_url("assets/loading2.gif"); ?>"><br><p style="margin-top:-5px;">Loading</p>'
            },
        });

        $('#tabel').on('draw.dt', function() {
            // reloadbutton();
            $('.make-switch').bootstrapSwitch();
        });
    })
</script>
<?= $this->endSection() ?>