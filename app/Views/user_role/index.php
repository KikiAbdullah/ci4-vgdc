<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php if (!empty($akses[1])) { ?>
    <div class="col-md-12" style="margin-bottom:  15px;">
        <label class="title_name" style="color: #00B14F;">CREATE USER ROLE</label><br>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light" style="background-color: #f0f0f0;border-radius: 10px!important;color: black;border-bottom: 4px solid #00B14F;border-left: 4px solid #00B14F;border-right: 4px solid #00B14F;border: 4px solid #00B14F;">
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->

                        <form action="<?= site_url('user_role/do_tambah')  ?>" class="form-horizontal" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

                            <div class="form-body row">
                                <div class="col-md-4">
                                    <h4 class="form-section grey" style="font-weight: bold">

                                        USER ROLE NAME
                                    </h4>
                                    <div class="form-group">
                                        <div class="col-md-8 float-md-left">
                                            <input type="text" name="user_role" class="form-control" value="<?= @session()->getFlashdata('postdata')['user_role'] ?>" placeholder="input name user role">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h4 class="form-section grey" style="font-weight: bold;margin-bottom: 5px;">

                                        HAK AKSES
                                    </h4>
                                    <div class="form-group">
                                        <div class="col-md-12">

                                            <?php foreach ($menu_list as $key => $value) {
                                                echo '<input type="hidden" name="id_menu[]" value="' . $value['id_menu'] . '" />';
                                                if ($value['tipe'] == 1) {
                                                    $hak_akses = array('baca');
                                                } elseif ($value['tipe'] == 2) {
                                                    $hak_akses = array('baca', 'tambah', 'ubah', 'hapus', 'approvement');
                                                } elseif ($value['tipe'] == 3) {
                                                    $hak_akses = array('baca', 'export');
                                                } elseif ($value['tipe'] == 4) {
                                                    $hak_akses = array('baca', 'ubah');
                                                }

                                                $ch = ($value['tipe'] == '1') ? 'checked' : '';
                                            ?>
                                                <br /><br />
                                                <div class="md-checkbox-inline parent">
                                                    <label class="float-md-left col-md-3" style="font-weight: bold !important;"><?= 'Menu ' . $value['nama'] ?></label>
                                                    <div class="float-md-left col-md-9">
                                                        <?php foreach ($hak_akses as $checkbox) {
                                                            $checked = @@session()->getFlashdata('postdata')['checkbox_' . $checkbox][$value['id_menu']] ? 'checked' : '';

                                                        ?>
                                                            <div class="md-checkbox <?= $key == 0 ? 'checked' : '' ?>">
                                                                <input type="checkbox" name="checkbox_<?= $checkbox . "[" . $value['id_menu'] . "]" ?>" id="checkbox_<?= $checkbox . "[" . $value['id_menu'] . "]" . $key ?>" class="md-check" value="1" <?= $checked ?>>
                                                                <label for="checkbox_<?= $checkbox . "[" . $value['id_menu'] . "]" . $key ?>">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> <?= ucfirst($checkbox) ?></label>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>

                                            <?php
                                            }
                                            ?>


                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12" style="text-align: right;">
                                            <button type="submit" class="btn green" style="width: 25%;">CREATE</button>
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
    <label class="title_name" style="text-align: left;">USER ROLE LIST</label><br>
</div>
<div class="col-md-2" style="margin-top: 40px; margin-bottom:20px">
    <div class="actions">
        <?php if (!empty($akses[5])) { ?>
            <a class="btn green" style="width: 100%;" href="<?= site_url('user_role/approve') ?>"><i class="fa fa-check"></i> Approvement</a>
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
                    <div class="garisatas" style="margin-top: 40px"></div>
                    <div class="garisatas2"></div>
                    <table id="tabel" class="table table-striped table-bordered ">
                        <thead>
                            <tr style="background-color: #e4e4e4;height: 40px;border: 2px #e4e4e4 solid;">
                                <th class="kiri" style="width: 15px;">No</th>
                                <th class="kiri">User Role</th>
                                <th style="width:125px;" class="kiri">Action</th>
                            </tr>
                        </thead>
                        <tbody id="listData" class="list" style="color: black;font-weight: 800;text-align:left;border: 2px #e4e4e4 solid;">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    html {
        overflow-x: hidden;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script type="text/javascript">
    $(document).ready(function() {

        var oTable = $('#tabel').dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?php echo site_url("user_role/get_data"); ?>'
            },
            paginationType: "full_numbers",
            lengthMenu: [
                [15, 30, 75],
                [15, 30, 75]
            ],
            ordering: false,
            columns: [{
                    data: "id_user_role"
                },
                {
                    data: "user_role"
                },
                {
                    render: function(data, type, row) {
                        if ('<?= $akses[2] ?>' != '') {
                            var html = "<a class='tombolEdit' title='Edit' href=" + '<?= site_url('user/edit/') ?>' + " data-id='" + row['id_user_role'] +
                                "'><img src=" + '<?= base_url('assets/edit.png') ?>' + " style='width: 30px;'></a>&nbsp;";
                        }

                        if ('<?= $akses[3] ?>' != '') {
                            var html = "<a class='tombolHapus' title='Hapus' href='#' data-id='" + row['id_user_role'] +
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
            // reloadbutton();
        });

    });
</script>
<?= $this->endSection() ?>