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

<?= $this->section('js') ?>
<script>
    var loading = false;
    $(function() {
        var oTable = $('#tabel').dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?php echo site_url("service/get_list_dashboard"); ?>'
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
                    data: "nm_driver"
                },
                {
                    data: "nm_jenis"
                },
                {
                    data: "nm_tipe"
                },
                {
                    data: "nm_layanan"
                },
                {
                    data: "lokasi"
                },
                {
                    data: "sts_trx",
                    render: function(data, type, row) {
                        if (data == 2) {

                            return '<input type="hidden" class="sts_trx" value="' + data + '"/>On process'
                        }

                        if (data == 3) {

                            return '<input type="hidden" class="sts_trx" value="' + data + '"/>Finish'
                        }

                        if (data == 1) {

                            return '<input type="hidden" class="sts_trx" value="' + data + '"/>Waiting'
                        }

                        if (data == 4) {

                            return '<input type="hidden" class="sts_trx" value="' + data + '"/>Cancel'
                        }
                    }
                }

            ],
            language: {
                processing: '<img src="<?php echo base_url("assets/loading2.gif"); ?>"><br><p style="margin-top:-5px;">Loading</p>'
            }
        });

        get_list();
        get_statistik();
        setInterval(() => {
            get_list();
            get_statistik();
        }, 3000);

        setInterval(() => {
            update_durasi();
        }, 1000);

        function get_list() {
            if (!loading) {
                loading = true;

                $.post('<?= site_url() ?>' + '/service/get_list_queue', {
                    hayo: '<?= encode(hayo()) ?>',
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                }, function(respon) {
                    loading = false;
                    if (respon.status == 'sukses') {
                        var data = respon.data;
                        var count = 1;
                        var str = '';
                        $.each(data.queue, function(index, value) {
                            //var arr = get_durasi(value)
                            var jam = value.waktu.substring(0, 3).replace(":", "");
                            var menit = value.waktu.substring(3, 5).replace(":", "");
                            str += '<tr>' +
                                '<td>' + count + '</td>' +
                                '<td>' + value.nm_driver + '</td>' +
                                '<td>' + value.nm_jenis + '</td>' +
                                '<td>' + value.nm_tipe + '</td>' +
                                '<td>' + value.nm_layanan + '</td>' +
                                '<td>' + value.lokasi + '</td>'
                            if (jam >= 0 || menit >= 15) {
                                str += '<td><span class="tmbl tmbl-ijo durasi" data-waktu="' + value.waktu + '">' + value.waktu + '</span></td>';
                            } else {
                                str += '<td><span class="tmbl tmbl-merah durasi" data-waktu="' + value.waktu + '">' + value.waktu + '</span></td>';
                            };

                            str += '<td>' + (count == 1 ? '<form method="post" action="<?= site_url('dashboard/video_call') ?>"><input type="hidden" name="id_trx" value="' + value.id_trx + '" /><input type="hidden" name="link" value="' + value.link + '" /><button type="submit" style="border: none!important; background-color : transparent;"><img src="<?= base_url('assets/icon-video.png'); ?>" style="width: 30px;"></button></form>' : '') + '</td>' +
                                '</tr>';
                            count++;
                        })


                        $('.list_queue').html(str);

                        var count = 1;
                        var str = '';
                        $.each(data.onservice, function(index, value) {
                            str += '<tr>' +
                                '<td>' + count + '</td>' +
                                '<td>' + value.nm_driver + '</td>' +
                                '<td>' + value.nm_jenis + '</td>' +
                                '<td>' + value.nm_tipe + '</td>' +
                                '<td>' + value.nm_layanan + '</td>' +
                                '<td>' + value.lokasi + '</td>' +
                                '<td><span class="tmbl tmbl-ijo durasi" data-waktu="' + value.waktu + '">' + get_durasi(value.waktu) + '</span></td>' +
                                '<td>' + (count == 1 ? '<form method="post" action="<?= site_url('dashboard/video_call_reconnect') ?>"><input type="hidden" name="id_trx" value="' + value.id_trx + '" /><input type="hidden" name="link" value="' + value.link + '" /><button type="submit" style="border: none!important; background-color : transparent;"><img src="<?= base_url('assets/icon-video.png'); ?>" style="width: 30px;"></button></form>' : '') + '</td>' +
                                // '<td><img src="<?= base_url('assets/senyum.png'); ?>" style="width: 30px;"></td>'+
                                '</tr>';
                            count++;
                        })


                        $('.list_onservice').html(str);

                        update_durasi();

                    }
                }, 'json');
            }

        }

        function get_statistik() {
            $.post('<?= site_url() ?>' + '/service/get_statistik', {
                hayo: '<?= encode(hayo()) ?>',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }, function(respon) {

                if (respon.status == 'sukses') {
                    var data = respon.data;

                    $('.total_driver').text(data.total_driver);
                    $('.avg_time').text(data.avg_time);
                    $('.avg_rating').text(data.avg_rating);
                }
            }, 'json');
        }

        function update_durasi() {
            if (!loading) {
                var durasi = $('.durasi');
                // console.log(durasi);

                $.each(durasi, function(index, value) {
                    // console.log(Date.now() +' || ' + new Date($(value).data('waktu')));
                    $(value).text(get_durasi($(value).data('waktu')));

                })
            }

        }

        function get_durasi(waktu) {
            var diff = Math.abs(Date.now() - new Date(waktu));
            var seconds = Math.floor(diff / 1000);
            var minutes = Math.floor(seconds / 60);
            seconds = seconds % 60;
            var hours = Math.floor(minutes / 60);
            minutes = minutes % 60;

            return hours + ":" + minutes + ":" + seconds;
        }
    })

    Highcharts.theme = {
        colors: ['#005339', '#00b14f', '#d3f035', '#7798BF', '#aaeeee', '#ff0066',
            '#eeaaee', '#55BF3B', '#DF5353', '#7798BF', '#aaeeee'
        ],
        chart: {
            backgroundColor: null,
            style: {
                fontFamily: 'Dosis, sans-serif'
            }
        },
        title: {
            style: {
                fontSize: '16px',
                fontWeight: 'bold',
                textTransform: 'uppercase'
            }
        },
        tooltip: {
            borderWidth: 0,
            backgroundColor: 'rgba(219,219,216,0.8)',
            shadow: false
        },
        legend: {
            backgroundColor: '#F0F0EA',
            itemStyle: {
                fontWeight: 'bold',
                fontSize: '13px'
            }
        },
        xAxis: {
            gridLineWidth: 1,
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        },
        yAxis: {
            minorTickInterval: 'auto',
            title: {
                style: {
                    textTransform: 'uppercase'
                }
            },
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        },
        plotOptions: {
            candlestick: {
                lineColor: '#00b14f'
            }
        }
    };
    // Apply the theme
    Highcharts.setOptions(Highcharts.theme);


    setInterval(grafik_all_service, 15000);
    setInterval(grafik_per_bulan, 15000);

    grafik_all_service();

    function grafik_all_service() {

        var filter = $('#filter').val();
        $.ajax({
                url: '<?= site_url('dashboard/grafik_all_service') ?>',
                dataType: 'json',
                data: {
                    filter: filter
                }
            })
            .done(function(res) {
                $('#tes').highcharts({
                    chart: {
                        type: 'areaspline'
                    },
                    title: {
                        text: 'Total Handling Per Month'
                    },
                    xAxis: {
                        gridLineWidth: 0,
                        minorGridLineWidth: 0,
                        // categories: [
                        //     'Monday',
                        //     'Tuesday',
                        //     'Wednesday',
                        //     'Thursday',
                        //     'Friday',
                        //     'Saturday',
                        //     'Sunday'
                        // ],
                        categories: res.kategori
                    },
                    yAxis: {
                        gridLineWidth: 0,
                        minorGridLineWidth: 0,
                        title: {
                            text: 'Handling'
                        }
                    },
                    tooltip: {
                        shared: true,
                        valueSuffix: ''
                    },
                    credits: {
                        enabled: false
                    },
                    plotOptions: {
                        areaspline: {
                            fillOpacity: 1
                        }
                    },
                    series: [{
                        name: 'Total All Service',
                        data: res.data
                    }]
                });
            });

    }

    grafik_per_bulan();

    function grafik_per_bulan() {

        var filter = $('#filter').val();

        $.ajax({
                url: '<?= site_url('dashboard/grafik_per_bulan') ?>',
                data: {
                    filter: filter
                },
                dataType: 'json',
            })
            .done(function(res) {
                $('#dong').highcharts({
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: 0,
                        plotShadow: false
                    },
                    title: {
                        text: 'DAX TYPE <br>STATISTIC<br>',
                        align: 'center',
                        verticalAlign: 'middle',
                        y: 60
                    },
                    credits: {
                        enabled: false
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y}</b>'
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            dataLabels: {
                                enabled: true,
                                distance: -50,
                                style: {
                                    fontWeight: 'bold',
                                    color: 'white'
                                }
                            },
                            startAngle: -90,
                            endAngle: 90,
                            center: ['50%', '75%'],
                            size: '110%'
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: 'Total',
                        innerSize: '50%',
                        data: [
                            ['GrabCar', res.received],
                            ['GrabBike', res.incoming],
                            ['TPI', res.receiveds]
                        ]
                    }]
                });
            });
    }
</script>
<?= $this->endSection() ?>