<?php include('auth.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="ie=egde">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Welcome Home</title>
<!-- CSS Must be in order -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/google.api.css" >
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/modal.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="index.php">Hello ID: <?php echo $_SESSION['username']; ?></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="update.php">Config</a></li>
        <li><a href="addmachine.php">Add Machine</a></li>
        <li><a href="registration.php">Register</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>


<!-- Table -->
<div class="container" style="overflow: auto;">
  <div class="panel panel-default panel-margin">
    <div class="panel-heading">IoT Monitoring</div>
    <table class="panel-body table table-bordered table-hover" id="myTable">
      <thead>
        <tr>
          <th>No.</th>
          <th>Machine</th>
          <th>Traget</th>
          <th>Plan</th>
          <th>Actual</th>
          <th>Diff</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody id="ggwp">
    
      </tbody>
    </table>

  </div>
</div>

<!-- The Modal -->
<div id="myModal" class="modal">
<div class="modal-dialog">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Modal title</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
      ...
    </div>
    <div class="modal-footer">
      ...
    </div>
  </div>
</div>
</div>

<!-- Footer -->
<footer class="footer container-fluid bg-4 text-center">
  <p><center>NHK Spring (Thailand) Co.,LTD.</center></a></p> 
</footer>

<!-- Javascript 1 -->
<script src="js/jquery.min.js"></script>
<script src="js/moment.js"></script>
<script src="js/bootstrap.min.js"></script>

<!-- Javascript 2 --> <!-- JSON Praser -->
<script>
function timeDiff(last_update) {
  var now = new moment();
  //return srt of time interval in format PT23H31M59.123S
  var time_diff = moment.duration(now.diff(moment(last_update))).toISOString();

  var idh = time_diff.indexOf("H"); // e.g. 4
  var idm = time_diff.indexOf("M"); // e.g. 7
  var idot = time_diff.indexOf("."); // e.g. 10

  // h:m:s can be numbers or '' 
  var h = time_diff.slice(2, idh<0?2:idh);
  var m = time_diff.slice(idh<0?2:idh+1, idm<0?(idh<0?2:idh+1):idm);
  var s = time_diff.slice(idm<0?(idh<0?2:idh+1):idm+1, idot);

  // modified units e.g. 2::17 => 02:00:17
  h = h.length==0?'00':(h.length==1?'0'+h:h);
  m = m.length==0?'00':(m.length==1?'0'+m:m);
  s = s.length==0?'00':(s.length==1?'0'+s:s);

  return h+':'+m+':'+s;
}

//Get machine status from json and return a <td> tag (string)
function getStat(myObj,i) {
  switch(myObj[i].st) {
    case '0':
      // black
      return '<td class="greybox">' + 'Disconnect' + '</td>';
      break;
    case '1':
      // red
      return '<td class="redbox">' + 'Down Time   ' + timeDiff(myObj[i].trn_date) + '</td>';
      break;
    case '2':
      // yellow
      return '<td class="yellowbox">' + 'Mat. Empty' + '</td>';
      break;
    case '3':
      // green
      return '<td class="greenbox">' + 'Run' + '</td>';
      break;
    default:
      console.log('There\'s an error in switchcase()');
  }
}

//$('#ggwp').empty();
function empty(elementId) {
  var selectedElement = document.getElementById(elementId)
  while(selectedElement.firstChild) selectedElement.removeChild(selectedElement.firstChild)
}

function updateTable() {
  // Full vanila AJAX for reference
  var xmlhttp =  new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      empty('ggwp')
      var myObj = JSON.parse(this.responseText);
      var i;
      for (i = 0; i < Object.keys(myObj).length; i++) {
        var innerTb =
          '<tr>'+
            '<td>'+myObj[i].no+'</td>'+
            '<td><a href="#" onclick= \'show_modal('+JSON.stringify(myObj[i])+')\'>'+myObj[i].MC_name+'</a></td>'+
            '<td>'+myObj[i].target+'</td>'+
            '<td>'+myObj[i].plan+'</td>'+
            '<td>'+myObj[i].pcs+'</td>'+
            '<td>'+myObj[i].diff+'</td>'+
            getStat(myObj,i)+
          '</tr>';

        document.getElementById('ggwp').insertAdjacentHTML('beforeend', innerTb);

      } //end for
    }
  }
  xmlhttp.open("GET", "mec_stat_parser.php", true);
  xmlhttp.send();
}; //end updateTable

