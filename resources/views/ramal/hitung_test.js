function TestOvercross(gen1, gen2)
{
  console.log("gen1:"+gen1+" + gen2:"+gen2+" = "+(gen1+gen2));
  console.log("genf/2:"+((gen1+gen2)/2));
  var random = 0.0;
  while(true) {
    random = Math.random();
    if(random>0 && random<1){ break; }
  }
  console.log("random:"+random);
  var r1 = random/gen2, r2 = random/gen1;
  console.log("r1:"+r1+", r2:"+r2);
  CheckAlpha(gen1, gen2, r1, r2);
}

function CheckAlpha(gen1, gen2, r1, r2)
{
  var jumlah = gen1 + gen2, gen1_, gen2_, check, check_, check1, check2;
  if((gen2<0 && gen1<gen2) || (gen2>=0 && gen1>gen2)){
    gen1_ = gen1-(r1*gen2);
    gen2_ = gen2+(r2*gen1);
  }else{ 
    gen1_ = gen1+(r1*gen2);
    gen2_ = gen2-(r2*gen1);
  }

  console.log("gen1_:"+gen1_+" + gen2_:"+gen2_+" = "+(gen1_+gen2_));
  console.log("genf_/2:"+((gen1_+gen2_)/2));

  check = jumlah - (gen1_+gen2_);
  check = check>-0.00001 && check<0.00002;
  check1 = Math.abs(gen1 - gen1_);
  check2 = Math.abs(gen2 - gen2_);
  check_ = check1 - check2;
  check_ = check_>=-0.00001 && check_<=0.00001;

  console.log("check1:"+check1+" - check2:"+check2+" = "+(check1-check2));
  console.log("plus:"+(((gen1+gen2)/2)+((gen1_-gen2_)/2))+" - min:"+(((gen1+gen2)/2)-((gen1_-gen2_)/2)));
  console.log("---------------------");

  var j1 = ((gen1+gen2)/2) + ((gen1_-gen2_)/2);
  var j2 = ((gen1+gen2)/2) - ((gen1_-gen2_)/2);
  /*if(alpha>=0.00001 && check && check_ && j1==gen1_ && j2==gen2_)
  {
    console.log("ketemu");
  }else if(!check){
    console.log("!check E-"+(gen1_+gen2_));
  }

  console.log(gen1+"-"+gen2+"-"+gen1_+"-"+gen2_+"-ketemu-"+(gen1_+gen2_)+"-"+(check1 - check2));
  */
}




        /*data_penj = JSON.parse(res);
        data_ramal = [];
        OperasiGenetika();*/

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