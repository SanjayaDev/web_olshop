	<!--Sidebar-->
	<div class="sidebar transition overlay-scrollbars">
	  <div class="logo d-block py-3 text-center">
			<h3 style="font-weight: 700;" class="mb-0">Administrator</h3>
			<p>Halo, <?= $this->session->admin_name; ?></p>
	  </div>
	  <div class="sidebar-items" style="margin-top: -20px;">
			<hr style="border: 1px solid white;">
	    <div class="accordion" id="sidebar-items">
	      <ul>

	        <p class="menu" style="margin-top: -10px;">Apps</p>

	        <li>
	          <a href="<?= base_url("dashboard"); ?>" class="items <?= $title == "Dashboard" ? "active" : FALSE; ?>">
	            <i class="fa fa-tachometer-alt"></i>
	            <span>Dashoard</span>
	          </a>
	        </li>
	        <li>
	          <a href="<?= base_url("admin_management") ?>" class="items <?= $title == "Admin Management" ? "active" : FALSE; ?>">
	            <i class="fa fa-users"></i>
	            <span>Admin Management</span>
	          </a>
	        </li>
	      </ul>
	    </div>
	  </div>
	</div>

	<div class="sidebar-overlay"></div>