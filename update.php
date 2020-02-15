<?php
	include('auth.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update</title>
<!-- CSS Must be in order -->
<link rel="stylesheet" href="css/bootstrap-clockpicker.min.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/google.api.css">
<link rel="stylesheet" href="css/style.css">
<style type="text/css">
select {
  font-family: "Courier New";
}
.hpg span {
  background-color: #dff0d8;
}
.hpr span {
  background-color: #f2dede;
  
}
.hpy span {
  background-color: #fcf8e3;
}
#breaktime_panel_container select option {
  background-color: #eee;
}
</style>

</head>
<body>
<!-- top bar -->
<div class = "alert alert-success alert-dismissible fade in" id="success-alert"><strong>Saved!</strong></div>
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


	<?php
		require('iot.php');

		if ($result = mysqli_query($con, "ALTER TABLE main_table MODIFY ct CHAR(6)")) {
            //echo "Successfully Converting column 'ct' from float to char";
    }

    //Autosave
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'update') {
          $id = stripslashes($_GET['id']);
          $id = mysqli_real_escape_string($con, $id);
          $item = stripslashes($_GET['item']);
          $item = mysqli_real_escape_string($con, $item);
          $val = stripslashes($_GET['val']);
          $val = mysqli_real_escape_string($con, $val);

          $query = "UPDATE main_table SET $item = '$val' WHERE id = '$id'";
          echo $query;
          $result = mysqli_query($con, $query);
          if ($result) {
              echo 'success';
          } else {
            echo 404;
          }
        }
    }

    if (isset($_POST['break_time'])) {
        //print_r($_POST['break_time']['state']) ;
        //print_r($_POST['break_time']['timeHr']) ;
        //print_r($_POST['break_time']['timeMin']) ;
      $a = array();
      if (is_array($_POST['break_time']['state'])) {
        for ($i = 0; $i < count($_POST['break_time']['state']); $i++) {
          $a[$i] = array("timeHr" => $_POST['break_time']['timeHr'][$i],
                        "timeMin" => $_POST['break_time']['timeMin'][$i],
                        "state"   => $_POST['break_time']['state'][$i]);
        }
      } else {
        $a = array("timeHr" => $_POST['break_time']['timeHr'],
                  "timeMin" => $_POST['break_time']['timeMin'],
                  "state"   => $_POST['break_time']['state']);
      }

      //echo json_encode($a);

      if ($result = mysqli_query($con, "ALTER TABLE main_table ADD scheduling JSON")) {
          echo 'Scheduling field has been added';
      }

      $id = $_POST['id'];
      $val = json_encode($a, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
      $query = "UPDATE main_table SET scheduling = '$val' WHERE id = '$id'";

      if ($result = mysqli_query($con, $query)) {
        echo 'saved!';
      }
    }
		
		if(isset($_REQUEST['MC_name'])){
			$MC_name = stripslashes($_REQUEST['MC_name']);
			$MC_name = mysqli_real_escape_string($con, $MC_name);
			$sec = stripslashes($_REQUEST['sec']);
			$sec = mysqli_real_escape_string($con, $sec);
			$msec = stripslashes($_REQUEST['msec']);
			$msec = mysqli_real_escape_string($con, $msec);
			$target = stripslashes($_REQUEST['target']);
			$target = mysqli_real_escape_string($con, $target);
			$CT = "$sec.$msec";
			
			$query = "UPDATE main_table SET target = '$target', ct = '$CT' WHERE MC_name = '$MC_name'";
			$result = mysqli_query($con, $query);
			
			if($result){
				echo "<div class='container'>
				 <h3>Successfully</h3>
				 <br>Click here to <a href='index.php'>Home</a>
				</div>";
        echo $_POST['test'];
			} else {
				echo "<div class='container'>
					 <h3>Error</h3>
					 <br>Click here to <a href='index.php'>Home</a>
					</div>";
				}
		} else {
	?>

<!-- Main content -->
<div class="container">
  <form name="update" method="post">
  <div class="panel panel-default panel-margin">
    <div class="panel-heading"><a href="index.php">Home</a>&gt;Configuration</div>
    <div class="panel-body">

      
          <!-- Name -->
          <div class="input-group">
            <span class="input-group-addon">Machine&nbsp;<i class="glyphicon glyphicon-tags"></i></span>
          	<select class="form-control" id="MC_name" name="MC_name">
          	</select>
          </div>
          <br>
           <!-- CT field -->
           <div class="input-group">
             <span class="input-group-addon">CT&nbsp;<i class="glyphicon glyphicon-time"></i></span>
             <select class="form-control" id="ct_sec" name="sec" onchange="autosave(this)">
               <option value="00">00</option>
               <option value="01">01</option>
               <option value="02">02</option>
               <option value="03">03</option>
               <option value="04">04</option>
               <option value="05">05</option>
               <option value="06">06</option>
               <option value="07">07</option>
               <option value="08">08</option>
               <option value="09">09</option>
<?php for ($i = 10 ; $i < 100 ; $i++){echo "               <option value=".$i.">".$i."</option>\r\n";}?>
             </select>
             <span class="input-group-addon inbetween">.</span>
             <select class="form-control" id="ct_msec" name="msec" onchange="autosave(this)">
               <option value="00">00</option>
               <option value="01">01</option>
               <option value="02">02</option>
               <option value="03">03</option>
               <option value="04">04</option>
               <option value="05">05</option>
               <option value="06">06</option>
               <option value="07">07</option>
               <option value="08">08</option>
               <option value="09">09</option>
<?php
for ($i = 10 ; $i < 100 ; $i++){echo "               <option value=".$i.">".$i."</option>\r\n";}?>
             </select>
             <span class="input-group-addon">sec</span>
           </div>
          <br>
          <!-- Target field -->
          <div class="input-group">
            <span class="input-group-addon">Target</span>
            <input type="text" class="form-control" id="target" name="target" placeholder="Target" onchange="autosave(this)" require>
          </div>
          <br>
    </div> <!-- panel body -->

    <!-- panel heading2 -->
    <div class="panel-heading"><a href="#" onclick="addBreak()" class="glyphicon glyphicon-plus" id="add_interval"></a> Break time itervals in each day</div>
    <div class="panel-body" id ="breaktime_panel_container">
        <div class="input-group hpy">
          <span class="input-group-addon">@</span>
          <select class="form-control form-control-sm" name="timeHr" onchange="autosave2()">
            <option value="0">00</option>
            <option value="1">01</option>
            <option value="2">02</option>
            <option value="3">03</option>
            <option value="4">04</option>
            <option value="5">05</option>
            <option value="6">06</option>
            <option value="7">07</option>
            <option value="8">08</option>
            <option value="9">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
          </select>
          <span class="input-group-addon inbetween">:</span>
          <select class="form-control form-control-sm" name="timeMin" onchange="autosave2()">
            <option value="0">00</option>
            <option value="1">01</option>
            <option value="2">02</option>
            <option value="3">03</option>
            <option value="4">04</option>
            <option value="5">05</option>
            <option value="6">06</option>
            <option value="7">07</option>
            <option value="8">08</option>
            <option value="9">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
            <option value="32">32</option>
            <option value="33">33</option>
            <option value="34">34</option>
            <option value="35">35</option>
            <option value="36">36</option>
            <option value="37">37</option>
            <option value="38">38</option>
            <option value="39">39</option>
            <option value="40">40</option>
            <option value="41">41</option>
            <option value="42">42</option>
            <option value="43">43</option>
            <option value="44">44</option>
            <option value="45">45</option>
            <option value="46">46</option>
            <option value="47">47</option>
            <option value="48">48</option>
            <option value="49">49</option>
            <option value="50">50</option>
            <option value="51">51</option>
            <option value="52">52</option>
            <option value="53">53</option>
            <option value="54">54</option>
            <option value="55">55</option>
            <option value="56">56</option>
            <option value="57">57</option>
            <option value="58">58</option>
            <option value="59">59</option>

          </select>
          <span class="input-group-addon inbetween"><span class="glyphicon glyphicon-time"></span></span>
          <select class="form-control form-control-sm state" name="state" onchange="recolor($(this))">
            <option value ="pause">PAUSE</option>
            <option value ="resume">RESUME</option>
            <option value ="start">START</option>
            <option value ="stop">STOP</option>
          </select>
          <span class="input-group-addon"><a href="#" class="glyphicon glyphicon-minus" onclick="removeBreak($(this))"></a></span>
        </div>
    </div>
    
  </div> <!-- main panel -->
  </form>
</div> <!-- container -->

<?php
	
}?>

<!-- Footer -->
<footer class="footer container-fluid bg-4 text-center">
  <p><center>NHK Spring (Thailand) Co.,LTD.</center></a></p> 
</footer>

<!-- Javascript 1 -->
<script type="text/javascript" src="js/moment.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-clockpicker.min.js"></script>

<script type="text/javascript">

var divtmp = '';

$(document).ready(function(){
  divtmp = $('#breaktime_panel_container').html();
  $('#breaktime_panel_container').children().remove();
});

function recolor(el) { //this = select

  switch(el.val()) {
    case 'pause':
      el.parent().removeClass('hpy hpr hpg');
      el.parent().addClass('hpy');
      break;
    case 'resume':
      el.parent().removeClass('hpy hpr hpg');
      el.parent().addClass('hpg');
      break;
    case 'start':
      el.parent().removeClass('hpy hpr hpg');
      el.parent().addClass('hpg');    break;
    case 'stop':
      el.parent().removeClass('hpy hpr hpg');
      el.parent().addClass('hpr');   break;
    default:
      el.parent().removeClass('hpy hpr hpg');
      el.parent().addClass('hpy');
    //console.log('focused');
  }
  // save changes
  autosave2();
}

function addBreak() {
  //var el_tmp = $("#breaktime_panel_container div:last-child").clone(true);
  //console.log(el_tmp.html());
  //switch(el.find('select:last-child').val()) {
  //  case 'pause':
  //    el.find('select:last-child option:selected').removeAttr("selected");
  //    el.find('select:last-child option[value="resume"]').attr('selected', 'selected');
  //    el.find('select:last-child').parent().removeClass('hpy hpr hpg').addClass('hpg');
  //    break;
  //  case 'resume':
  //    el.find('select:last-child option:selected').removeAttr("selected");
  //    el.find('select:last-child option[value="pause"]').attr('selected', 'selected');
  //    el.find('select:last-child').parent().removeClass('hpy hpr hpg').addClass('hpy');
  //
  //    break;
  //  case 'start':
  //    el.find('select:last-child option:selected').removeAttr("selected");
  //    el.find('select:last-child option[value="stop"]').attr('selected', 'selected');
  //    el.find('select:last-child').parent().removeClass('hpy hpr hpg').addClass('hpr');
  //    break;
  //  case 'stop':
  //    el.find('select:last-child option:selected').removeAttr("selected");
  //    el.find('select:last-child option[value="start"]').attr('selected', 'selected');
  //    el.find('select:last-child').parent().removeClass('hpy hpr hpg').addClass('hpg')
  //    break;
  //  default:
  //    console.log('default')
  //    el.find('select:last-child option:selected').removeAttr("selected");
  //    el.find('select:last-child option[value="pause"]').attr('selected', 'selected');
  //    el.find('select:last-child').parent().removeClass('hpy hpr hpg').addClass('hpy');
  //
  //}
  
  $('#breaktime_panel_container').append(divtmp);

  // save changes
  //console.log(divtmp.html());
  autosave2();
}

function removeBreak(el) {
  el.parent().parent().remove();
  autosave2();
}

function autosave2(){
  var x = $("form").serializeObject();
  //console.log(x);
  $.ajax({
    type: "POST",
    url: "update.php",
    data: { 'break_time':x, 'id':$("#MC_name").val() },
    //contentType: "application/json; charset=utf-8",
    dataType: "json",
    success: function(res){console.log(res);},
    failure: function(errMsg) {
      alert(errMsg);
     }
  });
  showAlert();
}

$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
}

