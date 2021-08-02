<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="col-md-12" style="margin-bottom: 30px;">
    <label class="title_name" style="color: #00B14F;">UPDATE</label><br>
</div>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light" style="background-color: #f0f0f0;border-radius: 10px!important;color: black;border-bottom: 4px solid #00B14F;border-left: 4px solid #00B14F;border-right: 4px solid #00B14F;border: 4px solid #00B14F;">
                <!-- <div class="portlet-title">
                    <div class="caption">
                        Form Tambah <?= ucwords(@$title) ?>
                    </div>
                </div> -->
                <div class="portlet-body form">
                    <form action="<?= site_url('update/do_tambah')  ?>" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                        <input type="hidden" name="status" value="N">
                        <div class="form-body">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4" style="text-align: left; font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'Name app')) ?></label>
                                    <br /><br />
                                    <div class="col-md-2" style="width: 100%;">
                                        <input class="form-control" type="text" name="nm_app" placeholder="<?= ucwords(str_replace('_', ' ', 'Name app')) ?>" value="<?= @session()->getFlashdata('postdata')->username ?>" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4" style="text-align: left; font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'id app')) ?></label>
                                    <br /><br />
                                    <div class="col-md-2" style="width: 100%;">
                                        <input class="form-control" type="text" name="id_app" placeholder="<?= ucwords(str_replace('_', ' ', 'id app')) ?>" value="<?= @session()->getFlashdata('postdata')->nama ?>" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4" style="text-align: left; font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'version')) ?></label>
                                    <br /><br />
                                    <div class="col-md-2" style="width: 100%;">
                                        <input class="form-control" type="text" name="ver" placeholder="<?= ucwords(str_replace('_', ' ', 'version')) ?>" value="<?= @session()->getFlashdata('postdata')->nama ?>" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4" style="text-align: left; font-weight: bold;"><?= ucwords(str_replace('_', ' ', 'attachment')) ?></label>
                                    <br /><br />
                                    <div class="col-md-2" style="width: 100%;">
                                        <input class="btn btn-secondary" type="file" name="attach" placeholder="<?= ucwords(str_replace('_', ' ', 'attachment')) ?>" value="<?= @session()->getFlashdata('postdata')->attach ?>" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="col-md-12">
                                <div class="row">
                                    <br /><br />
                                    <div class="col-md-12" style="text-align: right;">
                                        <button type="submit" class="btn green" style="width: 25%;">Upload</button>
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

<div class="col-md-12" style="margin-top: 30px; margin-bottom:10px">
    <label class="title_name" style="text-align: left;">UPDATE LIST</label><br>
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
                        <th class="kiri">Name App</th>
                        <th class="kiri">Version</th>
                        <th class="kiri">Upload At</th>
                        <th class="kiri">Attach</th>
                        <th class="kiri">Action</th>
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
    function reloadbutton() {
        $('.tombolHapus').each(function() {
            var id = $(this).data('id');
            //alert(id);
            encodedString = btoa(id).replace('==', '').replace('=', '');
            var link = "<?= site_url('update/hapus'); ?>";
            $(this).attr('onclick', "hapus('" + link + '/' + encodedString + "');");
        });
    }

    $(document).ready(function() {

        var oTable = $('#tabel').dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?php echo site_url("update/get_data"); ?>'
            },
            paginationType: "full_numbers",
            lengthMenu: [
                [10, 25, 65],
                [10, 25, 65]
            ],
            ordering: false,
            columns: [{
                    data: null,
                    sortable: true,
                    render: function(data, type, row, meta) {
                        index = meta.row + meta.settings._iDisplayStart + 1;
                        return '<center>' + (index) + '</center>';
                    }
                },
                {
                    data: "nm_app"
                },
                {
                    data: "version"
                },
                {
                    data: "tgl_update"
                },
                {
                    data: "attach"
                },
                {
                    render: function(data, type, row) {
                        var html = '';
                        var html = "<a class='tombolHapus' title='Hapus' href='#' data-id='" + row['id_update'] +
                            "'><img src=" + '<?= base_url('assets/delete.png') ?>' + " style='width: 30px;'></a>";
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
</script>
<?= $this->endSection() ?>