@extends('layouts.report')

@section('content')

<div class="content-header row">
  <div class="content-header-left col-xs-12">
    <h5 style="font-size: 0.8rem; font-weight: bold;">RENCANA PENEBUSAN PELUMAS DISTRIBUTOR PERTAMINA SALES REGION JATENG DIY - FIRM ORDER</h5>
    <dl class="row" style="font-size: 0.75rem;">
      <dt class="col-xs-3">DISTRIBUTOR</dt>
      <dd class="col-xs-9">: PT GELORA PUTRA PERKASA</dd>
      <dt class="col-xs-3">PENEBUSAN BULAN</dt>
      <dd class="col-xs-9">: {{ date('F Y', strtotime($renbeli->tgl_periode)) }}</dd>
    </dl>
  </div>
</div>

<div class="row" style="font-size: 0.75rem;">
  <div class="col-xs-12">
    <table class="table table-bordered table-striped table-xs">
      <colgroup>
        <col class="col-xs-1">
        <col class="col-xs-7">
      </colgroup>
      <thead>
        <tr>
          <th class="text-right">No</th>
          <th class="text-center">KIMAP</th>
          <th>Nama Produk</th>
          <th class="text-center">Kemasan</th>
          <th class="text-right">Minggu 1</th>
          <th class="text-right">Minggu 2</th>
          <th class="text-right">Minggu 3</th>
          <th class="text-right">Minggu 4</th>
          <th class="text-right">Jumlah</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=0; ?>
        @foreach( $rendbeli as $baris )
        <tr>
          <td class="text-right"><?php echo ++$i; ?></td>
          <td class="text-center">{{ $baris->kimap }}</td>
          <td>{{ $baris->nm_db }}</td>
          <td class="text-center">{{ $baris->satuan==1 ? (strpos($baris->nm_db,'GEMUK') > -1? 'PL' : 'DR') : 'BOX' }}</td>
          <?php 
            $ming = floor($baris->jml_disetujui/4);
          ?>
          <td class="text-right">{{ $ming }}</td>
          <td class="text-right">{{ $ming }}</td>
          <td class="text-right">{{ $ming }}</td>
          <td class="text-right">{{ $ming+($baris->jml_disetujui%4) }}</td>
          <td class="text-right">{{ $baris->jml_disetujui }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<script>
  $(document).ready(function() {
    //window.print();
  });
</script>

@endsection