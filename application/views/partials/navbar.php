<style>
	.myshadow {
		box-shadow: 0px 15px 10px -13px yellowgreen !important;
	}
	.nav-link{
		color: white !important;
	}
</style>
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar"
	 style="top: unset; background: #0E21A0 !important; opacity: 0.8;">
	<div class="container">
		<a class="navbar-brand" href="<?= base_url("") ?>"><h4 style="font-weight: bolder; color: skyblue;">sahabatabk</h4></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
				aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="oi oi-menu"></span> Menu
		</button>

		<div class="collapse navbar-collapse" id="ftco-nav">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item"><a href="<?= base_url() ?>" class="nav-link">Home</a></li>
				<li class="nav-item"><a href="<?= base_url("auth/register") ?>" class="nav-link">Register</a></li>
				<li class="nav-item"><a href="<?= base_url("auth") ?>" class="nav-link">Login</a></li>
				<li class="nav-item"><a href="https://www.youtube.com/watch?v=lMf9vDhlCkM" target="_blank" class="nav-link">Tutorial</a></li>
			</ul>
		</div>
	</div>
</nav>
<!-- END nav -->

