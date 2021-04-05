<div class="container-fluid">
  <?= $breadcrumb; ?>
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <img src="<?= $admin_info->admin_photo ?>" class="img-fluid d-block mx-auto" alt="Image Admin" onerror="onErrorImage(this)">
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="card">
        <div class="card-body">
          <button class="btn btn-primary btn-sm d-block ml-auto mb-3" onclick="showModal('modalEdit')"><i class="fas fa-edit mr-2"></i>Edit</button>
          <div class="table-responsive">
            <table class="table">
              <tr>
                <td>Name</td>
                <td>: <?= $admin_info->admin_name; ?></td>
              </tr>
              <tr>
                <td>Email</td>
                <td>: <?= $admin_info->admin_email; ?></td>
              </tr>
              <tr>
                <td>Phone</td>
                <td>: <?= $admin_info->admin_phone; ?></td>
              </tr>
              <tr>
                <td>Admin Level</td>
                <td>: <?= $admin_info->access_levelName; ?></td>
              </tr>
              <tr>
                <td>Last Login</td>
                <td>: <?= $admin_info->admin_lastLogin != NULL ? date("d M Y H:i", strtotime($admin_info->admin_lastLogin)) : "-"; ?></td>
              </tr>
              <tr>
                <td>Last Update</td>
                <td>: <?= $admin_info->updated_date != NULL ? date("d M Y H:i", strtotime($admin_info->updated_date)) : "-"; ?></td>
              </tr>
              <tr>
                <td>Status</td>
                <td>: <?= $admin_info->admin_status; ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md">
      <div class="card">
        <div class="card-body">
          <h5 class="mb-4">Activity Log</h5>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-dark w-100" id="tableActivity">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Activity</th>
                  <th>Description</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                if (isset($list_activity) && is_array($list_activity) && count($list_activity) > 0) {
                  foreach ($list_activity as $item) {
                    echo "<tr>";
                    echo "<td>$no</td>";
                    echo "<td>$item->action</td>";
                    echo "<td>$item->description</td>";
                    echo "<td>" . date("j F Y H:i", strtotime($item->time)) . "</td>";
                    echo "</tr>";
                    $no++;
                  }
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEdit">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Admin</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?= form_open_multipart("edit_admin", ["onsubmit" => "loadRequest()"]); ?>
        <div class="form-group">
          <label>Name</label>
          <input type="text" class="form-control" name="admin_name" placeholder="Input admin nam..." value="<?php echo $admin_info->admin_name ?>">
          <input type="hidden" name="admin_id" value="<?= encrypt_url($admin_info->admin_id) ?>">
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" class="form-control" name="admin_email" placeholder="Input admin email..." value="<?php echo $admin_info->admin_email ?>">
        </div>
        <div class="form-group">
          <label>Phone</label>
          <input type="number" class="form-control" name="admin_phone" placeholder="Input admin phone..." value="<?php echo $admin_info->admin_phone ?>">
        </div>
        <div class="form-group">
          <label>Admin Level</label>
          <select name="admin_tierId" class="form-control">
            <?php
            foreach ($list_access as $item) {
              $selected = $admin_info->admin_tierId == $item->admin_tier_id ? "selected" : "";
              echo "<option $selected value='" . encrypt_url($item->admin_tier_id) . "'>$item->access_levelName</option>";
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label>Admin Status</label>
          <select name="admin_statusId" class="form-control">
            <?php
            foreach ($list_admin_status as $item) {
              $selected = $item->admin_status_id == $admin_info->admin_statusId ? "active" : FALSE;
              echo "<option $selected value='" . encrypt_url($item->admin_status_id) . "'>$item->admin_status</option>";
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
          <input type="password" name="admin_password" class="form-control" placeholder="Empty if not change password...">
        </div>
        <div class="form-group">
          <label>Re-password</label>
          <input type="password" name="admin_repassword" class="form-control" placeholder="Input re-password admin...">
        </div>
        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-plus mr-1"></i>Save Changes</button>
        <?= form_close(); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  dataTable("tableActivity");
</script>