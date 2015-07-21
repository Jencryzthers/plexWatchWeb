<?php include("password_protect.php"); ?>
<?php
require_once(dirname(__FILE__) . '/includes/functions.php');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>plexWatch</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Le styles -->
		<link href="css/plexwatch.css" rel="stylesheet">
		<link href="css/font-awesome.css" rel="stylesheet" >
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
		<link href="css/plexwatch-tables.css" rel="stylesheet">
		<link href="css/xcharts.css" rel="stylesheet" >
		<style type="text/css">
			body {
				padding-top: 60px;
				padding-bottom: 40px;
			}
			.sidebar-nav {
				padding: 9px 0;
			}
		</style>

		<!-- touch icons -->
		<link rel="shortcut icon" href="images/favicon.ico">
		<link rel="apple-touch-icon" href="images/icon_iphone.png">
		<link rel="apple-touch-icon" sizes="72x72" href="images/icon_ipad.png">
		<link rel="apple-touch-icon" sizes="114x114" href="images/icon_iphone@2x.png">
		<link rel="apple-touch-icon" sizes="144x144" href="images/icon_ipad@2x.png">
	</head>
	<body>
		<div class="container">
			<div class="navbar navbar-fixed-top">
				<div class="navbar-inner">
					<a href="index.php"><div class="logo hidden-phone"></div></a>
					<ul class="nav">
						<li><a href="index.php"><i class="fa fa-home fa-2x" data-toggle="tooltip" data-placement="bottom" title="Home" id="home"></i></a></li>
						<li><a href="history.php"><i class="fa fa-history fa-2x" data-toggle="tooltip" data-placement="bottom" title="History" id="history"></i></a></li>
						<li><a href="users.php"><i class="fa fa-users fa-2x" data-toggle="tooltip" data-placement="bottom" title="Users" id="users"></i></a></li>
						<li class="active"><a href="stats.php"><i class="fa fa-area-chart fa-2x" data-toggle="tooltip" data-placement="bottom" title="Stats" id="stats"></i></a></li>
						<li><a href="charts.php"><i class="fa fa-bar-chart fa-2x" data-toggle="tooltip" data-placement="bottom" title="Charts" id="charts"></i></a></li>
						<li><a href="settings.php"><i class="fa fa-cogs fa-2x" data-toggle="tooltip" data-placement="bottom" title="Settings" id="settings"></i></a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">
					<div class="wellheader-bg">
						<div class="dashboard-wellheader-no-chevron">
							<h2><i class="fa fa-area-chart"></i> Statistics</h2>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		echo '<div class="container-fluid">';
			echo '<div class="row-fluid">';
				echo '<div class="span12">';
					echo '<div class="row-fluid">';
						echo '<div class="span6">';
							echo '<div class="wellbg">';
								echo '<div class="history-charts-header">';
									echo 'Hourly Plays (Last 24 Hours)<br>';
								echo '</div>';
								echo '<div class="history-charts-instance-chart" id="playChartHourly"></div>';
							echo '</div>';
						echo '</div>';
						echo '<div class="span6">';
							echo '<div class="wellbg">';
								echo '<div class="history-charts-header">';
									echo 'Max Hourly Plays<br>';
								echo '</div>';
								echo '<div class="history-charts-instance-chart" id="playChartMaxHourly"></div>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
					echo '<div class="row-fluid">';
						echo '<div class="span6">';
							echo '<div class="wellbg">';
								echo '<div class="history-charts-header">';
									echo 'Daily Plays<br>';
								echo '</div>';
								echo '<div class="history-charts-instance-chart" id="playChartDaily"></div>';
							echo '</div>';
						echo '</div>';
						echo '<div class="span6">';
							echo '<div class="wellbg">';
								echo '<div class="history-charts-header">';
									echo 'Monthly Plays<br>';
								echo '</div>';
								echo '<div class="history-charts-instance-chart" id="playChartMonthly"></div>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
					echo '<div class="row-fluid">';
						echo '<div class="span6">';
							echo '<div class="wellbg">';
								echo '<div class="history-charts-header">';
									echo 'Daily Distinct Users<br>';
								echo '</div>';
								echo '<div class="history-charts-instance-chart" id="playChartDailyDistinct"></div>';
							echo '</div>';
						echo '</div>';
						echo '<div class="span6">';
							echo '<div class="wellbg">';
								echo '<div class="history-charts-header">';
									echo 'Monthly Distinct Users<br>';
								echo '</div>';
								echo '<div class="history-charts-instance-chart" id="playChartMonthlyDistinct"></div>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
			?>
		</div>
		<footer></footer>
<?php
$database = dbconnect();
$plexWatchDbTable = dbTable();

$query = "SELECT " .
		"strftime('%Y-%m-%d %H', datetime(time, 'unixepoch', 'localtime')) as date, " .
		"COUNT(title) as count ".
	"FROM $plexWatchDbTable " .
	"WHERE datetime(time, 'unixepoch', 'localtime') >= " .
		"datetime('now', '-24 hours', 'localtime') " .
	"GROUP BY strftime('%Y-%m-%d %H', datetime(time, 'unixepoch', 'localtime')) " .
	"ORDER BY date ASC;";
$hourlyPlays = getResults($database, $query);
$hourlyPlayData = array();
while ($row = $hourlyPlays->fetch(PDO::FETCH_ASSOC)) {
	$hourlyPlayData[] = array('x'=>$row['date'], 'y'=>(int) $row['count']);
}

$query = "SELECT " .
		"strftime('%Y-%m-%d %H', datetime(time, 'unixepoch', 'localtime')) as date, " .
		"COUNT(title) as count " .
	"FROM $plexWatchDbTable " .
	"GROUP BY strftime('%Y-%m-%d %H', datetime(time, 'unixepoch', 'localtime')) " .
	"ORDER BY count(*) desc " .
	"LIMIT 25;";
$maxhourlyPlays = getResults($database, $query);
$maxhourlyPlayData = array();
while ($row = $maxhourlyPlays->fetch(PDO::FETCH_ASSOC)) {
	$maxhourlyPlayData[] = array('x'=>$row['date'], 'y'=>(int) $row['count']);
}

$query = "SELECT " .
		"date(time, 'unixepoch','localtime') as date, " .
		"COUNT(title) as count " .
	"FROM $plexWatchDbTable " .
	"GROUP BY date " .
	"ORDER BY time DESC " .
	"LIMIT 30;";
$dailyPlays = getResults($database, $query);
$dailyPlayData = array();
while ($row = $dailyPlays->fetch(PDO::FETCH_ASSOC)) {
	$dailyPlayData[] = array('x'=>$row['date'], 'y'=>(int) $row['count']);
}

$query = "SELECT " .
		"strftime('%Y-%m', datetime(time, 'unixepoch', 'localtime')) as date, " .
		"COUNT(title) as count " .
	"FROM $plexWatchDbTable " .
	"WHERE datetime(time, 'unixepoch', 'localtime') >= " .
		"datetime('now', '-12 months', 'localtime') " .
	"GROUP BY strftime('%Y-%m', datetime(time, 'unixepoch', 'localtime')) " .
	"ORDER BY date DESC " .
	"LIMIT 13;";
$monthlyPlays = getResults($database, $query);
$monthlyPlayData = array();
while ($row = $monthlyPlays->fetch(PDO::FETCH_ASSOC)) {
	$monthlyPlayData[] = array('x'=>$row['date'], 'y'=>(int) $row['count']);
}

$query = "SELECT date(D.time, 'unixepoch','localtime') as date
			,(
				SELECT COUNT(DISTINCT (CU.user))
				FROM $plexWatchDbTable CU
				Where date(CU.time, 'unixepoch','localtime')=date(D.time, 'unixepoch','localtime')
			) AS count
			FROM $plexWatchDbTable D
			GROUP BY date(D.time, 'unixepoch','localtime')
			ORDER BY D.time DESC LIMIT 30;";
$dailyPlaysDistinct = getResults($database, $query);
$dailyPlayDataDistinct = array();
while ($row = $dailyPlaysDistinct->fetch(PDO::FETCH_ASSOC)) {
	$dailyPlayDataDistinct[] = array('x'=>$row['date'], 'y'=>(int) $row['count']);
}

