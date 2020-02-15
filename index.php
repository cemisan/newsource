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

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
      <h2>Machine Info</h2>
    </div>
    <div class="modal-body">
      <p>Some extra infomation here</p>
    </div>
    <div class="modal-footer">
      <h3>Footer</h3>
    </div>
  </div>

</div>

<div class="container">
  <!-- Modal -->
  <div class="modal fade" id="myModall" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>This is a large modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
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
  //AJAX
  var xmlhttp =  new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      empty('ggwp')
      var myObj = JSON.parse(this.responseText);
      var i;
      for (i = 0; i < Object.keys(myObj).length; i++) {
        var innerTb = '<tr>'+
                       '<td>'+myObj[i].no+'</td>'+
                       '<td><a href="#" onclick= \'show_modal('+JSON.stringify(myObj[i])+')\'>'+myObj[i].MC_name+'</a></td>'+
                       '<td>'+myObj[i].target+'</td>'+
                       '<td>'+myObj[i].plan+'</td>'+
                       '<td>'+myObj[i].pcs+'</td>'+
                       '<td>'+myObj[i].diff+'</td>'+
                       getStat(myObj,i)+
                     '</tr>';
        //console.log(getStat(myObj,i));

        //$('#myTable > tbody:last').append(innerTb);
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
  modal.style.display = "block";
  $('#myModal .modal-content .modal-header h2').empty();
  $('#myModal .modal-content .modal-header h2').append(p.machinename+' info');
  clearInterval(timer);
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