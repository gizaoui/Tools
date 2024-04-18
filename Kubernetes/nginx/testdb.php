<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="css/styles.css">
  </head>

<body>

<?php
$conn = pg_connect("host=postgres-service port=5432 dbname=postgres user=postgres password=postgres");
if (!$conn) {
  echo "An error occurred.\n";
  exit;
}

$result = pg_query($conn, "SELECT version()");
if (!$result) {
  echo "An error occurred.\n";
  exit;
}
?>

<h1>Version</h1>
<p>
<?php
while ($row = pg_fetch_row($result)) {
  echo "Version: $row[0]";
  echo "<br />\n";
}
?>
</p>
</body>
</html>