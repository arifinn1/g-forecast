
<script src="{{ asset('robust-assets/js/core/libraries/jquery.min.js') }}" type="text/javascript"></script>
<div class="row"><div class="col-sm-12"><span id="progres-div"></span></div></div>
<a name="" id="" class="btn btn-primary" href="#" onclick="loop()" role="button">Proses</a>

<script>
  var timer;
  
  function makeRequest(toPHP, callback) {
    var xmlhttp;

    if (window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
    } else {
      // code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState==4 && xmlhttp.status==200) {
        callback(xmlhttp.response);
      }
    }

    xmlhttp.open("GET",toPHP,true);
    xmlhttp.send();
  }

  function loop() {
    makeRequest("/ramal/bulan/test_proses", function(response) {
      console.log('done');
      clearInterval(timer);
    });

    timer = setInterval(makeRequest("/ramal/bulan/test_progress", function(response) {
      console.log(response);
    }),1000);
  }

  function proses() {
    //test_proses();
    console.log('mulai-');

    jQuery.ajax({
      type: "GET",
      url: "/ramal/bulan/test_proses",
      success: function(res)
      {
        console.log(res);
        console.log('-done');
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        test = false;
      }
    });
  }

  var test = true, progres = 0;

  function test_proses() {
    if(test){
      if(progres<5){
        console.log(progres);
        $.getJSON('/ramal/bulan/test_progress', function(result){
          progres = result;
          console.log(progres);
          test_proses();
        });
      }
    }
  }
</script>