// Load page ajax
$(document).ready(function(){
  $.get("mec_stat_parser.php", function(data, status){
    if (status == 'success') {
      //alert("\nStatus: " + status);
      JSON.parse(data).forEach(load_machine_name);
      load_info();
    } else {
      alert("\nStatus: " + status);
    }
  });

  $("#MC_name").change(load_info);    
});

function load_machine_name(item, index){
  var opt = document.createElement("option");
  opt.innerHTML = item.MC_name;
  opt.value = item.id;
  $("#MC_name").append(opt);
}

function load_info() {
  var id = $("#MC_name").val();
  //console.log(id)
  $.get("mec_stat_parser.php?id="+id, function(data, status){
    if (status == 'success') {
      //alert("\nStatus: " + data);
      JSON.parse(data).forEach(function(item, index){
        $('#ct_sec').val(item.ct.split('.')[0]);
        $('#ct_msec').val(item.ct.split('.')[1]);
        $('#target').val(item.target);
        var sch = JSON.parse(item.scheduling);
        if (sch == null || sch == '' || item.scheduling == '{"timeHr":null,"timeMin":null,"state":null}') {
          $('#breaktime_panel_container').children().remove();
        } else if (Array.isArray(sch)) {
          //console.log(sch);
          $('#breaktime_panel_container').children().remove();
          sch.forEach(function(it,idx){

            var div = $(divtmp);
            div.find('select[name=timeHr]').val(it.timeHr);
            div.find('select[name=timeMin]').val(it.timeMin);
            div.find('select[name=state]').val(it.state);
            div.removeClass('hpy hpr hpg').addClass(setClass(it.state));
            $('#breaktime_panel_container').append(div);
          });
        } else {
            $('#breaktime_panel_container').append(divtmp);
        }
      });
    } else {
      alert("\nStatus: " + status);
    }
  });
}

// used in load_info()
function setClass(state) {
  switch (state) {
    case 'pause':
      return 'hpy';
      break;
    case 'resume':
      return 'hpg';
      break;
    case 'start':
      return 'hpg';
      break;
    case 'stop':
      return 'hpr';
      break;
    default:
      return 'hpr';
  }
}

function autosave(el){
  var id = $("#MC_name").val();
  if(el.id == 'ct_msec' || el.id == 'ct_sec'){
    var ct = $('#ct_sec').val()+'.'+$('#ct_msec').val();
    $.get("update.php?action=update&id="+id+"&item=ct&val="+ct,function(data, status){
      if (status == 'success'){
        showAlert();
        console.log("saved!");
      } else {
        alert("\nStatus: " + status);
      }
    });

  } else {
    $.get("update.php?action=update&id="+id+"&item="+el.id+"&val="+el.value,function(data, status){
      if (status == 'success'){
        showAlert();
        console.log("saved!");
      } else {
        alert("\nStatus: " + status);
      }
    });
  }
}

function showAlert() {
  $("#success-alert").fadeTo(500, 100).slideUp(100, function() {
    $("#success-alert").slideUp(100);
  });
}

</script>

</body>
</html>

