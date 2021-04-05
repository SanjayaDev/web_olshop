<div class="topbar transition">
	<!-- <h4>Template Admin</h4> -->
	<div class="bars">
		<button type="button" class="btn transition" id="sidebar-toggle">
			<i class="las la-bars"></i>
		</button>
		<h5>Applikasi *Nama Aplikasi*</h5>
	</div>
	<div class="menu">
		<ul>

			<li>
				<a href="notifications.html" class="transition">
					<i class="las la-bell"></i>
					<span class="badge badge-danger notif">5</span>
				</a>
			</li>

			<li>
				<div class="dropdown">
					<div class="dropdown-toggle" id="dropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<img src="<?= base_url("assets/image/default_user.png") ?>" alt="Profile">
					</div>
					<div class="dropdown-menu" aria-labelledby="dropdownProfile">

						<a class="dropdown-item" href="<?= base_url("admin_detail?id=" . $this->session->admin_id) ?>">
							<i class="las la-user mr-2"></i> My Profile
						</a>

						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#" data-toggle="modal" data-target="#isLogout">
							<i class="las la-sign-out-alt mr-2"></i> Sign Out
						</a>
					</div>
				</div>
			</li>
		</ul>
	</div>
</div>