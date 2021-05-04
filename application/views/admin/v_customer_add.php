<div class="container-fluid">
  <?= $breadcrumb ?>
  <div class="card">
    <div class="card-body">
      <?= form_open("add_customer", ["onsubmit" => "loadRequest()"]) ?>
        <div class="form-group">
          <label>Customer Name</label>
          <input type="text" class="form-control <?= form_error("customer_name") ? "is-invalid" : ""; ?>" name="customer_name">
          <?= form_error("customer_name") ?>
        </div>
        <div class="form-row">
          <div class="form-group col-md">
            <label>Email</label>
            <input type="email" class="form-control <?= form_error("customer_email") ? "is-invalid" : ""; ?>" name="customer_email">
            <?= form_error("customer_email") ?>
          </div>
          <div class="form-group col-md">
            <label>Phone</label>
            <input type="number" class="form-control <?= form_error("customer_phone") ? "is-invalid" : ""; ?>" name="customer_phone">
            <?= form_error("customer_phone") ?>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md">
            <label>Province</label>
            <select name="province_id" class="form-control <?= form_error("province_id") ? "is-invalid" : ""; ?>" id="selectProvince">
              <?php
              foreach ($list_province as $item) {
                $id = encrypt_url($item["province_id"]) . ":" . $item["province"];
                echo "<option value='$id'>" . $item["province"] . "</option>";
              }
              ?>
            </select>
            <?= form_error("province_id") ?>
          </div>
          <div class="form-group col-md">
            <label>District</label>
            <select name="district_id" class="form-control <?= form_error("district_id") ? "is-invalid" : ""; ?>" id="selectDistrict">
            </select>
            <?= form_error("district_id") ?>
          </div>
        </div>
        <div class="form-group">
          <label>Full Address</label>
          <textarea name="customer_address" class="form-control <?= form_error("customer_address") ? "is-invalid" : ""; ?>" cols="30" rows="5"></textarea>
          <?= form_error("customer_address") ?>
        </div>
        <div class="form-row">
          <div class="form-group col-md">
            <label>Password</label>
            <input type="password" class="form-control <?= form_error("customer_password") ? "is-invalid" : ""; ?>" name="customer_password">
            <?= form_error("customer_password") ?>
          </div>
          <div class="form-group col-md">
            <label>Konfirmasi Password</label>
            <input type="password" class="form-control <?= form_error("customer_password2") ? "is-invalid" : ""; ?>" name="customer_password2">
            <?= form_error("customer_password2") ?>
          </div>
        </div>
        <div class="form-group">
          <label>Customer Status</label>
          <select name="customer_status_id" class="form-control">
              <?php 
                foreach ($list_customer_status as $item) {
                  echo "<option value='$item->customer_status_id'>$item->customer_status</option>";
                }
              ?>
          </select>
        </div>
        <button type="submit" class="btn btn-success"><i class="fas fa-plus mr-2"></i>Add</button>
        <button type="button" onclick="back('customer')" class="btn btn-danger"><i class="fas fa-times mr-2"></i>Back</button>
      </form>
    </div>
  </div>
</div>

<script>
  let selectProvince = document.getElementById("selectProvince");
  let selectDistrict = document.getElementById("selectDistrict");

  selectProvince.addEventListener("change", function() {
    let provinceID = this.value;
    selectDistrict.innerHTML = "";

    let sendData = fetch(`${baseUrl}get_district?province_id=${provinceID}`, {
        method: "GET"
      }).then(response => response.json()).then(json => {
        if (json.success) {
          let html = ``;
          json.data.forEach(item => {
            html += `<option value="${item.city_id}:${item.city_name}">${item.city_name}</option>`;
          });
          selectDistrict.innerHTML = html;
        }
      })
      .catch(err => console.log(err));
  });
</script>