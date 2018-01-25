function show_alert(id, pesan, alert, timeout = 0){
  $(id).hide();
  $(id).html('<div class="alert alert-'+alert+' alert-dismissable" role="alert">'+
      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+pesan+'</div>');
  $(id).show('slow');

  if(timeout>0){
    setTimeout(function(){ $(id).hide('slow'); }, timeout);
  }
}

function block_card(id){
  var block_ele = $(id).closest('.card');

  // Block Element
  block_ele.block({
      message: '<div class="icon-spinner9 icon-spin icon-lg"></div>',
      //timeout: 2000, //unblock after 2 seconds
      overlayCSS: {
          backgroundColor: '#FFF',
          cursor: 'wait',
      },
      css: {
          border: 0,
          padding: 0,
          backgroundColor: 'none'
      }
  });
}

function unblock_card(id){
  var block_ele = $(id).closest('.card');
  block_ele.unblock();
}

function show_fade(id, pesan){
  $(id).hide();
  $(id).html(pesan);
  $(id).fadeIn("slow");
}

function month_by_int(index){
  index -= 1;
  var month = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep','Okt', 'Nov', 'Des'];
  return month[index];
}

function getRangeWeek(weekNumb, yearNumb) {
  var beginningOfWeek = moment().year(yearNumb).week(weekNumb).startOf('week');
  var endOfWeek = moment().year(yearNumb).week(weekNumb).startOf('week').add(7, 'days');
  return beginningOfWeek.format('D')+' - '+endOfWeek.format('D MMM YYYY');
}

function dateonly_sql_to_js(date){
	var d = null;
	if(date != null){
		var t = (date).split(/[- :]/);
		d = new Date(t[0], t[1]-1, t[2]);
	}
	return d;
}

function timeonly_sql_to_js(date){
	var d = null;
	if(date != null){
		var t = (date).split(/[- :]/);
		d = new Date();
		d.setHours(t[0]);
		d.setMinutes(t[1]);
		d.setSeconds(t[2]);
	}
	return d;
}

function msToTime(duration) {
  var milliseconds = parseInt((duration%1000)/100)
    , seconds = parseInt((duration/1000)%60)
    , minutes = parseInt((duration/(1000*60))%60)
    , hours = parseInt((duration/(1000*60*60))%24);
  
  return (hours>0? hours+" jam ":"")+(minutes>0? minutes+" menit ":"")+(seconds>0? seconds+" detik":"");
}

function ucwords(kalimat) {
  kalimat = kalimat.toLowerCase().replace(/\b[a-z]/g, function(letter) {
    return letter.toUpperCase();
  });

  return kalimat;
}