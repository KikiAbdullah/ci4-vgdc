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
                <form action="<?= site_url('user_role/do_ubah')  ?>" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

                    <div class="form-body row">
                        <div class="col-md-3">
                            <h4 class="form-section grey" style="font-weight: bold">
                                <i class="icon-info"></i>
                                User Role
                            </h4>
                            <div class="form-group">
                                <!-- <label class="control-label col-md-4 float-md-left">User Role</label> -->
                                <div class="col-md-8 float-md-left">
                                    <input type="hidden" name="id_user_role" value="<?= $data['id_user_role'] ?>" />
                                    <input type="text" name="user_role" class="form-control elipsis" value="<?= @$data['user_role'] ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h4 class="form-section grey" style="font-weight: bold;margin-bottom: 5px;">
                                <i class="icon-info"></i>
                                Hak Akses
                            </h4>
                            <div class="form-group">
                                <!-- <label class="control-label col-md-3">Hak Akses</label> -->
                                <div class="col-md-12">
                                    <?php foreach ($menu_list as $value) {
                                        echo '<input type="hidden" name="id_menu[]" value="' . $value['id_menu'] . '" />';
                                        if ($value['tipe'] == 1) {
                                            $akses = array(
                                                '0' => 'baca'
                                            );
                                        } elseif ($value['tipe'] == 2) {
                                            $akses = array(
                                                '0' => 'baca',
                                                '1' => 'tambah',
                                                '2' => 'ubah',
                                                '3' => 'hapus',
                                                '5' => 'approvement'
                                            );
                                        } elseif ($value['tipe'] == 3) {
                                            $akses = array(
                                                '0' => 'baca',
                                                '4' => 'export'
                                            );
                                        } elseif ($value['tipe'] == 4) {
                                            $akses = array(
                                                '0' => 'baca',
                                                '2' => 'ubah'
                                            );
                                        }
                                    ?>
                                        <br /><br />
                                        <div class="md-checkbox-inline parent">
                                            <label class="float-md-left col-md-3" style="font-weight: bold;"><?= 'Menu ' . $value['nama'] ?></label>
                                            <div class="float-md-left col-md-9">
                                                <?php foreach ($akses as $key => $checkbox) {
                                                    $isi_akses = !empty(@$data['menu_akses']) ? @$data['menu_akses'][$value['id_menu']][$key] : @$this->session->flashdata('postdata')['checkbox_' . $checkbox][$value['id_menu']];

                                                    $checked = $isi_akses == 1 ? 'checked' : '';
                                                ?>
                                                    <div class="md-checkbox <?= $key == 0 ? 'checked' : '' ?>">
                                                        <input type="checkbox" name="checkbox_<?= $checkbox . "[" . $value['id_menu'] . "]" ?>" <?= @$checked ?> value="1" id="checkbox_<?= $checkbox . "[" . $value['id_menu'] . "]" . $key ?>" class="md-check">
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
                                    <button type="submit" class="btn green">Simpan</button>
                                    <a href="<?= site_url('user_role') ?>" class="btn default">Kembali</a>
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
<?= $this->endSection() ?>