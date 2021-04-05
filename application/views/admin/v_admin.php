<div class="container-fluid dashboard">
  <?= $breadcrumb ?>
  <h4>Admin Management</h4>
  <div class="card">
    <div class="card-body">
      <button class="btn btn-success btn-sm mb-4" onclick="showModal('modalAdd')"><i class="fas fa-plus mr-1"></i>Tambah Admin</button>
      <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-dark w-100" id="tableAdmin">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Admin Level</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalAdd">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Admin</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?= form_open("add_admin", ["onsubmit" => "submitAdmin('formAdd', event)", "id" => "formAdd"]); ?>
        <div class="form-group">
          <label>Name</label>
          <input type="text" class="form-control" name="admin_name" placeholder="Input admin nam...">
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" class="form-control" name="admin_email" placeholder="Input admin email...">
        </div>
        <div class="form-group">
          <label>Phone</label>
          <input type="number" class="form-control" name="admin_phone" placeholder="Input admin phone...">
        </div>
        <div class="form-group">
          <label>Admin Level</label>
          <select name="admin_tierId" class="form-control">
            <?php
            foreach ($list_access as $item) {
              echo "<option value='" . encrypt_url($item->admin_tier_id) . "'>$item->access_levelName</option>";
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label>Admin Status</label>
          <select name="admin_statusId" class="form-control">
            <?php
            foreach ($list_admin_status as $item) {
              echo "<option value='" . encrypt_url($item->admin_status_id) . "'>$item->admin_status</option>";
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label>Photo</label>
          <input type="file" class="form-control" name="admin_photo" id="inputFile" onchange="readerImage('inputFile', 'imgLoad')">
        </div>
        <img src="" class="img-fluid mb-2" width="100%" id="imgLoad">
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="admin_password" class="form-control" placeholder="Input admin password...">
        </div>
        <div class="form-group">
          <label>Re-password</label>
          <input type="password" name="admin_repassword" class="form-control" placeholder="Input re-password admin...">
        </div>
        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-plus mr-1"></i>Save</button>
        <?= form_close(); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
  const table = getDataTable("tableAdmin", "get_admin");

  function submitAdmin(formId, e) {
    e.preventDefault();
    sendData(formId, "add_admin", "modalAdd", table);
  }
</script>