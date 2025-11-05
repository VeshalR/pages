<?php
ob_start();
include '../includes/connection.php';
include '../includes/topp.php';

// Handle Insert/Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $customer_name = $_POST['customer_name'];
    $customer_phone = $_POST['customer_phone'];
    $device = $_POST['device'];
    $issue = $_POST['issue'];
    $status = $_POST['status'];

    if ($id) {
        // Update
        $stmt = $db->prepare("UPDATE repair_jobs SET customer_name=?, customer_phone=?, device=?, issue=?, status=? WHERE id=?");
        $stmt->bind_param("sssssi", $customer_name, $customer_phone, $device, $issue, $status, $id);
    } else {
        // Insert
        $stmt = $db->prepare("INSERT INTO repair_jobs (customer_name, customer_phone, device, issue, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $customer_name, $customer_phone, $device, $issue, $status);
    }
    $stmt->execute();
    header("Location: repair_tracking.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($db, "DELETE FROM repair_jobs WHERE id = $id");
    header("Location: repair_tracking.php");
    exit;
}

// Filters
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$filterSQL = "WHERE 1=1 ";
if ($search) $filterSQL .= "AND (customer_name LIKE '%$search%' OR device LIKE '%$search%') ";
if ($status) $filterSQL .= "AND status = '$status' ";
if ($from && $to) $filterSQL .= "AND DATE(created_at) BETWEEN '$from' AND '$to' ";
$result = mysqli_query($db, "SELECT * FROM repair_jobs $filterSQL ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Repair Tracking</title>
  <!-- ✅ Bootstrap 4.6 & FontAwesome CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>@media print {.no-print {display:none}}</style>
</head>
<body id="page-top">
<div id="wrapper">

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
    <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-laptop"></i></div>
    <div class="sidebar-brand-text mx-3">KCC Inventory</div>
  </a>
  <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
  <li class="nav-item active"><a class="nav-link" href="repair_tracking.php"><i class="fas fa-tools"></i><span>Repair Tracking</span></a></li>
</ul>

<div id="content-wrapper" class="d-flex flex-column">
<div id="content">
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
  <h1 class="h3 text-gray-800">Repair Tracking</h1>
</nav>
<div class="container-fluid">

<form class="form-inline mb-3 no-print" method="get">
  <input type="text" name="search" class="form-control mr-2" placeholder="Search customer/device" value="<?= $search ?>">
  <select name="status" class="form-control mr-2">
    <option value="">All Status</option>
    <option <?= $status == 'Pending' ? 'selected' : '' ?>>Pending</option>
    <option <?= $status == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
    <option <?= $status == 'Completed' ? 'selected' : '' ?>>Completed</option>
  </select>
  <label class="mr-1">From:</label>
  <input type="date" name="from" class="form-control mr-2" value="<?= $from ?>">
  <label class="mr-1">To:</label>
  <input type="date" name="to" class="form-control mr-2" value="<?= $to ?>">
  <button type="submit" class="btn btn-primary">Search</button>
  <a href="repair_tracking.php" class="btn btn-secondary ml-2">Reset</a>
  <button onclick="window.print()" type="button" class="btn btn-info ml-2">🖨️ Print</button>
  <a href="repair_export_csv.php?<?= http_build_query($_GET) ?>" class="btn btn-success ml-2">📤 Export CSV</a>
  <button type="button" class="btn btn-dark ml-2" data-toggle="modal" data-target="#repairModal" onclick="openModal()">➕ Add Repair</button>
</form>

<div class="card shadow mb-4">
  <div class="card-header bg-dark text-white"><h6 class="m-0">Repair Job List</h6></div>
  <div class="card-body table-responsive">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Customer</th>
          <th>Phone</th>
          <th>Device</th>
          <th>Issue</th>
          <th>Status</th>
          <th>Created</th>
          <th class="no-print">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php while ($row = mysqli_fetch_array($result)): ?>
        <tr>
          <td><?= $row['customer_name'] ?></td>
          <td><?= $row['customer_phone'] ?></td>
          <td><?= $row['device'] ?></td>
          <td><?= $row['issue'] ?></td>
          <td><span class="badge badge-<?= $row['status'] == 'Completed' ? 'success' : ($row['status'] == 'In Progress' ? 'info' : 'warning') ?>"><?= $row['status'] ?></span></td>
          <td><?= $row['created_at'] ?></td>
          <td class="no-print">
            <button class="btn btn-sm btn-primary" onclick='openModal(<?= json_encode($row) ?>)'>Edit</button>
            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this record?')" class="btn btn-sm btn-danger">Delete</a>
            <?php if ($row['status'] == 'Completed' && $row['customer_phone']): ?>
              <a href="https://wa.me/6<?= $row['customer_phone'] ?>?text=Hello%20<?= urlencode($row['customer_name']) ?>%2C%20your%20<?= urlencode($row['device']) ?>%20repair%20is%20now%20completed.%20Thank%20you!" target="_blank" class="btn btn-sm btn-success mt-1">📲 WhatsApp</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</div></div>
<footer class="sticky-footer bg-white"><div class="container text-center"><span>KCC Secure Stock &copy; <?= date("Y") ?></span></div></footer>
</div></div>

<!-- Modal Form -->
<div class="modal fade" id="repairModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document"><div class="modal-content">
    <form method="post">
      <div class="modal-header bg-primary text-white"><h5 class="modal-title">Repair Job</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="repair_id">
        <div class="form-group"><label>Customer Name</label><input type="text" name="customer_name" id="customer_name" class="form-control" required></div>
        <div class="form-group"><label>Phone</label><input type="text" name="customer_phone" id="customer_phone" class="form-control" required></div>
        <div class="form-group"><label>Device</label><input type="text" name="device" id="device" class="form-control" required></div>
        <div class="form-group"><label>Issue</label><input type="text" name="issue" id="issue" class="form-control" required></div>
        <div class="form-group"><label>Status</label>
          <select name="status" id="status" class="form-control" required>
            <option>Pending</option>
            <option>In Progress</option>
            <option>Completed</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success" type="submit">Save</button>
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div></div>
</div>

<!-- ✅ JS Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ✅ Global openModal function
function openModal(data = null) {
  $('#repair_id').val(data?.id || '');
  $('#customer_name').val(data?.customer_name || '');
  $('#customer_phone').val(data?.customer_phone || '');
  $('#device').val(data?.device || '');
  $('#issue').val(data?.issue || '');
  $('#status').val(data?.status || 'Pending');
  $('#repairModal').modal('show');
}
</script>

</body>
</html>

<?php ob_end_flush(); ?>
