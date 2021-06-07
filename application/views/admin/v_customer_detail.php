<div class="container-fluid">
  <?= $breadcrumb ?>
  <div class="row">
    <div class="col-md-3">
      <img src="<?= $customer->customer_photo ?>" alt="Customer Photo" onerror="onErrorImage(this)" class="img-fluid d-block mx-auto">
    </div>
    <div class="col-md-9">
      <div class="card">
        <div class="card-body">
          <button class="btn btn-success btn-sm mb-2 d-block ml-auto" onclick="navigateTo('customer_edit/<?= encrypt_url($customer->customer_id) ?>')"><i class="fas fa-edit"></i></button>
          <div class="row">
            <div class="col-md-6">
              <table class="table">
                <tr>
                  <td>Name</td>
                  <td>: <?= $customer->customer_name; ?></td>
                </tr>
                <tr>
                  <td>Phone</td>
                  <td>: <?= $customer->customer_phone; ?></td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td>: <?= $customer->customer_email; ?></td>
                </tr>
                <tr>
                  <td>Status</td>
                  <td>: <?= $customer->customer_status; ?></td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <table class="table">
                <tr>
                  <td>Province</td>
                  <td>: <?= $customer->province_name; ?></td>
                </tr>
                <tr>
                  <td>District</td>
                  <td>: <?= $customer->district_name; ?></td>
                </tr>
                <tr>
                  <td>Address</td>
                  <td>: <?= $customer->customer_address; ?></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>