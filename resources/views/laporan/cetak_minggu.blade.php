@extends('layouts.report')

@section('content')

<div class="content-header row">
  <div class="content-header-left col-xs-6 mb-1">
    <!--<h2 class="content-header-title">{{ $dramal[0]->nm_db }}</h2>-->
    <img alt="company logo" style="height: 40px;" src="{{ asset('logo-ico/app-logo2-big.png') }}" class="">
  </div>
  <div class="content-header-right breadcrumbs-right breadcrumbs-top col-xs-6">
    <div class="text-right col-xs-12">
      <ul class="px-0 list-unstyled">
        <h3 class="text-bold-800">PERAMALAN MINGGUAN</h3>
        <li style="margin-top: -10px;">Dibuat &nbsp; : &nbsp; {{ date("D, j M Y", strtotime($dramal[0]->dibuat)) }}</li>
      </ul>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <table class="table table-bordered table-striped table-sm">
      <colgroup>
        <col class="col-xs-1">
        <col class="col-xs-7">
      </colgroup>
      <thead>
        <tr>
          <th>#</th>
          <th>Produk</th>
          <th>MAPE</th>
          <?php $r_col = [];
            if(count($dramal)>0){
            $fdata = json_decode($dramal[0]->actual);
            $rleng = count($fdata[1]) - count($fdata[0]);
            
            for($i=count($fdata[1])-$rleng-1; $i<count($fdata[1]); $i++){
              echo '<th>'.$fdata[1][$i].'</th>';
              array_push($r_col, array('data'=>str_replace(' ', '_', $fdata[1][$i])));
            }
          } ?>
        </tr>
      </thead>
      <tbody>
        <?php $i=0; ?>
        @foreach( $dramal as $baris )
        <tr>
          <td><?php echo ++$i; ?></td>
          <td>{{ $baris->nm_db }}</td>
          <td>{{ round($baris->mape, 8) }}</td>
          <?php
            $acdata = json_decode($baris->actual);
            $rmdata = json_decode($baris->ramalan);
            $ftdata = json_decode($baris->fitness);
            echo '<td>'.round($acdata[0][count($acdata[0])-1], 2).'</td>';
            
            for($j=count($rmdata)-$rleng; $j<count($rmdata); $j++){
              echo '<td>'.round($rmdata[$j], 2).'</td>';
            }

            $acdata = str_replace('"', "'", $baris->actual);
          ?>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<script>
  $(document).ready(function() {
    window.print();
  });
</script>

@endsection