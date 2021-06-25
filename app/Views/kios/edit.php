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
				<form action="<?= site_url('kios/do_ubah')  ?>" class="form-horizontal" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'KiosK_Name')) ?></label>
							<div class="col-md-2" style="width: 70%;">
								<input class="form-control" type="text" name="nm_gdc" placeholder="<?= ucwords(str_replace('_', ' ', 'KiosK_Name')) ?>" value="<?= @$item['nm_gdc'] ?>" required />
								<input class="form-control" type="hidden" name="id_gdc" placeholder="<?= ucwords(str_replace('_', ' ', 'id_gdc')) ?>" value="<?= @$item['id_gdc'] ?>" required readonly />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'KiosK_Email')) ?></label>
							<div class="col-md-2" style="width: 70%;">
								<input class="form-control" type="email" name="email_gdc" placeholder="<?= ucwords(str_replace('_', ' ', 'KiosK_Email')) ?>" value="<?= @$item['email_gdc'] ?>" required />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'Location')) ?></label>
							<div class="col-md-2" style="width: 70%;">
								<input class="form-control" type="text" name="lokasi" placeholder="<?= ucwords(str_replace('_', ' ', 'Location')) ?>" value="<?= @$item['lokasi'] ?>" required />
							</div>
						</div>
						<br>
						<div class="form-group">
							<label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'IP_server')) ?></label>
							<div class="col-md-3" style="width: 70%;">
								<input class="form-control" type="text" name="ipserver" placeholder="<?= ucwords(str_replace('_', ' ', 'IP_Server')) ?>" value="<?= @$item['ipserver'] ?>" required />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'IP_KiosK')) ?></label>
							<div class="col-md-2" style="width: 70%;">
								<input class="form-control" type="text" name="ip_gdc" placeholder="<?= ucwords(str_replace('_', ' ', 'IP_KiosK')) ?>" value="<?= @$item['ip_gdc'] ?>" required />
							</div>
						</div>
						<br>
						<div class="form-group">
							<label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'apikey')) ?></label>
							<div class="col-md-3" style="width: 70%;">
								<input class="form-control" type="text" name="apikey" placeholder="<?= ucwords(str_replace('_', ' ', 'apikey')) ?>" value="<?= @$item['apikey'] ?>" required />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'Device_id')) ?></label>
							<div class="col-md-3" style="width: 70%;">
								<input class="form-control" type="text" name="id_device" placeholder="<?= ucwords(str_replace('_', ' ', 'Device_id')) ?>" value="<?= @$item['id_device'] ?>" required />
							</div>
						</div>
						<br>
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
					</div>
					<div class="form-group">
						<label class="control-label col-md-2" style="font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'status')) ?></label>
						<div class="col-md-2">
							<input type="checkbox" class="make-switch" name="status" <?= $item['status'] == 'Y' ? 'checked' : '' ?> data-on-text="&nbsp;Active&nbsp;" data-off-text="&nbsp;Not Active&nbsp;" data-on-color="primary" data-off-color="danger">
						</div>
					</div>
					<br>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-2 col-md-9">
								<button type="submit" class="btn green">Simpan</button>
								<a href="<?= site_url('kios') ?>" class="btn default">Kembali</a>
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