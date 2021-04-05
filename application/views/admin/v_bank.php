<div class="container-fluid dashboard">
  <?= $breadcrumb; ?>
  <button class="btn btn-success btn-sm mt-2" onclick="showModal('modalAdd')"><i class="fas fa-plus mr-2"></i>Add New Bank</button>
  
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
          <div class="form-group">
            <label>Image</label>
            <input type="file" class="form-control" name="bank_photo">
            <?= form_error("bank_photo"); ?>
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