<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat putih">
            <div class="visual">
                <img src="<?= base_url('assets/total-handling.png'); ?>" style="width: 50px;">
            </div>
            <div class="details">
                <div class="number total_driver">
                    0
                </div>
                <div class="desc"> Total Handling</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat putih4">
            <div class="visual">
                <img src="<?= base_url('assets/average-time.png'); ?>" style="width: 50px;">
            </div>
            <div class="details">
                <div class="number avg_time"> 00:00 </div>
                <div class="desc"> Average Handling Time</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat putih">
            <div class="visual">
                <img src="<?= base_url('assets/csat-point.png'); ?>" style="width: 50px;">
            </div>
            <div class="details">
                <div class="number avg_rating">
                    0
                </div>
                <div class="desc"> CSAT Point</div>
            </div>
        </div>
    </div>

    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
        <br>
        <br>
        <div id="tes" style="background-color: white;margin-bottom: 44px;">
        </div>
    </div>

    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
        <br>
        <br>
        <div id="dong" style="background-color: white;margin-bottom: 44px;">
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <!-- <i class="fa fa-search"></i><?= ucwords('Waiting List Today') ?> -->
                    <i class="fa fa-list"></i><b style="color:#00b14f;">Waiting List Today</br>
                </div>
                <div class="tools">
                    <a href="" class="collapse"> </a>
                </div>
            </div>
            <div class="portlet-body form">
                <br><br>
                <div class="col-md-12 garisatas" style="margin-top: 5px"></div>
                <div class="col-md-12 garisatas2"></div>
                <table id="tabel" class="table table-striped table-bordered ">
                    <thead>
                        <tr style="background-color: #e4e4e4;height: 40px;border: 2px #e4e4e4 solid;">
                            <!-- <th class="kiri">No</th> -->
                            <th class="kiri"><b>Date</b></th>
                            <th class="kiri"><b>DAX Name</b></th>
                            <th class="kiri"><b>DAX Type</b></th>
                            <th class="kiri"><b>DAX Status</b></th>
                            <th class="kiri"><b>Service</b></th>
                            <th class="kiri"><b>KiosK Location</b></th>
                            <th class="kiri"><b>Status</b></th>
                        </tr>
                    </thead>
                    <tbody id="listData" class="list" style="color: black;font-weight: 800;text-align: left;border: 2px #e4e4e4 solid;">
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</div>
<?= $this->endSection() ?>