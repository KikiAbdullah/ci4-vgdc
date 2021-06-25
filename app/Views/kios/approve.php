<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="col-md-12" style="margin-bottom:10px">
    <label class="title_name" style="color: #00B14F;">GDC KIOSK APPROVEMENT</label><br>
</div>

<div class="modal fade bs-modal-dm detailmod" tabindex="-1" role="dialog" aria-hidden="true" style="margin-top: 68px;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <br><br>
                <h4 id="myModalLabel" class="bold">Proses Approvement</h4>
                <br>
                <p class="no-margin">Tekan tombol <b>Approve</b> untuk menyetujui penambahan/perubahan data GDCKiosk Baru atau tekan tombol <b>Reject</b> untuk menolak penambahan/perubahan data GDCKiosk</p>
                <br>
            </div>
            <div class="modal-footer">
                <br>
                <div class="col-md-2">
                    <button type="button" class="btn btn-white" data-dismiss="modal" style="width: -webkit-fill-available">Batal</button>
                </div>
                <div class="col-md-5">
                    <a href="#" class="btn btn-primary-f2 approve" style="width: -webkit-fill-available">Approve</a>
                </div>
                <div class="col-md-5">
                    <a href="#" class="btn btn-primary-f reject" style="width: -webkit-fill-available">Reject</a>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="garisatas" style="margin-top: 70px"></div>
            <div class="garisatas2"></div>
            <table id="tabel" class="table table-striped table-bordered ">
                <thead>
                    <tr style="background-color: #e4e4e4;height: 40px;border: 2px #e4e4e4 solid;">
                        <th class="kiri" style="width: 150px;">Datetime</th>
                        <th class="kiri" style="width: 100px;">Action Type</th>
                        <th class="kiri">GDC Name</th>
                        <th class="kiri">Location</th>
                        <th class="kiri">User</th>
                        <th class="kiri" style="width: 80px;"> </th>
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
    var loading = false;
    $(function() {

        $('body').on('click', '.detail', function() {
            console.log($(this).closest('tr').find('.id_temp').val());
            detail($(this).closest('tr').find('.id_temp').val());
        })
    });

    function detail(id_temp) {
        var link = '<?= site_url('kios/approved') ?>';

        $('.approve').attr('href', link + '/' + id_temp + '/' + 'Y');
        $('.reject').attr('href', link + '/' + id_temp + '/' + 'N');

        $('.detailmod').modal();
    }

    $(document).ready(function() {

        var oTable = $('#tabel').dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?php echo site_url("kios/get_data_temp"); ?>'
            },
            paginationType: "full_numbers",
            lengthMenu: [
                [15, 30, 75],
                [15, 30, 75]
            ],
            ordering: false,
            columns: [{
                    data: "a"
                },
                {
                    data: "ket"
                },
                {
                    data: "nm_gdc"
                },
                {
                    data: "lokasi"
                },
                {
                    data: "nama"
                },
                {
                    render: function(data, type, row) {
                        return '<input class="id_temp" type="hidden" name="id_temp" value="' + row['id_temp'] + '"/><button class="btn detail" style="background-color:#009a3c; color:azure; border-radius: 5px;"> Approvement </button>'
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
</script>
<?= $this->endSection() ?>