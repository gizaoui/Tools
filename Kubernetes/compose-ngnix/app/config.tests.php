<!DOCTYPE html>
<html>
<body>

<?php
$conn = pg_connect("host=192.168.49.2 port=30432 dbname=postgres user=postgres password=postgres");
if (!$conn) {
  echo "An error occurred.\n";
  exit;
}

$result = pg_query($conn, "SELECT version()");
if (!$result) {
  echo "An error occurred.\n";
  exit;
}

while ($row = pg_fetch_row($result)) {
  echo "Version: $row[0]";
  echo "<br />\n";
}
?>

</body>
</html>


