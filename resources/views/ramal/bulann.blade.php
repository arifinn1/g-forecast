@extends('layouts.light')

@section('content')
<div class="row">
  <div class="col-xs-12">

    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Peramalan per Bulan</h4>
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
          <div class="row">
            <div class="col-xs-8">
              <progress class="progress progress-striped progress-animated with-btn" value="0" max="100"></progress>
            </div>
            <div class="col-xs-4 del-left-pad">
              <div class="btn-group btn-group-justified">
                <a href="#" class="btn btn-primary btn-sm">Proses</a>
                <a href="#" class="btn btn-secondary btn-sm">Simpan</a>
              </div>
            </div>
            <div class="col-xs-12">
                <p class="card-text"><code><?php echo count($produk); ?> Produk</code> berhasil ditarik. Klik tombol <code>proses</code> dan tunggu sampai proses peramalan selesai. </p>
            </div>
          </div>
        </div>
        <div class="table-responsive" style="height: 400px; overflow: auto;">
          <table class="table mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th></th>
                <th>Nama</th>
                <th class="text-center">Data</th>
                <th class="text-center">Generasi</th>
                <th class="text-center">Alpha</th>
                <th class="text-center">Gamma</th>
                <th class="text-center">Evaluasi</th>
              </tr>
            </thead>
            <tbody><!-- <i class="text-success icon-check | text-danger icon-close2 | text-warning icon-sun-o icon-spin | text-info icon-repeat"> -->
              <?php $i=0; ?>
              @foreach( $produk as $baris )
              <tr>
                <th scope="row"><?php echo ++$i; ?></th>
                <td></td>
                <td>{{ $baris->nm_db }}</td>
                <td class="text-center">{{ $baris->panjang }}</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection