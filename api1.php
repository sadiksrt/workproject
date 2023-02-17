<!DOCTYPE html>
<html>
<head>
	<title>Remote API Data Table</title>
	<style>
		table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
			padding: 5px;
		}
		td.color {
			color: white;
			font-weight: bold;
			text-align: center;
		}
	</style>
</head>
<body>
	<h1>Remote API Data Table</h1>
	<form method="get">
		<input type="text" name="search" placeholder="Search...">
		<input type="submit" value="Submit">
	</form>
	<?php
	
		function getData() {
			$url = "https://api.baubuddy.de/dev/index.php/v1/tasks/select";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
			return json_decode($result, true);
		}
		
	
		function displayData($data) {
			echo "<table>\n";
			echo "<tr><th>Task</th><th>Title</th><th>Description</th><th>Color Code</th></tr>\n";
			foreach ($data as $row) {
				echo "<tr>\n";
				echo "<td>" . $row['task'] . "</td>\n";
				echo "<td>" . $row['title'] . "</td>\n";
				echo "<td>" . $row['description'] . "</td>\n";
				echo "<td class='color' style='background-color: " . $row['colorCode'] . "'>" . $row['colorCode'] . "</td>\n";
				echo "</tr>\n";
			}
			echo "</table>\n";
		}
		
		
		$data = getData();
		displayData($data);
		
		
		if (isset($_GET['search'])) {
			$search = $_GET['search'];
			$data_filtered = array_filter($data, function($row) use ($search) {
				return stripos($row['task'], $search) !== false
					|| stripos($row['title'], $search) !== false
					|| stripos($row['description'], $search) !== false
					|| stripos($row['colorCode'], $search) !== false;
			});
			displayData($data_filtered);
		}
	?>
	<script>
		
		setTimeout(function() {
			location.reload();
		}, 60 * 60 * 1000);
	</script>
	
	
	<button onclick="document.getElementById('modal').style.display='block'">Select Image</button>
	

	<div id="modal" style="display: none;">
		<h2>Select Image</h2>
		<form>
			<input type="file" accept="image/*">
			<input type="button" value="Submit">
		</form>
		<button onclick="document.getElementById('modal').style.display='none'">Close</button>
	</div>
</body>
</html>
