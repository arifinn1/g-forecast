@extends('layouts.light')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="card" id="card-pbulan">
            <div class="card-header">
                <h4 class="card-title">Peramalan Bulanan</h4>
                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                        <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body collapse in">
                <div class="card-block card-dashboard">
                    <form id="form-ramal" class="form" method="post" autocomplete="off" onsubmit="return Proses()">
                        {{ csrf_field() }}
                        <div class="form-body overflow-none">
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                      <label for="cari_prod">Cari Produk</label>
                                      <input type="hidden" id="kd_prod">
                                      <input type="text" id="cari_prod" class="form-control" placeholder="Ketik nama produk > klik Enter" maxlength="100" required>
                                    </div>
                                </div>
                                <div class="col-sm-8 col-md-5">
                                    <div class="form-group">
                                        <label for="nama">Nama Produk</label>
                                        <div class="text-info" id="nm_prod_txt">...</div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-3">
                                    <div class="form-group">
                                        <label for="nama">Panjang data</label>
                                        <div class="text-info" id="panjang_txt">...</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="alpha">Alpha</label>
                                        <input type="number" step="0.001" id="alpha" class="form-control" placeholder="Alpha" name="alpha" value="0.400" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="gamma">Gamma</label>
                                        <input type="number" step="0.001" id="gamma" class="form-control" placeholder="Gamma" name="gamma" value="0.500" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="error_txt">Error</label>
                                        <div class="text-info" id="error_txt">...</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-check2"></i> Proses
                            </button>
                        </div>
                    </form>
                    <div class="chartjs">
                        <canvas id="line-chart" height="500"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade text-xs-left" id="modal-cari" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Basic Modal</h4>
      </div>
      <div class="modal-body no-padding">
        <div class="loading text-center" id="cari-loading">
          <img src="{{ asset('loading/pinwheel.svg') }}">
        </div>
        <table class="table table-hover table-selected mb-0" id="cari-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Panjang</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
      $("#cari_prod").keyup(function(event) {
          if (event.keyCode === 13) {
              Cari();
          }
      });
  });

  function Cari(){
    $('#cari-table').hide();
    $('#cari-loading').show();
    $('#modal-cari').modal('show');

    jQuery.ajax({
      type: "POST",
      url: "/ramal/bulan/cari_produk",
      data: { _token: "{{ csrf_token() }}", keyword: $('#cari_prod').val() },
      success: function(res)
      {
        var data = JSON.parse(res);
        var row = '', nama = '';
        for (var i = 0, len = data.length; i < len; i++) {
          nama = data[i]['nm_lain']=='' ? data[i]['nm_db'] : data[i]['nm_lain'];
          row += `<tr
            onclick="PilihProduk(${data[i]['kd_prod']},'${nama}',${data[i]['panjang']})"
            ><td>${i+1}</td><td>${nama}</td><td>${data[i]['panjang']}</td></tr>`;
        }          
        $('#modal-cari tbody').html(row);
        $('#modal-cari .modal-title').html("Pencarian keyword '"+$('#cari_prod').val()+"', "+(data.length)+" data ditemukan");
        
        $('#cari-table').show();
        $('#cari-loading').hide();
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        if(confirm("Cari data gagal, coba lagi?")){ Cari(); }else{ $('#modal-cari').modal('hide'); }
      }
    });
    return false;
  }

  function PilihProduk(kd, nama, panjang){
    $('#cari-table').hide();
    $('#cari-loading').show();
    
    $('#kd_prod').val(kd);
    $('#nm_prod_txt').html(nama);
    $('#panjang_txt').html(panjang);

    jQuery.ajax({
      type: "POST",
      url: "/ramal/bulan/ambil_penjualan",
      data: { _token: "{{ csrf_token() }}", kd_prod: kd },
      success: function(res)
      {
        res = res.split("||");
        for(var i=0; i<res.length; i++){ res[i] = JSON.parse(res[i]); }
        //console.log(res);

        //data_ramal = res[1];
        SetDataChart(res[0]);
        BuatChart();

        $('#modal-cari').modal('hide');
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        if(confirm("Tarik data penjualan gagal, coba lagi?")){ PilihProduk(kd, nama, panjang); }
      }
    });
  }

  function SetDataChart(data){
    data_labels = [];
    data_penju = [];
    for(var i=0; i<data.length; i++){
      data_labels[i] = month_by_int(data[i]['bulan'])+" "+(data[i]['tahun']).toString().substring(2);
      if((i+1) == data.length){
        data_labels[i+1] = month_by_int(data[i]['bulan']<12 ? data[i]['bulan']+1 : 1)+" "+(data[i]['bulan']<12 ? data[i]['tahun'] : data[i]['tahun']+1).toString().substring(2);
      }

      data_penju[i] = parseFloat(data[i]['jumlah']);
    }
  }

  function Proses(){
    return false;
  }

  var data_labels = [];
  var data_penj = [];
  var data_penju = [];
  var data_ramal = [];

  function Peramal(alp_gam, show_ftm = false){
    var st=[], dt=[], ftm=[];
    var mse=0, mape=0;

    for(var i=0; i<data_penju.length; i++){
      if(i==0){
        st[i] = null;
        dt[i] = null;
        ftm[i] = null;
      }else if(i<=1){
        st[i] = data_penju[0];
        dt[i] = data_penju[1]-data_penju[0];
        ftm[i] = data_penju[0];
      }else{
        st[i] = (alp_gam[0] * data_penju[i])+((1-alp_gam[0]) * (st[i-1]+dt[i-1]));
        dt[i] = (alp_gam[1] * (st[i]-st[i-1]))+((1-alp_gam[1]) * dt[i-1]);
        ftm[i] = st[i-1] + dt[i-1];
        if((i+1) == data_penju.length){
          ftm[i+1] = st[i] + dt[i];
        }
      }

      mse += i>0 ? Math.pow(data_penju[i] - ftm[i], 2) : 0;
      mape += i>0 ? (data_penju[i] - ftm[i]) / data_penju[i] : 0;
    }

    mse = mse / (data_penju.length - 1);
    mape = (mape / (data_penju.length - 1)) * 100;
    if(show_ftm){ return { ftm: ftm, mse: mse, mape: mape };
    }else{ return { alpha: alp_gam[0], gamma: alp_gam[1], mse: mse, mape: mape, mapee: Math.abs(mape) }; }
  }

  function Overcross(gen1, gen2){
    var random = Math.random(), r1 = random/gen2, r2 = random/gen1, gen1_, gen2_;
    if((gen2<0 && gen1<gen2) || (gen2>=0 && gen1>gen2)){
      gen1_ = gen1-(r1*gen2);
      gen2_ = gen2+(r2*gen1);
    }else{ 
      gen1_ = gen1+(r1*gen2);
      gen2_ = gen2-(r2*gen1);
    }

    if(gen1_>0 && gen1_<1 && gen2_>0 && gen2_<1){
      return [gen1, gen2, gen1_, gen2_];
    }else{
      return Overcross(gen1, gen2);
    }
  }

  function Mutation(gen){
    var ret = gen + ((Math.random() * 2 - 1) * 1);
    if(ret<0 || ret>1){
      ret = Mutation(gen);
    }
    return ret;
  }

  function RandomizeC(pop_size, pc){
    var ret=[], temp1=[], temp2=[], count=0, idx=-1;
    for(var i=0; i<pop_size; i++){
      temp1.push(Math.random());
      if(temp1[i]<=pc){
        count++;
        if(count%2==1){
          temp2 = [];
          temp2.push(i);
        }else{
          temp2.push(i);
          ret.push(temp2);
        }
      }

      if(i==9 && count%2==1 && temp1[i]>pc){
        temp2.push(i);
        ret.push(temp2);
        break;
      }
    }

    return ret;
  }

  function RandomizeM(pop_size, pm){
    var ret=[], rand1, rand2;
    for(var i=0; i<pop_size; i++){
      rand1 = Math.random();
      rand2 = Math.random();
      if(rand1<=pm || rand2<=pm){
        ret.push([i, rand1<=pm ? 0 : 1]);
      }
      //ret.push([Math.random(), Math.random()]);
    }

    if(ret.length == 0){
      ret = RandomizeM(pop_size, pm);
    }

    return ret;
  }

  function Selection(pop_size, _eval, _cross, _mut){
    var _cm = _cross.concat(_mut), _sel = [];
    _eval.sort(function(a, b) { return a['mapee']-b['mapee']; });
    _cm.sort(function(a, b) { return a['mapee']-b['mapee']; });

    for(var i=0; i<_cm.length; i++){
      if(_cm[i]['mapee'] < _eval[pop_size-1]['mapee']){ _sel.push(_cm[i]); }
    }

    var _e = pop_size-_sel.length, _s = 0;
    while(_e < pop_size){
      if(_eval[_e]['mapee'] > _sel[_s]['mapee']){
        _sel[_s]['index'] = _eval[_e]['index'];
        _eval[_e] = _sel[_s];
        _eval[_e]['offs'] = 1;
        _s++;
      }
      _e++;
    }
    
    return _eval.sort(function(a, b) { return a['index']-b['index']; });
  }

  function OperasiGenetika(){
    data_labels = [];
    data_penju = [];
    for(var i=0; i<data_penj.length; i++){
      data_labels[i] = month_by_int(data_penj[i]['bulan'])+" "+(data_penj[i]['tahun']).toString().substring(2);
      if((i+1) == data_penj.length){
        data_labels[i+1] = month_by_int(data_penj[i]['bulan']<12 ? data_penj[i]['bulan']+1 : 1)+" "+(data_penj[i]['bulan']<12 ? data_penj[i]['tahun'] : data_penj[i]['tahun']+1).toString().substring(2);
      }

      data_penju[i] = parseFloat(data_penj[i]['jumlah']);
    }

    var pop_size=10, maxgen=10, pm=0.1, pc=0.3;

    var P=[], Eval=[], Offs=[], Err=[], temp_p=[], Pcross=[], Pmut=[];

    for(var i=0; i<maxgen; i++){
      Eval[i] = [];
      Pcross[i] = [];
      Pmut[i] = [];

      if(i==0){
        temp_p = [];
        for(var j=0; j<pop_size; j++){ temp_p.push([Math.random(), Math.random()]); }
        P[i] = temp_p.slice();
      }

      for(var j=0; j<pop_size; j++){
        Eval[i].push(Peramal(P[i][j]));
        Eval[i][j]['index'] = j;
      }

      var temp1, temp2, temp3, temp_rand = RandomizeC(pop_size, pc);
      for(var k=0; k<temp_rand.length; k++){
        temp1=[0,0], temp2=[0,0];
        temp3 = Overcross(P[i][temp_rand[k][0]][0], P[i][temp_rand[k][1]][0]);
        temp1[0] = temp3[2];
        temp2[0] = temp3[3];
        temp3 = Overcross(P[i][temp_rand[k][0]][1], P[i][temp_rand[k][1]][1]);
        temp1[1] = temp3[2];
        temp2[1] = temp3[3];
        Pcross[i].push(Peramal(temp1));
        Pcross[i].push(Peramal(temp2));
      }

      temp_rand = RandomizeM(pop_size, pm);
      for(var l=0; l<temp_rand.length; l++){
        temp1 = [P[i][temp_rand[l][0]][0], P[i][temp_rand[l][0]][1]];
        temp1[temp_rand[l][1]] = Mutation(temp1[temp_rand[l][1]]);
        Pmut[i].push(Peramal(temp1));
      }

      Offs[i] = Selection(pop_size, Eval[i].slice(), Pcross[i].slice(), Pmut[i].slice());
      
      temp_p = [], Err[i] = 0;
      for(var j=0; j<pop_size; j++){
        temp_p.push([Offs[i][j]['alpha'], Offs[i][j]['gamma']]);
        Err[i] += Offs[i][j]['mapee'];
      }
      Err[i] = Err[i] / pop_size;
      P[i+1] = temp_p.slice();
    }

    console.log(Err);
    console.log(Offs[0]);
    console.log(Offs[maxgen-1]);

    var hasil = Offs[maxgen-1].slice();
    hasil.sort(function(a, b) { return a['mapee']-b['mapee']; });
    hasil = Peramal([ hasil[0]['alpha'], hasil[0]['gamma'] ], true);
    data_ramal = hasil['ftm'];

    BuatChart();
  }

  var lineChart;

  function BuatChart(){
    //Get the context of the Chart canvas element we want to select
    var ctx = $("#line-chart");

    // Chart Options
    var chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        position: 'bottom',
      },
      hover: {
        mode: 'label'
      },
      scales: {
        xAxes: [{
          display: true,
          gridLines: {
            color: "#f3f3f3",
            drawTicks: false,
          },
          scaleLabel: {
            display: true,
            labelString: 'Periode'
          }
        }],
        yAxes: [{
          display: true,
          gridLines: {
            color: "#f3f3f3",
            drawTicks: false,
          },
          scaleLabel: {
            display: true,
            labelString: 'Penjualan'
          }
        }]
      },
      title: {
        display: true,
        text: 'Ramalan Bulan'
      }
    };

    var data_set = [{
        label: "Nilai Penjualan",
        data: data_penju,
        fill: false,
        borderColor: "#fdcf09",
        pointBorderColor: "#fdcf09",
        pointBackgroundColor: "#FFF",
        pointBorderWidth: 2,
        pointHoverBorderWidth: 2,
        pointRadius: 4,
      },{
        label: "Peramalan",
        data: data_ramal,
        fill: false,
        borderDash: [5, 5],
        borderColor: "#db2727",
        pointBorderColor: "#db2727",
        pointBackgroundColor: "#FFF",
        pointBorderWidth: 2,
        pointHoverBorderWidth: 2,
        pointRadius: 4,
      }];

    // Chart Data
    var chartData = {
      labels: data_labels,
      datasets: data_set
    };

    var config = {
      type: 'line',

      // Chart Options
      options : chartOptions,

      data : chartData
    };

    // Create the chart
    lineChart= new Chart(ctx, config);
  }
</script>
@endsection