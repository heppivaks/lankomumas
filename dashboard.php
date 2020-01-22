<?php
session_start();
error_reporting(0);
$ateita=0;
$neateita=0;
$siMenAteita=0;
$buvusiMenAteita=0;
$studentoID = $_SESSION['ID'];
$dabartinisMenuo = date("m");
$buvesMenuo = date('m', strtotime("last month"));
include('includes/config.php');
if(isset($_REQUEST['del']))
	{
$delid=intval($_GET['del']);
$sql = "delete from paskaitos  WHERE  id_paskaita=:delid";
$query = $dbh->prepare($sql);
$query -> bindParam(':delid',$delid, PDO::PARAM_STR);
$query -> execute();
$msg="Paskaita sėkmingai ištrinta";
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
</head>

<body>
<?php include('includes/header.php');?>

	<div class="ts-main-content">
<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
<?php
$sql = "SELECT  *  from ((paskaitastudentai INNER JOIN paskaitos ON
paskaitastudentai.fk_paskaita = paskaitos.id_paskaita)
            INNER JOIN lankomumas_irasai ON paskaitastudentai.fk_studentas = lankomumas_irasai.cardid)
WHERE fk_studentas  = $studentoID";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{		$timestamp=htmlentities($result->logdate);
$atejimoData1 = gmdate("Y-m-d H:i:s", $timestamp);
$atejimoData = date("Y-m-d H:i:s", strtotime('+2 hour', strtotime($atejimoData1)));

$id = $result->id_paskaita;

$paskaitosLaikas = $result->laikas;


//echo $atejimoData;
//echo "<br>";



$month = date('F', strtotime($atejimoData));


$intervalas = abs(strtotime($atejimoData) - strtotime($paskaitosLaikas));

