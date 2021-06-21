<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php if (!empty($akses[1])) { ?>
    <div class="col-md-12" style="margin-bottom: 30px;">
        <label class="title_name" style="color: #00B14F;">USER REGISTRATION</label><br>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light" style="background-color: #f0f0f0;border-radius: 10px!important;color: black;border-bottom: 4px solid #00B14F;border-left: 4px solid #00B14F;border-right: 4px solid #00B14F;border: 4px solid #00B14F;">
                    <!-- <div class="portlet-title">
                    <div class="caption">
                        Form Tambah <?= ucwords(@$this->title) ?>
                    </div>
                </div> -->
                    <div class="portlet-body form">
                        <form action="<?= site_url('user/do_tambah')  ?>" class="form-horizontal" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            <input type="hidden" name="status" value="N">
                            <div class="form-body">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4" style="text-align: left; font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'username')) ?></label>
                                        <br /><br />
                                        <div class="col-md-2" style="width: 100%;">
                                            <input class="form-control" type="text" name="username" placeholder="<?= ucwords(str_replace('_', ' ', 'username')) ?>" value="<?= @session()->getFlashdata('postdata')->username ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label class="control-label col-md-4" style="text-align: left; font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'user_role')) ?></label>
                                        <br /><br />
                                        <div class="col-md-2" style="width: 100%;">
                                            <select class="form-control elipsis user_role " name="id_user_role">
                                                <option value="">Pilih User Role</option>
                                                <?php foreach ($user_role as $key => $c) :
                                                    $selected = '';
                                                    if ($c['id_user_role'] == @session()->getFlashdata('postdata')->id_user_role) {
                                                        $selected = 'selected';
                                                    }
                                                ?>
                                                    <option value="<?= $c['id_user_role'] ?>" <?= $selected ?>><?= $c['user_role'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4" style="text-align: left; font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'Name')) ?></label>
                                        <br /><br />
                                        <div class="col-md-2" style="width: 100%;">
                                            <input class="form-control" type="text" name="nama" placeholder="<?= ucwords(str_replace('_', ' ', 'Name')) ?>" value="<?= @session()->getFlashdata('postdata')->nama ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4" style="text-align: left; font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'email')) ?></label>
                                        <br /><br />
                                        <div class="col-md-2" style="width: 100%;">
                                            <input class="form-control" type="email" name="email" placeholder="<?= ucwords(str_replace('_', ' ', 'email')) ?>" value="<?= @session()->getFlashdata('postdata')->email ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4" style="text-align: left; font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'password')) ?></label>
                                        <br /><br />
                                        <div class="col-md-2" style="width: 100%;">
                                            <input class="form-control" type="password" name="password" placeholder="<?= ucwords(str_replace('_', ' ', 'password')) ?>" value="" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4" style="text-align: left; font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'confirm_password')) ?></label>
                                        <br /><br />
                                        <div class="col-md-2" style="width: 100%;">
                                            <input class="form-control" type="password" name="confirm_password" placeholder="<?= ucwords(str_replace('_', ' ', 'confirm_password')) ?>" value="" required />
                                        </div>
                                    </div>
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


<div class="col-md-10" style="margin-top: 30px; margin-bottom:10px">
    <label class="title_name" style="text-align: left;">USER LIST</label><br>
</div>
<div class="col-md-2" style="margin-top: 30px; margin-bottom:10px">
    <div class="actions">
        <?php if (!empty($akses[5])) { ?>
            <a class="btn green" style="width: 100%;" href="<?= site_url('user/approve') ?>"><i class="fa fa-check"></i> Approvement</a>
        <?php } ?>
    </div>
</div>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="garisatas" style="margin-top: 70px"></div>
            <div class="garisatas2"></div>
            <table id="tabel" class="table table-striped table-bordered ">
                <thead>
                    <tr style="background-color: #e4e4e4;height: 40px;border: 2px #e4e4e4 solid;">
                        <th class="kiri" style="width: 15px;">ID</th>
                        <th class="kiri">Username</th>
                        <th class="kiri">Name</th>
                        <th class="kiri">User Group</th>
                        <th class="kiri">Email</th>
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
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script type="text/javascript">
    $(document).ready(function() {

        var oTable = $('#tabel').dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?php echo site_url("user/get_data"); ?>'
            },
            paginationType: "full_numbers",
            lengthMenu: [
                [15, 30, 75],
                [15, 30, 75]
            ],
            ordering: false,
            columns: [{
                    data: "id_user"
                },
                {
                    data: "username"
                },
                {
                    data: "nama"
                },
                {
                    data: "user_role"
                },
                {
                    data: "email"
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
                    data: "action",
                    render: function(data, type, row) {
                        var html = '<input class="id_temp" type="hidden" name="id_temp" value="' + row['id_user'] +
                            '"/><button class="btn detail" style="background-color : #009a3c;color :azure; border-radius : 5px;">Approvement </button>';
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
            $('.make-switch').bootstrapSwitch();
        });

    });
</script>
<?= $this->endSection() ?>