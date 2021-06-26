<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php if (!empty($akses[1])) { ?>
    <div class="col-md-12" style="margin-bottom:  25px;">
        <label class="title_name" style="color: #00B14F;">GDC KIOSK REGISTRATION</label><br>
    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light" style="background-color: #f0f0f0;border-radius: 10px!important;color: black;border-bottom: 4px solid #00B14F;border-left: 4px solid #00B14F;border-right: 4px solid #00B14F;border: 4px solid #00B14F;">
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form action="<?= site_url('kios/do_tambah')  ?>" class="form-horizontal" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                            <input type="hidden" name="status" value="N">
                            <input type="hidden" name="is_done" value="N">
                            <div class="form-body">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4" style="text-align: left;font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'KiosK_name')) ?></label>
                                        <br /><br />
                                        <div class="col-md-2" style="width: 100%;">
                                            <input class="form-control" type="text" name="nm_gdc" placeholder="<?= ucwords(str_replace('_', ' ', 'KiosK_name')) ?>" value="<?= @session()->getFlashdata('postdata')->nm_gdc ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4" style="text-align: left;font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'Location')) ?></label>
                                        <br /><br />
                                        <div class="col-md-3" style="width: 100%;">
                                            <input class="form-control" type="text" name="lokasi" placeholder="<?= ucwords(str_replace('_', ' ', 'Location')) ?>" value="<?= @session()->getFlashdata('postdata')->lokasi ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4" style="text-align: left;font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'KiosK_email')) ?></label>
                                        <br /><br />
                                        <div class="col-md-3" style="width: 100%;">
                                            <input class="form-control" type="email" name="email_gdc" placeholder="<?= ucwords(str_replace('_', ' ', 'KiosK_email')) ?>" value="<?= @session()->getFlashdata('postdata')->email_gdc ?>" required />
                                        </div>
                                    </div>
                                    <br /><br />
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-md-6" style="text-align: left;font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'IP_server')) ?></label>
                                        <br /><br />
                                        <div class="col-md-3" style="width: 100%;">
                                            <input class="form-control" type="text" name="ipserver" placeholder="<?= ucwords(str_replace('_', ' ', 'IP_Server')) ?>" value="<?= @session()->getFlashdata('postdata')->ipserver ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-md-4" style="text-align: left;font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'IP_KiosK')) ?></label>
                                        <br /><br />
                                        <div class="col-md-3" style="width: 100%;">
                                            <input class="form-control" type="text" name="ip_gdc" placeholder="<?= ucwords(str_replace('_', ' ', 'IP_KiosK')) ?>" value="<?= @session()->getFlashdata('postdata')->ip_gdc ?>" required />
                                        </div>
                                    </div>
                                    <br /><br />
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4" style="text-align: left;font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'apikey')) ?></label>
                                        <br /><br />
                                        <div class="col-md-3" style="width: 100%;">
                                            <input class="form-control" type="text" name="apikey" placeholder="<?= ucwords(str_replace('_', ' ', 'apikey')) ?>" value="<?= @session()->getFlashdata('postdata')->apikey ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4" style="text-align: left;font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'Device_id')) ?></label>
                                        <br /><br />
                                        <div class="col-md-3" style="width: 100%;">
                                            <input class="form-control" type="text" name="id_device" placeholder="<?= ucwords(str_replace('_', ' ', 'Device_id')) ?>" value="<?= @session()->getFlashdata('postdata')->id_device ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4" style="text-align: left;font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'password')) ?></label>
                                        <br /><br />
                                        <div class="col-md-2" style="width: 100%;">
                                            <input class="form-control" type="password" name="password" placeholder="<?= ucwords(str_replace('_', ' ', 'password')) ?>" value="" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4" style="text-align: left;font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'confirm_password')) ?></label>
                                        <br /><br />
                                        <div class="col-md-2" style="width: 100%;">
                                            <input class="form-control" type="password" name="confirm_password" placeholder="<?= ucwords(str_replace('_', ' ', 'confirm_password')) ?>" value="" required />
                                        </div>
                                    </div>
                                    <br /><br />
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="col-md-12">
                                    <div class="row">
                                        <br /><br />
                                        <div class="col-md-12" style="text-align: right;">
                                            <button type="submit" class="btn green" style="width: 25%;">REGISTER</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- END FORM-->
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="col-md-10" style="margin-top: 40px; margin-bottom:20px">
    <label class="title_name" style="color: #00B14F;">GDC KIOSK LIST</label><br>