$years = floor($intervalas / (365*60*60*24));
$months = floor(($intervalas - $years * 365*60*60*24) / (30*60*60*24));
$days = floor(($intervalas - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
$hours   = floor(($intervalas - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));

$minuts  = floor(($intervalas - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);

$seconds = floor(($intervalas - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60));

//  echo "<br>";
	//printf("%d years, %d months, %d days, %d hours, %d minuts\n, %d seconds\n", $years, $months, $days, $hours, $minuts, $seconds);
	//echo $id;

if($month == $dabartinisMenuo && $days == 0 && $hours == 0 && $minuts >= 0 && $minuts <= 20
|| $month == $dabartinisMenuo && $days == 0 && $hours == 0 && $minuts <= 0 && $minuts >= -20){
	$siMenAteita = $siMenAteita + 1;
}
else {

}

if($buvesMenuo == $month && $days == 0 && $hours == 0 && $minuts >= 0 && $minuts <= 20
|| $buvesMenuo == $month && $days == 0 && $hours == 0 && $minuts <= 0 && $minuts >= -20){
	$buvusiMenAteita = $buvusiMenAteita + 1;
}
else {

}


//	echo $atejimoData;
//echo "<br>";
//	printf("%d years, %d months, %d days, %d hours, %d minuts\n, %d seconds\n", $years, $months, $days, $hours, $minuts, $seconds);
if($days == 0 && $hours == 0 && $minuts >= 0 && $minuts <= 20 || $days == 0 && $hours == 0 && $minuts <= 0 && $minuts >= -20){
//		$ateita = $ateita +1;
		$sql2 = "UPDATE paskaitastudentai SET atvykimas = atvykimas + 1 WHERE fk_studentas = $studentoID AND fk_paskaita = '$id'";
		$query1 = $dbh -> prepare($sql2);
		$query1->execute();
}
else {
	//	echo $id;
	$neateita = $neateita +1;
	$sql3 = "UPDATE paskaitastudentai SET atvykimas = atvykimas  WHERE fk_studentas = $studentoID";
	$query2 = $dbh -> prepare($sql3);
	$query2->execute();

}
}
}
?>
	<?php
	$sql ="SELECT * from vartotojai WHERE id_slapyvardis = $studentoID";
	$query = $dbh -> prepare($sql);;
	$query->execute();
	$results=$query->fetchAll(PDO::FETCH_OBJ);
	$siandien = date('d', strtotime("today"));
	if($query->rowCount() > 0)
	{
		foreach($results as $result)
			{
	?>
						<h1 class="page-title">Sveiki, <?php echo htmlentities($result->vardas);?>  <?php echo htmlentities($result->pavarde);?>!</h1>
					<?php }}?>
						<h4 class="page-title">Čia galite peržiūrėti savo paskaitų statistiką:</h4>
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-3">
										<div class="panel panel-default">
											<div class="panel-body bk-success text-light">
												<div class="stat-panel text-center">
												<?php
$sql1 ="SELECT * FROM paskaitastudentai
WHERE fk_studentas = $studentoID AND atvykimas > 0";
$query1 = $dbh -> prepare($sql1);;
$query1->execute();
$results1=$query1->fetchAll(PDO::FETCH_OBJ);
$ateita=$query1->rowCount();
?>
													<div class="stat-panel-number h1 "><?php echo htmlentities($ateita);?></div>
													<div class="stat-panel-title text-uppercase">Iš viso kartų ateita į paskaitas</div>
												</div>
											</div>
											<a href="post-devices.php" class="block-anchor panel-footer text-center">Išsamiau &nbsp; <i class="fa fa-arrow-right"></i></a>
										</div>
									</div>
									<div class="col-md-3">
										<div class="panel panel-default">
											<div class="panel-body bk-info text-light">
												<div class="stat-panel text-center">
	<?php
	$sql1 ="SELECT * FROM paskaitastudentai INNER JOIN paskaitos ON
	paskaitastudentai.fk_paskaita = paskaitos.id_paskaita
	WHERE fk_studentas = $studentoID AND atvykimas > 0 AND MONTH(laikas) = $dabartinisMenuo";
	$query1 = $dbh -> prepare($sql1);;
	$query1->execute();
	$results1=$query1->fetchAll(PDO::FETCH_OBJ);
	$siMenAteita=$query1->rowCount();
	?>
													 <div class="stat-panel-number h1 "><?php echo $siMenAteita;?></div>
													<div class="stat-panel-title text-uppercase">Šį mėnesį ateita kartų</div>
												</div>
											</div>
											<a href="manage-bookings.php" class="block-anchor panel-footer text-center">Išsamiau &nbsp; <i class="fa fa-arrow-right"></i></a>
										</div>
									</div>
									<div class="col-md-3">
										<div class="panel panel-default">
											<div class="panel-body bk-warning text-light">
												<div class="stat-panel text-center">
<?php
$sql1 ="SELECT * FROM paskaitastudentai INNER JOIN paskaitos ON
paskaitastudentai.fk_paskaita = paskaitos.id_paskaita
WHERE fk_studentas = $studentoID AND atvykimas > 0 AND MONTH(laikas) = $buvesMenuo";
$query1 = $dbh -> prepare($sql1);
$query1->execute();
$results1=$query1->fetchAll(PDO::FETCH_OBJ);
$buvusiMenAteita=$query1->rowCount();
?>
													<div class="stat-panel-number h1 "><?php echo $buvusiMenAteita;?></div>
													<div class="stat-panel-title text-uppercase">Paskutinį mėnesį ateita kartų</div>
												</div>
											</div>
											<a href="manage-brands.php" class="block-anchor panel-footer text-center">Išsamiau &nbsp; <i class="fa fa-arrow-right"></i></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
					<h3 class="page-title">Jūsų tvarkaraštis:</h3>
					<hr>
					<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
						<thead>
							<tr>
							<th>#</th>
								<th>Paskaita</th>
								<th>Data</th>
								<th>Auditorija</th>
								<th>Atėjau?</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
							<th>#</th>
								<th>Paskaita</th>
								<th>Data</th>
								<th>Auditorija</th>
								<th>Atėjau?</th>
							</tr>
						</tfoot>
<?php
$sql = "SELECT  *  from paskaitastudentai INNER JOIN paskaitos ON
paskaitastudentai.fk_paskaita = paskaitos.id_paskaita WHERE fk_studentas = $studentoID";
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
			<td><?php echo htmlentities($result->pavadinimas);?></td>
			<td><?php echo htmlentities($result->laikas);?></td>
			<td><?php echo htmlentities($result->auditorija);?></td>
			<?php if($result->atvykimas > 0){?>
			<td><i class="fa fa-check"></i></a>&nbsp;&nbsp; <?php } else {?>
			<td><i class="fa fa-frown-o"></i></a>&nbsp;&nbsp;<?php }?>
		</tr>
		<?php $cnt=$cnt+1; }} ?>
	</tbody>
</table>
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
