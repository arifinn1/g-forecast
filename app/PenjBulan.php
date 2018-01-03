<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PenjBulan extends Model
{
  protected $table = 'f_penj_bulan';
  protected $primaryKey = 'kd_f';
  public $timestamps = false;

  public function ambil_penjualan($kd_prod)
  {
    $data = DB::table("f_penj_bulan")
      ->whereRaw("kd_prod=$kd_prod")
      ->orderBy('tahun', 'ASC')
      ->orderBy('bulan', 'ASC')
      ->get();
    
    return $data;
  }

  public function resolve_missing_val($data)
  {
    $bln_awal = $data[0]['bulan'];
    $bln_akhir = 12;
    $cur_idx = 0;
    $add_idx = 0;
    $insert = [];

    $avg = 0;
    for($i=0; $i<count($data); $i++){ $avg += $data[$i]['jumlah']; }
    $avg = $avg / count($data);

    for($i=$data[0]['tahun']; $i<=$data[count($data)-1]['tahun']; $i++){
      if($i == $data[count($data)-1]['tahun']){ $bln_akhir = $data[count($data)-1]['bulan']; }

      for($j=$bln_awal; $j<=$bln_akhir; $j++){
        if($j == $data[$cur_idx]['bulan'] && $i == $data[$cur_idx]['tahun']){
          $cur_idx++;
        }else{
          $insert[] = array( $cur_idx+$add_idx, array("kd_f"=>-1, "tahun"=>$i, "bulan"=>$j, "kd_prod"=>$data[0]['kd_prod'], "jumlah"=>$avg) );
          $add_idx++;
        }
      }
      $bln_awal = 1;
    }

    for($i=0; $i<count($insert); $i++){
      array_splice($data, $insert[$i][0], 0, $insert[$i][1]);
    }

    return $data;
  }

  public function operasi_genetika($data, $min)
  {
    $data_penju = [];
    for($i=0; $i<count($data); $i++){
      array_push($data_penju, $data[$i]['jumlah']);
    }

    $pop_size = 10;
    $maxgen = 1000;
    $pm = 0.1;
    $pc = 0.3;

    $P = [];
    $Eval = [];
    $Offs = [];
    $Err = [];
    $temp_p = [];
    $Pcross = [];
    $Pmut = [];

    for($i=0; $i<$maxgen; $i++){
      $Eval[$i] = [];
      $Pcross[$i] = [];
      $Pmut[$i] = [];

      if($i==0){
        for($j=0; $j<$pop_size; $j++){
          $P[$i][] = array($this->rand_float(), $this->rand_float());
        }
      }

      for($j=0; $j<$pop_size; $j++){
        $Eval[$i][] = $this->peramal($data_penju, $P[$i][$j]);
        $Eval[$i][$j]['index'] = $j;
      }

      $temp1;
      $temp2;
      $temp3;
      $temp_rand = $this->randomize_c($pop_size, $pc);
      for($k=0; $k<count($temp_rand); $k++){
        $temp1=[0,0];
        $temp2=[0,0];
        $temp3 = $this->overcross($P[$i][$temp_rand[$k][0]][0], $P[$i][$temp_rand[$k][1]][0]);
        $temp1[0] = $temp3[2];
        $temp2[0] = $temp3[3];
        $temp3 = $this->overcross($P[$i][$temp_rand[$k][0]][1], $P[$i][$temp_rand[$k][1]][1]);
        $temp1[1] = $temp3[2];
        $temp2[1] = $temp3[3];
        $Pcross[$i][] = $this->peramal($data_penju, $temp1);
        $Pcross[$i][] = $this->peramal($data_penju, $temp2);
      }

      $temp_rand = $this->randomize_m($pop_size, $pm);
      for($l=0; $l<count($temp_rand); $l++){
        $temp1 = array($P[$i][$temp_rand[$l][0]][0], $P[$i][$temp_rand[$l][0]][1]);
        $temp1[$temp_rand[$l][1]] = $this->mutation($temp1[$temp_rand[$l][1]]);
        $Pmut[$i][] = $this->peramal($data_penju, $temp1);
      }

      $Offs[$i] = $this->selection($pop_size, $Eval[$i], $Pcross[$i], $Pmut[$i]);
      
      $temp_p = [];
      $Err[$i] = 0;
      for($j=0; $j<$pop_size; $j++){
        $temp_p[] = array($Offs[$i][$j]['alpha'], $Offs[$i][$j]['gamma']);
        $Err[$i] += $Offs[$i][$j]['mapee'];
      }
      $Err[$i] = $Err[$i] / $pop_size;
      $P[$i+1] = $temp_p;

      if($this->cek_alp_gam($Offs[$i], 0.00001, 0.99999)){
        $maxgen = $i + 1;
        break;
      }
    }
    
    /*echo "<pre>";
    echo "<br><br>Eval : "; print_r($Eval);
    echo "<br><br>Pcross : "; print_r($Pcross);
    echo "<br><br>Pmut : "; print_r($Pmut);
    echo "<br><br>Offs : "; print_r($Offs);
    echo "<br><br>Offs : "; print_r($Offs[$maxgen-1]);
    print_r($Err);
    echo "</pre>";*/

    $hasil_temp = $Offs[$maxgen-1];
    usort($hasil_temp, function($a, $b) { return $a['mapee'] <=> $b['mapee']; });
    $hasil1 = $this->peramal($data_penju, array( $hasil_temp[0]['alpha'], $hasil_temp[0]['gamma'] ), true);
    $hasil2 = $this->peramal($data_penju, array( $hasil_temp[0]['alpha'], $hasil_temp[0]['gamma'] ));

    if($min){
      return json_encode($hasil1['ftm'])."||".json_encode($this->error_min($Err))."||".$maxgen."||".($hasil_temp[0]['alpha'])."||".($hasil_temp[0]['gamma'])."||".($hasil2['mapee']);
    }else{
      return json_encode($hasil1['ftm'])."||".json_encode($Err)."||".json_encode($Offs[0])."||".json_encode($Offs[$maxgen-1]);
    }
  }

  function error_min($error){
    $label = [];
    $data = [];
    $len_err = count($error);
    $max_err = ($len_err > 20 ? 20 : $len_err);
    $incre = $len_err/20;
    $curr_gen = -1;
    for($i=0; $i<$max_err; $i++){
      if($len_err > 20){
        $curr_gen = floor($i*$incre) + 1;
        if($i==0){
          $data[$i] = $error[0];
          $label[$i] = 1;
        }else{
          $data[$i] = $error[$curr_gen];
          $label[$i] = $curr_gen;
        }
        
        if($len_err==1000 && $i==$max_err-1){
          $data[20] = $error[999];
          $label[20] = 1000;
        }
      }else{ 
        $data[$i] = $error[$i];
        $label[$i] = $i+1;
      }
    }

    return array($data, $label);
  }

  function cek_alp_gam($data, $min, $max){
    usort($data, function($a, $b) { return $a['alpha'] <=> $b['alpha']; });
    $max_alpha = $data[count($data)-1]['alpha'];
    $min_alpha = $data[0]['alpha'];
    usort($data, function($a, $b) { return $a['gamma'] <=> $b['gamma']; });
    $max_gamma = $data[count($data)-1]['gamma'];
    $min_gamma = $data[0]['gamma'];

    if($max_alpha > $max || $max_gamma > $max || $min_alpha < $min || $min_gamma < $min){
      return true;
    }else{ return false; }
  }

  function peramal($data, $alp_gam, $show_ftm = false){
    $st = [];
    $dt = [];
    $ftm = [];
    $mse = 0;
    $mape = 0;

    for($i=0; $i<count($data); $i++){
      if($i==0){
        $st[$i] = null;
        $dt[$i] = null;
        $ftm[$i] = null;
      }else if($i<=1){
        $st[$i] = $data[0];
        $dt[$i] = $data[1]-$data[0];
        $ftm[$i] = $data[0];
      }else{
        $st[$i] = ($alp_gam[0] * $data[$i])+((1-$alp_gam[0]) * ($st[$i-1]+$dt[$i-1]));
        $dt[$i] = ($alp_gam[1] * ($st[$i]-$st[$i-1]))+((1-$alp_gam[1]) * $dt[$i-1]);
        $ftm[$i] = $st[$i-1] + $dt[$i-1];
        if(($i+1) == count($data)){
          $ftm[$i+1] = $st[$i] + $dt[$i];
        }
      }

      $mse += $i>0 ? pow($data[$i] - $ftm[$i], 2) : 0;
      $mape += $i>0 ? ($data[$i] - $ftm[$i]) / $data[$i] : 0;
    }

    $mse = $mse / (count($data) - 1);
    $mape = ($mape / (count($data) - 1)) * 100;
    if($show_ftm){ return array( 'ftm'=> $ftm, 'mse'=> $mse, 'mape'=> $mape );
    }else{ return array( 'alpha'=> $alp_gam[0], 'gamma'=> $alp_gam[1], 'mse'=> $mse, 'mape'=> $mape, 'mapee'=> abs($mape) ); }
  }

  function randomize_c($pop_size, $pc){
    $ret = [];
    $temp1 = [];
    $temp2 = [];
    $count = 0;
    $idx = -1;
    for($i=0; $i<$pop_size; $i++){
      array_push($temp1, $this->rand_float());
      if($temp1[$i]<=$pc){
        $count++;
        if($count%2==1){
          $temp2 = [];
          array_push($temp2, $i);
        }else{
          array_push($temp2, $i);
          array_push($ret, $temp2);
        }
      }

      if($i==9 && $count%2==1 && $temp1[$i]>$pc){
        $temp2[] = $i;
        array_push($ret, $temp2);
        break;
      }
    }

    return $ret;
  }

  function randomize_m($pop_size, $pm){
    $ret=[];
    $rand1;
    $rand2;
    for($i=0; $i<$pop_size; $i++){
      $rand1 = $this->rand_float();
      $rand2 = $this->rand_float();
      if($rand1<=$pm || $rand2<=$pm){
        $ret[] = array($i, $rand1<=$pm ? 0 : 1);
      }
    }

    if(count($ret) == 0){
      $ret = $this->randomize_m($pop_size, $pm);
    }

    return $ret;
  }

  function overcross($gen1, $gen2){
    $random = $this->rand_float();
    $r1 = $random/$gen2;
    $r2 = $random/$gen1;
    $gen1_;
    $gen2_;
    if(($gen2<0 && $gen1<$gen2) || ($gen2>=0 && $gen1>$gen2)){
      $gen1_ = $gen1-($r1*$gen2);
      $gen2_ = $gen2+($r2*$gen1);
    }else{ 
      $gen1_ = $gen1+($r1*$gen2);
      $gen2_ = $gen2-($r2*$gen1);
    }

    if($gen1_>0 && $gen1_<1 && $gen2_>0 && $gen2_<1){
      return array($gen1, $gen2, $gen1_, $gen2_);
    }else{
      return $this->overcross($gen1, $gen2);
    }
  }

  function mutation($gen){
    $ret = $gen + (($this->rand_float() * 2 - 1) * 1);
    if($ret<0 || $ret>1){
      $ret = $this->mutation($gen);
    }
    return $ret;
  }

  function selection($pop_size, $_eval, $_cross, $_mut){
    $_cm = array_merge($_cross, $_mut);
    $_sel = [];
    usort($_eval, function($a, $b) { return $a['mapee'] <=> $b['mapee']; });
    usort($_cm, function($a, $b) { return $a['mapee'] <=> $b['mapee']; });

    for($i=0; $i<count($_cm); $i++){
      if($_cm[$i]['mapee'] < $_eval[$pop_size-1]['mapee']){ $_sel[] = $_cm[$i]; }
    }

    $_e = $pop_size-count($_sel);
    $_s = 0;
    while($_e < $pop_size){
      if($_eval[$_e]['mapee'] > $_sel[$_s]['mapee']){
        $_sel[$_s]['index'] = $_eval[$_e]['index'];
        $_eval[$_e] = $_sel[$_s];
        $_eval[$_e]['offs'] = 1;
        $_s++;
      }
      $_e++;
    }
    
    usort($_eval, function($a, $b) { return $a['index'] <=> $b['index']; });
    return $_eval;
  }

  function rand_float($st_num=0,$end_num=1,$mul=100000000)
  {
    if ($st_num>$end_num) return false;
    return mt_rand($st_num*$mul,$end_num*$mul)/$mul;
  }
}
