<div class="container-fluid dashboard">
  <?= $breadcrumb; ?>
  <button class="btn btn-success btn-sm my-3" onclick="showModal('modalAdd')"><i class="fas fa-plus mr-2"></i>Add New Bank</button>
  <?php if (is_array($list_bank) && count($list_bank) > 0) : ?>
    <div class="row">
      <?php foreach ($list_bank as $item) : ?>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="icon-bank">
                <button type="button" class="btn btn-info btn-sm" onclick="<?= "getBankDetail('" . encrypt_url($item->bank_id) . "')" ?>"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-danger btn-sm" onclick="<?= "deleteBank('" . encrypt_url($item->bank_id) . "')" ?>"><i class="fas fa-trash"></i></button>
              </div>
              <h5 class="card-title"><?= "$item->bank_name - $item->bank_code"; ?></h5>
              <p class="card-title"><?= "$item->account_number - $item->holder_name"; ?></p>
              <p class="card-title">Branch : <?= $item->branch_name; ?></p>
              <p class="card-title">Status : <?= $item->is_active == 1 ? "Active" : "Discontinued"; ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else :  ?>
    <div class="card">
      <div class="card-body">
        <p class="text-center">No Bank Account</p>
      </div>
    </div>
  <?php endif; ?>
</div>

<div class="modal fade" id="modalAdd">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Bank</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?= form_open_multipart("add_bank", ["onsubmit" => "loadRequest()"]); ?>
        <div class="form-group">
          <label>Bank Name</label>
          <input type="text" class="form-control <?= form_error("bank_name") ? "is-invalid" : ""; ?>" name="bank_name">
          <?= form_error("bank_name"); ?>
        </div>
        <div class="form-group">
          <label>Branch Name</label>
          <input type="text" class="form-control <?= form_error("branch_name") ? "is-invalid" : ""; ?>" name="branch_name">
          <?= form_error("branch_name"); ?>
        </div>
        <div class="form-group">
          <label>Bank Code</label>
          <input type="text" class="form-control <?= form_error("bank_code") ? "is-invalid" : ""; ?>" name="bank_code">
          <?= form_error("bank_code"); ?>
        </div>
        <div class="form-group">
          <label>Account Number</label>
          <input type="text" class="form-control <?= form_error("account_number") ? "is-invalid" : ""; ?>" name="account_number">
          <?= form_error("account_number"); ?>
        </div>
        <div class="form-group">
          <label>Holder Name</label>
          <input type="text" class="form-control <?= form_error("holder_name") ? "is-invalid" : ""; ?>" name="holder_name">
          <?= form_error("holder_name"); ?>
        </div>
        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-plus mr-1"></i>Add</button>
        <?= form_close(); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEdit">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New Bank</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?= form_open_multipart("edit_bank", ["onsubmit" => "loadRequest()"]); ?>
        <div class="form-group">
          <label>Bank Name</label>
          <input type="hidden" name="bank_id" id="bankID">
          <input type="text" class="form-control input-edit <?= form_error("bank_name") ? "is-invalid" : ""; ?>" name="bank_name">
          <?= form_error("bank_name"); ?>
        </div>
        <div class="form-group">
          <label>Branch Name</label>
          <input type="text" class="form-control input-edit <?= form_error("branch_name") ? "is-invalid" : ""; ?>" name="branch_name">
          <?= form_error("branch_name"); ?>
        </div>
        <div class="form-group">
          <label>Bank Code</label>
          <input type="text" class="form-control input-edit <?= form_error("bank_code") ? "is-invalid" : ""; ?>" name="bank_code">
          <?= form_error("bank_code"); ?>
        </div>
        <div class="form-group">
          <label>Account Number</label>
          <input type="text" class="form-control input-edit <?= form_error("account_number") ? "is-invalid" : ""; ?>" name="account_number">
          <?= form_error("account_number"); ?>
        </div>
        <div class="form-group">
          <label>Holder Name</label>
          <input type="text" class="form-control input-edit <?= form_error("holder_name") ? "is-invalid" : ""; ?>" name="holder_name">
          <?= form_error("holder_name"); ?>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="is_active" class="form-control input-edit">
            <option value="1">Active</option>
            <option value="0">Discontinued</option>
          </select>
        </div>
        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-plus mr-1"></i>Add</button>
        <?= form_close(); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  const getBankDetail = (bankID) => {
    getDataEdit(`get_bank_detail?id=${bankID}`, "modalEdit");
    document.getElementById("bankID").value = bankID;
  }

  const deleteBank = (bankID) => {
    Swal.fire({
      title: 'Anda yakin?',
      text: "Item ini akan dihapus secara permanent!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus ini!'
    }).then((result) => {
      if (result.value) {
        loadRequest();
        window.location.href = `<?= base_url("delete_bank?id=") ?>${bankID}`;
      }
    })
  }
</script>