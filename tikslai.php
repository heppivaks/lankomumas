<?php
session_start();
error_reporting(0);
$studentoID = $_SESSION['ID'];
$ateita=0;
$neateita=0;
$siMenAteita=0;
$buvusiMenAteita=0;
$siaSavaiteAteita=0;
$siandienAteita=0;

$dabartinisMenuo = date("m");
$buvesMenuo = date('m', strtotime("last month"));

include('includes/config.php');
if(isset($_REQUEST['del']))
	{
$delid=intval($_GET['del']);
$sql = "delete from tikslai  WHERE  id_tikslas=:delid";
$query = $dbh->prepare($sql);
$query -> bindParam(':delid',$delid, PDO::PARAM_STR);
$query -> execute();
$msg="Tikslas sėkmingai ištrintas";
}
if(strlen($_SESSION['alogin'])==0)
	{
header('location:index.php');
}
else{
	?>
<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">

	<title>Lankomumas</title>

	<!-- Font awesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- Sandstone Bootstrap CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Bootstrap Datatables -->
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<!-- Bootstrap social button library -->
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<!-- Bootstrap select -->
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<!-- Bootstrap file input -->
	<link rel="stylesheet" href="css/fileinput.min.css">
	<!-- Awesome Bootstrap checkbox -->
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<!-- Admin Stye -->
	<link rel="stylesheet" href="css/style.css">
	<style>
	/* Style tab links */
.tablink {
background-color: black;
color: green;
float: left;
border: none;
outline: none;
cursor: pointer;
padding: 14px 16px;
width: 25%;
font-weight: bold;
font-size: 20px;
}

.tablink:hover {
background-color: white;
color: black;
font-size: 20px;
font-weight: bold;
}

/* Style the tab content (and add height:100% for full page content) */
.tabcontent {
color: white;
display: none;
padding: 100px 20px;
height: 100%;
}

#Home {background-color: white;}
#News {background-color: white;}
#Contact {background-color: white;}
#About {background-color: white;}
		</style>


</head>

<body>
<?php include('includes/header.php');?>

	<div class="ts-main-content">
<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">

					<h3 class="page-title">Čia galite peržiūrėti savo tikslus: <a href="testimonials.php"><i class="fa fa-plus"></a></i></h3>
					<button class="tablink" onclick="openPage('Home', this, 'white')"  id="defaultOpen">Mėnesis</button>
					<button class="tablink" onclick="openPage('News', this, 'white')">Savaitė</button>
					<button class="tablink" onclick="openPage('Contact', this, 'white')">Diena</button>
					<button class="tablink" onclick="openPage('About', this, 'white')">Istorija</button>

					<div id="Home" class="tabcontent">
						<table id="zctb" style="color: black;" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
							<thead>
								<tr>
								<th>#</th>
									<th>Sukūrimo data</th>
									<th>Norimas kiekis</th>
									<th>Pasiektas kiekis</th>
									<th>Ar įvykdyta?</th>
									<th>Veiksmai</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
								<th>#</th>
									<th>Sukūrimo data</th>
									<th>Norimas kiekis</th>
									<th>Pasiektas kiekis</th>
									<th>Ar įvykdyta?</th>
									<th>Veiksmai</th>
								</tr>
							</tfoot>

							<?php
							$sql = "SELECT tikslas, norimaDienu, id_slapyvardis, atvykimas, laikas, fk_paskaita, sukurimo_data FROM (((tikslai INNER JOIN VARTOTOJAI ON tikslai.fk_studentas = vartotojai.id_slapyvardis)
							INNER JOIN paskaitastudentai ON id_slapyvardis = paskaitastudentai.fk_studentas)
							          INNER JOIN paskaitos ON fk_paskaita = id_paskaita) WHERE id_slapyvardis = $studentoID AND tikslas = 'Menesis' AND atvykimas > 0 AND MONTH(laikas) = MONTH(sukurimo_data)";
							$query1 = $dbh -> prepare($sql);
							$query1->execute();
							$results1=$query1->fetchAll(PDO::FETCH_OBJ);
							$ateita=$query1->rowCount();

							$sql2 = "SELECT * FROM tikslai WHERE fk_studentas = $studentoID AND tikslas = 'Menesis'";
							$query2 = $dbh -> prepare($sql2);
							$cnt=1;
							$query2->execute();
							$results2=$query2->fetchAll(PDO::FETCH_OBJ);
							if($query2->rowCount() > 0)
							{
								foreach($results2 as $result)
								{
									?>
									<tr>
										<td><?php echo htmlentities($cnt);?></td>
										<td><?php echo htmlentities($result->sukurimo_data);?></td>
										<td><?php echo htmlentities($result->norimaDienu);?></td>
										<td><?php echo $ateita ?></td>
										<?php if($result->norimaDienu <= $ateita ){?>
										<td><i class="fa fa-check"></i></a>&nbsp;&nbsp; <?php } else {?>
										<td><i class="fa fa-frown-o"></i></a>&nbsp;&nbsp;<?php }?>
										<td><a href="atsakymas.php?id=<?php echo $result->id_tikslas;?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
										<a href="tikslai.php?del=<?php echo $result->id_tikslas;?>" onclick="return confirm('Ar tikrai norite ištrinti?');"><i class="fa fa-close">
									</tr>
									<?php $cnt=$cnt+1; }} ?>
								</tbody>
							</table>
					</div>

					<div id="News" class="tabcontent">
						<table id="zctb" style="color: black;" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
							<thead>
								<tr>
								<th>#</th>
									<th>Sukūrimo data</th>
									<th>Norimas kiekis</th>
									<th>Pasiektas kiekis</th>
									<th>Ar įvykdyta?</th>
									<th>Veiksmai</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
								<th>#</th>
									<th>Sukūrimo data</th>
									<th>Norimas kiekis</th>
									<th>Pasiektas kiekis</th>
									<th>Ar įvykdyta?</th>
									<th>Veiksmai</th>
								</tr>
							</tfoot>

							<?php

							if(date('D')!='Mon')
							{
							  $savaitesPradzia = date('d',strtotime('last Monday'));

							}else{
							  $savaitesPradzia = date('d');
							}

							if(date('D')!='Sun')
							{
							    $savaitesPabaiga = date('d',strtotime('next Sunday'));
							}else{

							    $savaitesPabaiga = date('d');
							}

							$sql = "SELECT tikslas, norimaDienu, id_slapyvardis, atvykimas, laikas, fk_paskaita, sukurimo_data FROM (((tikslai INNER JOIN VARTOTOJAI ON tikslai.fk_studentas = vartotojai.id_slapyvardis)
							INNER JOIN paskaitastudentai ON id_slapyvardis = paskaitastudentai.fk_studentas)
							          INNER JOIN paskaitos ON fk_paskaita = id_paskaita) WHERE id_slapyvardis = $studentoID AND tikslas = 'Savaite' AND atvykimas > 0
												AND DAY(laikas) >= $savaitesPradzia AND DAY(laikas) <= $savaitesPabaiga AND DAY(laikas) >= DAY(sukurimo_data)
												AND DAY(laikas) <= DAY(sukurimo_data)";
							$query1 = $dbh -> prepare($sql);
							$query1->execute();
							$results1=$query1->fetchAll(PDO::FETCH_OBJ);
							$siaSavaiteAteita=$query1->rowCount();
							$cnt=1;
							$sql2 = "SELECT * FROM tikslai WHERE fk_studentas = $studentoID AND tikslas = 'Savaite'";
							$query2 = $dbh -> prepare($sql2);
							$query2->execute();
							$results2=$query2->fetchAll(PDO::FETCH_OBJ);
							if($query2->rowCount() > 0)
							{
								foreach($results2 as $result)
								{
									?>
									<tr>
										<td><?php echo htmlentities($cnt);?></td>
										<td><?php echo htmlentities($result->sukurimo_data);?></td>
										<td><?php echo htmlentities($result->norimaDienu);?></td>
										<td><?php echo $siaSavaiteAteita ?></td>
										<?php if($result->norimaDienu <= $siaSavaiteAteita ){?>
										<td><i class="fa fa-check"></i></a>&nbsp;&nbsp; <?php } else {?>
										<td><i class="fa fa-frown-o"></i></a>&nbsp;&nbsp;<?php }?>
										<td><a href="atsakymas.php?id=<?php echo $result->id_tikslas;?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
										<a href="tikslai.php?del=<?php echo $result->id_tikslas;?>" onclick="return confirm('Ar tikrai norite ištrinti?');"><i class="fa fa-close">
									</tr>
									<?php $cnt=$cnt+1; }} ?>
								</tbody>
							</table>
					</div>

					<div id="Contact" class="tabcontent">
						<table id="zctb" style="color: black;" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
							<thead>
								<tr>
								<th>#</th>
									<th>Sukūrimo data</th>
									<th>Norimas kiekis</th>
									<th>Pasiektas kiekis</th>
									<th>Ar įvykdyta?</th>
									<th>Veiksmai</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
								<th>#</th>
									<th>Sukūrimo data</th>
									<th>Norimas kiekis</th>
									<th>Pasiektas kiekis</th>
									<th>Ar įvykdyta?</th>
									<th>Veiksmai</th>
								</tr>
							</tfoot>

							<?php

							$siandien = date('d', strtotime("today"));

							$sql = "SELECT tikslas, norimaDienu, id_slapyvardis, atvykimas, laikas, fk_paskaita, sukurimo_data FROM (((tikslai INNER JOIN VARTOTOJAI ON tikslai.fk_studentas = vartotojai.id_slapyvardis)
							INNER JOIN paskaitastudentai ON id_slapyvardis = paskaitastudentai.fk_studentas)
												INNER JOIN paskaitos ON fk_paskaita = id_paskaita) WHERE id_slapyvardis = $studentoID AND tikslas = 'Diena' AND atvykimas > 0
												AND DAY(laikas) = $siandien  AND DAY(sukurimo_data) = $siandien";
							$query1 = $dbh -> prepare($sql);
							$query1->execute();
							$results1=$query1->fetchAll(PDO::FETCH_OBJ);
							$siandienAteita=$query1->rowCount();
							$cnt=1;
							$sql2 = "SELECT * FROM tikslai WHERE fk_studentas = $studentoID AND tikslas = 'Diena'";
							$query2 = $dbh -> prepare($sql2);
							$query2->execute();
							$results2=$query2->fetchAll(PDO::FETCH_OBJ);
							if($query2->rowCount() > 0)
							{
								foreach($results2 as $result)
								{
									?>
									<tr>
										<td><?php echo htmlentities($cnt);?></td>
										<td><?php echo htmlentities($result->sukurimo_data);?></td>
										<td><?php echo htmlentities($result->norimaDienu);?></td>
										<td><?php echo $siandienAteita ?></td>
										<?php if($result->norimaDienu <= $siandienAteita ){?>
										<td><i class="fa fa-check"></i></a>&nbsp;&nbsp; <?php } else {?>
										<td><i class="fa fa-frown-o"></i></a>&nbsp;&nbsp;<?php }?>
										<td><a href="atsakymas.php?id=<?php echo $result->id_tikslas;?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
										<a href="tikslai.php?del=<?php echo $result->id_tikslas;?>" onclick="return confirm('Ar tikrai norite ištrinti?');"><i class="fa fa-close">
									</tr>
									<?php $cnt=$cnt+1; }} ?>
								</tbody>
							</table>
					</div>

					<div id="About" class="tabcontent">
						<table id="zctb" style="color: black;" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
							<thead>
								<tr>
								<th>#</th>
							  	<th>Laikotarpis</th>
									<th>Sukūrimo data</th>
									<th>Norimas kiekis</th>
									<th>Pasiektas kiekis</th>
									<th>Ar įvykdyta?</th>
									<th>Veiksmai</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
								<th>#</th>
									<th>Laikotarpis</th>
									<th>Sukūrimo data</th>
									<th>Norimas kiekis</th>
									<th>Pasiektas kiekis</th>
									<th>Ar įvykdyta?</th>
									<th>Veiksmai</th>
								</tr>
							</tfoot>

							<?php
							$sql = "SELECT  *  from tikslai WHERE fk_studentas = $studentoID";
							$query = $dbh -> prepare($sql);
							$query->execute();
							$results=$query->fetchAll(PDO::FETCH_OBJ);
							$cnt=1;
							if($query->rowCount() > 0)
							{
								foreach($results as $result)
								{
									?>
									<tr>
										<td><?php echo htmlentities($cnt);?></td>
										<td><?php echo htmlentities($result->tikslas);?></td>
										<td><?php echo htmlentities($result->sukurimo_data);?></td>
										<td><?php echo htmlentities($result->norimaDienu);?></td>
										<?php if ($result->tikslas == 'Menesis'){
											?> 	<td><?php echo $ateita ?></td> <?php
										}  else if ($result->tikslas == 'Savaite'){
											?> <td><?php echo $siaSavaiteAteita ?></td> <?php }
												else if ($result->tikslas == 'Diena') {
													?> <td><?php echo $siandienAteita?></td> <?php } ?>
										<?php if($result->tikslas == 'Menesis' && $result->norimaDienu <= $siMenAteita
									|| $result->tikslas == 'Savaite' && $result->norimaDienu <= $siaSavaiteAteita
								 || $result->tikslas == 'Diena' && $result->norimaDienu <= $siandienAteita ){?>
										<td><i class="fa fa-check"></i></a>&nbsp;&nbsp; <?php } else {?>
										<td><i class="fa fa-frown-o"></i></a>&nbsp;&nbsp;<?php }?>
										<td><a href="atsakymas.php?id=<?php echo $result->id_tikslas;?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
										<a href="tikslai.php?del=<?php echo $result->id_tikslas;?>" onclick="return confirm('Ar tikrai norite ištrinti?');"><i class="fa fa-close">
									</tr>
									<?php $cnt=$cnt+1; }} ?>
								</tbody>
							</table>
					</div>

					<script>
					function openPage(pageName,elmnt,color) {
  				var i, tabcontent, tablinks;
  				tabcontent = document.getElementsByClassName("tabcontent");
  				for (i = 0; i < tabcontent.length; i++) {
    				tabcontent[i].style.display = "none";
  				}
  				tablinks = document.getElementsByClassName("tablink");
  				for (i = 0; i < tablinks.length; i++) {
    				tablinks[i].style.backgroundColor = "";
  				}
  			document.getElementById(pageName).style.display = "block";
  			elmnt.style.backgroundColor = color;
}

document.getElementById("defaultOpen").click();
</script>
			</div>
		</div>
	</div>

	<!-- Loading Scripts -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>

	<script>

	window.onload = function(){

		// Line chart from swirlData for dashReport
		var ctx = document.getElementById("dashReport").getContext("2d");
		window.myLine = new Chart(ctx).Line(swirlData, {
			responsive: true,
			scaleShowVerticalLines: false,
			scaleBeginAtZero : true,
			multiTooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
		});

		// Pie Chart from doughutData
		var doctx = document.getElementById("chart-area3").getContext("2d");
		window.myDoughnut = new Chart(doctx).Pie(doughnutData, {responsive : true});

		// Dougnut Chart from doughnutData
		var doctx = document.getElementById("chart-area4").getContext("2d");
		window.myDoughnut = new Chart(doctx).Doughnut(doughnutData, {responsive : true});

	}
	</script>
</body>
</html>
<?php } ?>