$query = "SELECT strftime('%Y-%m', datetime(D.time, 'unixepoch', 'localtime')) as date
						,(
							SELECT COUNT(DISTINCT (U.user))
							FROM $plexWatchDbTable U
							Where  strftime('%Y-%m', datetime(U.time, 'unixepoch', 'localtime'))=strftime('%Y-%m', datetime(D.time, 'unixepoch', 'localtime'))
						) AS count
						FROM $plexWatchDbTable D
						WHERE datetime(D.time, 'unixepoch', 'localtime') >= datetime('now', '-12 months', 'localtime') 
						GROUP BY strftime('%Y-%m', datetime(D.time, 'unixepoch', 'localtime'))
						ORDER BY strftime('%Y-%m', datetime(D.time, 'unixepoch', 'localtime')) DESC LIMIT 6; ";
$monthlyPlaysDistinct = getResults($database, $query);
$monthlyPlayDataDistinct = array();
while ($row = $monthlyPlaysDistinct->fetch(PDO::FETCH_ASSOC)) {
	$monthlyPlayDataDistinct[] = array('x'=>$row['date'], 'y'=>(int) $row['count']);
}

?>
		<!-- javascript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="js/jquery-2.0.3.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/jquery.dataTables.js"></script>
		<script src="js/jquery.dataTables.plugin.bootstrap_pagination.js"></script>
		<script src="js/jquery.dataTables.plugin.date_sorting.js"></script>
		<script src="js/d3.v3.js"></script>
		<script src="js/xcharts.min.js"></script>
		<script src="js/moment-with-locale.js"></script>
		<script>
			$(document).ready(function() {
				$('#home').tooltip();
			});
			$(document).ready(function() {
				$('#history').tooltip();
			});
			$(document).ready(function() {
				$('#users').tooltip();
			});
			$(document).ready(function() {
				$('#charts').tooltip();
			});
			$(document).ready(function() {
				$('#settings').tooltip();
			});
			$(document).ready(function() {
				$('#stats').tooltip();
			});
		</script>
		<script>
			var tt = document.createElement('div'),
				leftOffset = -(~~$('html').css('padding-left').replace('px', '') + ~~$('body').css('margin-left').replace('px', '')),
				topOffset = -35;
			tt.className = 'ex-tooltip';
			document.body.appendChild(tt);

			function ttText(d, i, fmt, obj) {
				var pos = $(obj).offset();
				$(tt).text(moment(d.x).format(fmt) + ': ' +
						d.y + ' play' + (d.y > 1 ? 's' : ''))
					.css({top: topOffset + pos.top, left: pos.left + leftOffset})
					.show();
			}

			var hourlyData = {
				"xScale": "ordinal",
				"yScale": "linear",
				"main": [{
					"className": ".playChartHourly",
					"data": JSON.parse('<?php echo json_encode($hourlyPlayData); ?>')
				}]
			};
			var hourlyOpts = {
				"dataFormatX": function (x) {
					return d3.time.format('%Y-%m-%d %H').parse(x);
				},
				"tickFormatX": function (x) {
					return moment(x).format('<?php echo $settings->getTimeFormat(); ?>');
				},
				"paddingLeft": ('35'),
				"paddingRight": ('35'),
				"paddingTop": ('10'),
				"tickHintY": ('5'),
				"mouseover": function(d, i) {
					ttText(d, i, '<?php echo $settings->getTimeFormat(); ?>', this);
				},
				"mouseout": function (x) {
					$(tt).hide();
				}
			};
			var myChart = new xChart('line-dotted', hourlyData, '#playChartHourly', hourlyOpts);

			var maxHourlyData = {
				"xScale": "ordinal",
				"yScale": "linear",
				"main": [{
					"className": ".maxplayChartHourly",
					"data": JSON.parse('<?php echo json_encode($maxhourlyPlayData); ?>')
				}]
			};
			var maxHourlyOpts = {
				"dataFormatX": function (x) {
					return d3.time.format('%Y-%m-%d %H').parse(x);
				},
				"tickFormatX": function (x) {
					return moment(x).format('MMM D');
				},
				"paddingLeft": ('35'),
				"paddingRight": ('35'),
				"paddingTop": ('10'),
				"tickHintY": ('5'),
				"mouseover": function(d, i) {
					ttText(d, i, '<?php echo $settings->getTimeFormat(); ?>', this);
				},
				"mouseout": function (x) {
					$(tt).hide();
				}
			};
			var myChart = new xChart('bar', maxHourlyData, '#playChartMaxHourly', maxHourlyOpts);

			var dailyPlayData = {
				"xScale": "ordinal",
				"yScale": "linear",
				"main": [{
					"className": ".playcount",
					"data": JSON.parse('<?php echo json_encode($dailyPlayData); ?>')
				}]
			};
			var dailyPlayOpts = {
				"dataFormatX": function (x) {
					return d3.time.format('%Y-%m-%d').parse(x);
				},
				"tickFormatX": function (x) {
					return moment(x).format('MMM D');
				},
				"paddingLeft": ('35'),
				"paddingRight": ('35'),
				"paddingTop": ('10'),
				"tickHintY": ('5'),
				"mouseover": function(d, i) {
					ttText(d, i, 'MMM D', this);
				},
				"mouseout": function (x) {
					$(tt).hide();
				}
			};
			var myChart = new xChart('bar', dailyPlayData, '#playChartDaily', dailyPlayOpts);

			var monthlyData = {
				"xScale": "ordinal",
				"yScale": "linear",
				"main": [{
					"className": ".playcount",
					"data": JSON.parse('<?php echo json_encode($monthlyPlayData); ?>')
				}]
			};
			var monthlyOpts = {
				"dataFormatX": function (x) {
					return d3.time.format('%Y-%m').parse(x);
				},
				"tickFormatX": function (x) {
					return moment(x).format('MMM YYYY');
				},
				"paddingLeft": ('35'),
				"paddingRight": ('35'),
				"paddingTop": ('10'),
				"tickHintY": ('5'),
				"mouseover": function(d, i) {
					ttText(d, i, 'MMM', this);
				},
				"mouseout": function (x) {
					$(tt).hide();
				}
			};
			var myChart = new xChart('line-dotted', monthlyData, '#playChartMonthly', monthlyOpts);
			
			
			var monthlyDataDistinct = {
				"xScale": "ordinal",
				"yScale": "linear",
				"main": [{
					"className": ".playcount",
					"data": JSON.parse('<?php echo json_encode($monthlyPlayDataDistinct); ?>')
				}]
			};
			var monthlyOptsDistinct = {
				"dataFormatX": function (x) {
					return d3.time.format('%Y-%m').parse(x);
				},
				"tickFormatX": function (x) {
					return moment(x).format('MMM YYYY');
				},
				"paddingLeft": ('35'),
				"paddingRight": ('35'),
				"paddingTop": ('10'),
				"tickHintY": ('5'),
				"mouseover": function(d, i) {
					ttText(d, i, 'MMM', this);
				},
				"mouseout": function (x) {
					$(tt).hide();
				}
			};
			var myChart = new xChart('line-dotted', monthlyDataDistinct, '#playChartMonthlyDistinct', monthlyOptsDistinct);
			
			
			var dailyPlayDataDistinct = {
				"xScale": "ordinal",
				"yScale": "linear",
				"main": [{
					"className": ".playcount",
					"data": JSON.parse('<?php echo json_encode($dailyPlayDataDistinct); ?>')
				}]
			};
			var dailyPlayOptsDistinct = {
				"dataFormatX": function (x) {
					return d3.time.format('%Y-%m-%d').parse(x);
				},
				"tickFormatX": function (x) {
					return moment(x).format('MMM D');
				},
				"paddingLeft": ('35'),
				"paddingRight": ('35'),
				"paddingTop": ('10'),
				"tickHintY": ('5'),
				"mouseover": function(d, i) {
					ttText(d, i, 'MMM D', this);
				},
				"mouseout": function (x) {
					$(tt).hide();
				}
			};
			var myChart = new xChart('bar', dailyPlayDataDistinct, '#playChartDailyDistinct', dailyPlayOptsDistinct);
			
		</script>
	</body>
</html>