updateTable();
var timer = setInterval(updateTable, 500);

</script>


<!-- The Modal -->
<script>
var modal = document.getElementById("myModal");
var span = document.getElementsByClassName("close")[0];

function show_modal(p) {
  //clearInterval(timer);
  modal.style.display = "block";
  $('#myModal .modal-title').html('<strong>'+p.MC_name+'</strong> info');
  var obj = JSON.parse(p.scheduling);
  obj = Array.isArray(obj)? obj : [obj];
  //$('#myModal .modal-body').append(p.scheduling);
  var process_time = p.ct*p.target;//min
  //findStart(obj);
  //onsole.log('expected to be an array: '+obj);
  var final_time = finalTimeCal(process_time, p.target, obj);
  //console.log(final_time.time);
  $('#myModal .modal-body').html("<strong>Status:</strong> "+final_time.status+ " <strong>Time require/finished:</strong> " + final_time.time[0] + ":" + final_time.time[1]);
}

// input must be an array []
function findStart(obj) {
  // expected to be time of each day e.g. 0.31, 12.21, 23.59
  var start_time_ref = [0,0];
  var i;
  for (i = 0; i < obj.length; i++) {
    if (obj[i].state == 'start' || obj[i].state == 'resume') {
      start_time_ref[0] = obj[i].timeHr;
      start_time_ref[1] = obj[i].timeMin;
      //console.log('start found!');
      return {'flag':1, 'idx':i, 'start_time_ref':start_time_ref, 'len':obj.length};
      break;
    }
  }
  return {'flag':0, 'idx':i, 'start_time_ref':[0,0], 'len':obj.length};
}

function finalTimeCal(pt, target, obj) {
  // obj has more than one row
  if (!Array.isArray(obj)) {
    //console.log('shit');
    return null;
  }
  var o = findStart(obj);


  var remain_pt = Number(pt); // min
  var flag = o.flag;
  var time_ref = o.start_time_ref;
  //console.log('pt: '+pt+' mins')
  // if all pass
  var i;
  for (i = o.idx; i<o.len; i++) {
    if ((obj[i].state == 'stop' && flag == 1) || (obj[i].state == 'pause' && flag == 1)) {
      //console.log('stop');
      //var on_time = [time_ref[0] - obj.[$i].timeHr, time_ref[1] - obj.[$i].timeMin];
      var on_time = hr2min([obj[i].timeHr, obj[i].timeMin]) - hr2min(time_ref);
      //console.log('timeHr: '+obj[i].timeHr);
      //console.log('timeMin: '+obj[i].timeMin);
      //console.log('time_ref: '+hr2min(time_ref)+' mins');
      //console.log('current_time: '+hr2min([obj[i].timeHr, obj[i].timeMin])+' mins');
      remain_pt = remain_pt - on_time; //min
      //console.log('remain_pt: '+remain_pt+' mins');
      if (remain_pt <= 0) {
        //console.log('in if');
        return {'status':'completed','time':normalize(time_ref, on_time + remain_pt)};
      }
      // New time_ref (current time)
      time_ref = normalize(time_ref, on_time);
      flag = 0;
    } else if ((obj[i].state == 'start' && flag == 0) || (obj[i].state == 'resume' && flag == 0)) {
      //console.log('start');
      var off_time = time_ref - obj[i].timeMH;
      var off_time = hr2min([obj[i].timeHr, obj[i].timeMin]) - hr2min(time_ref);
      time_ref = normalize(time_ref, off_time);
      flag = 1;
    }
  }
  return {'status':'incomplete', 'time':min2time(remain_pt)};
}

function normalize(time_ref, min) {
  var time_t = hr2min(time_ref) + Number(min);
  return min2time(time_t);
}

function hr2min(time) {
  return time[0]*60 + Number(time[1]);
}

function min2time(time) {
  return [Math.floor(time/60), time%60];
}

span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

</script>

<!-- Resize the table -->

</body>
</html>