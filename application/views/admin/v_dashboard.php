<div class="container-fluid dashboard">
  <?= $breadcrumb; ?>
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5>Admin Online</h5>
          <hr style="border: 1px solid white;">
          <ul>
            <?php
            foreach ($admin_online as $item) :
              $to_time = strtotime(date("Y-m-d H:i:s"));
              $from_time = strtotime($item->active_time);
              $last_active = round(abs($to_time - $from_time) / 60);
              if ($last_active <= 0) {
                $last_active = "Active";
              } else {
                $last_active .= "m ago";
              }
              echo "<li><a href='" . base_url("admin_detail?id=" . encrypt_url($item->admin_id)) . "'>$item->admin_name</a> $last_active</li>";
            endforeach;
            ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>