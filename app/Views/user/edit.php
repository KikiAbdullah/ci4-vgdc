<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    Form Edit <?= ucwords(@$this->title) ?>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="<?= site_url('user/do_ubah')  ?>" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'username')) ?></label>
                            <div class="col-md-2" style="width: 70%;">
                                <input class="form-control" type="text" name="username" placeholder="<?= ucwords(str_replace('_', ' ', 'username')) ?>" value="<?= @$item['username'] ?>" required readonly />
                                <input class="form-control" type="hidden" name="id_user" placeholder="<?= ucwords(str_replace('_', ' ', 'id_user')) ?>" value="<?= @$item['id_user'] ?>" required readonly />
                            </div>
                        </div>
                        <!-- <div class="form-group">
							<label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'CS_Name')) ?></label>
							<div class="col-md-2" style="width: 70%;">
								<input class="form-control" type="text" name="nm_cs" placeholder="<?= ucwords(str_replace('_', ' ', 'CS_Name')) ?>" value="<?= @$item['nm_cs'] ?>" required/>
							</div>
						</div> -->
                        <div class="form-group">
                            <label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'Name')) ?></label>
                            <div class="col-md-3" style="width: 70%;">
                                <input class="form-control" type="text" name="nama" placeholder="<?= ucwords(str_replace('_', ' ', 'Name')) ?>" value="<?= @$item['nama'] ?>" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'email')) ?></label>
                            <div class="col-md-2" style="width: 70%;">
                                <input class="form-control" type="email" name="email" placeholder="<?= ucwords(str_replace('_', ' ', 'email')) ?>" value="<?= @$item['email'] ?>" required />
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'user_role')) ?></label>
                            <div class="col-md-2" style="width: 70%;">
                                <select class="form-control elipsis user_role " name="id_user_role">
                                    <option value="">Pilih User Role</option>
                                    <?php foreach ($user_role as $key => $c) :
                                        $selected = '';
                                        if ($c['id_user_role'] == @$item['id_user_role']) {
                                            $selected = 'selected';
                                        }
                                    ?>
                                        <option value="<?= $c['id_user_role'] ?>" <?= $selected ?>><?= $c['user_role'] ?></option>
                                    <?php endforeach ?>

                                </select>
                            </div>
                        </div>
                        <div class="form-group "><br>
                            <label class="control-label col-md-2" style="font-weight: bold;">Ganti Password</label>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'password')) ?></label>
                            <div class="col-md-2" style="width: 70%;">
                                <input class="form-control" type="password" name="password" placeholder="<?= ucwords(str_replace('_', ' ', 'password')) ?>" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'confirm_password')) ?></label>
                            <div class="col-md-2" style="width: 70%;">
                                <input class="form-control" type="password" name="confirm_password" placeholder="<?= ucwords(str_replace('_', ' ', 'confirm_password')) ?>" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'status')) ?></label>
                            <div class="col-md-2">
                                <input type="checkbox" class="make-switch" name="status" <?= $item['status'] == 'Y' ? 'checked' : '' ?> data-on-text="&nbsp;Active&nbsp;" data-off-text="&nbsp;Not Active&nbsp;" data-on-color="primary" data-off-color="danger">
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-2 col-md-9">
                                <button type="submit" class="btn green">Simpan</button>
                                <a href="<?= site_url('user') ?>" class="btn default">Kembali</a>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>