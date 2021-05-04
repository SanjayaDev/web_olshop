<div class="container-fluid">
  <?= $breadcrumb; ?>
  <div class="card">
    <div class="card-body">
      <button class="btn btn-success btn-sm mb-3" onclick="navigateTo('add_customer')"><i class="fas fa-plus mr-2"></i>Add New</button>
      <input type="hidden" id="csrfView" value="<?= $csrf ?>">
      <table class="table table-bordered table-hover table-dark w-100" id="tableCustomer">
        <thead>
          <tr>
            <th>No</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<script>
  const tableAjax = getDataTable("tableCustomer", "get_customer");
</script>