</div>
<div class="col-md-2" style="margin-top: 40px; margin-bottom:20px">
    <div class="actions">
        <?php if (!empty($akses[5])) { ?>
            <a class="btn green" style="width: 100%;" href="<?= site_url('kios/approve') ?>"><i class="fa fa-check"></i> Approvement</a>
        <?php } ?>
    </div>
</div>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <br />
                <br />
                <div class="portlet-body form">
                    <div class="garisatas" style="margin-top: 70px"></div>
                    <div class="garisatas2"></div>
                    <table id="tabel" class="table table-striped table-bordered ">
                        <thead>
                            <tr style="background-color: #e4e4e4;height: 40px;border: 2px #e4e4e4 solid;">
                                <th class="kiri" style="width: 15px;">ID</th>
                                <th class="kiri">GDC Name</th>
                                <th class="kiri">Location</th>
                                <th class="kiri">Id Device</th>
                                <th class="kiri">API Key</th>
                                <th class="kiri" style="width: 100px;">Status</th>
                                <!-- <th style="width:135px;">Status</th> -->
                                <?php if ($akses[1] > 0) { ?>
                                    <th class="kiri">Action</th>
                                <?php   } else { ?>
                                    <th class="kiri"></th>
                                <?php   } ?>
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
<script type="text/javascript">
    $(document).ready(function() {

        function reloadbutton() {
            $('.tombolEdit').each(function() {
                var id = $(this).data('id');
                // alert(id);
                encodedString = btoa(id).replace('==', '').replace('=', '');
                // alert(encodedString);
                var link = $(this).attr('href');
                $(this).attr('href', link + '/' + encodedString);
            });


            $('.tombolHapus').each(function() {
                var id = $(this).data('id');
                //alert(id);
                encodedString = btoa(id).replace('==', '').replace('=', '');
                var link = "<?= site_url('kios/hapus'); ?>";
                $(this).attr('onclick', "hapus('" + link + '/' + encodedString + "');");
            });
        }

        var oTable = $('#tabel').dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?php echo site_url("kios/get_data"); ?>'
            },
            paginationType: "full_numbers",
            lengthMenu: [
                [15, 30, 75],
                [15, 30, 75]
            ],
            ordering: false,
            columns: [{
                    data: "id_gdc"
                },
                {
                    data: "nm_gdc"
                },
                {
                    data: "lokasi"
                },
                {
                    data: "id_device"
                },
                {
                    data: "apikey"
                },
                {
                    data: "status",
                    render: function(data, type, row) {
                        if (data == 'Y') {
                            var sts = '<label class="text-center" style="width: 100%; color:blue; font-weight:800;">ACTIVE</label>';
                        } else {
                            var sts = '<label class="text-center" style="width: 100%; color:red; font-weight:800;">NOT ACTIVE</label>';
                        }
                        return sts;
                    }
                },
                {
                    render: function(data, type, row) {
                        var html = '';
                        if ('<?= $akses[2] ?>' != '') {
                            var html = "<a class='tombolEdit' title='Edit' href=" + '<?= site_url('kios/edit') ?>' + " data-id='" + row['id_gdc'] +
                                "'><img src=" + '<?= base_url('assets/edit.png') ?>' + " style='width: 30px;'></a>&nbsp;";
                        }

                        if ('<?= $akses[3] ?>' != '') {
                            var html = html + "<a class='tombolHapus' title='Hapus' href='#' data-id='" + row['id_gdc'] +
                                "'><img src=" + '<?= base_url('assets/delete.png') ?>' + " style='width: 30px;'></a>";
                        }
                        return html;
                    }
                }
            ],
            language: {
                processing: '<img src="<?php echo base_url("assets/loading2.gif"); ?>"><br><p style="margin-top:-5px;">Loading</p>'
            }
        });

        $('#tabel').on('draw.dt', function() {
            reloadbutton();
            $('.make-switch').bootstrapSwitch();
        });

    });


    function ubah_status(id) {
        //console.log(id);
        $.ajax({
            url: "<?= site_url('kios/ubah_status')  ?>",

            type: "POST",
            data: {
                id_user: id,
                hayo: '<?= encode(hayo()) ?>',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            dataType: "json",
            cache: false,
            success: function(hasil) {

                if (hasil.notif == 'sukses') {
                    $('#tabel').DataTable();
                    // get_list();
                }
            }
        });
    }


    // var loading = false;
    // $(function(){
    //     get_list();



    //     // $('#datefilterfrom').change(function() {
    //     //     get_list();
    //     // });
    // })
</script>
<?= $this->endSection() ?>