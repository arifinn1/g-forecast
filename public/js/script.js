 function show_alert(id, pesan, alert){
    $(id).hide();
    $(id).html('<div class="alert alert-'+alert+' alert-dismissable" role="alert">'+
        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+pesan+'</div>');
    $(id).show('slow');
}

function month_by_int(index){
  index -= 1;
  var month = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep','Okt', 'Nov', 'Des'];
  return month[index];
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