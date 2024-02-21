<div class="hero-wrap" style="background-image: url('<?= base_url() ?>front-end/bg/bg.jpg');"
	 data-stellar-background-ratio="0.5">
	<div class=""></div>
	<div class="container">
		<div class="row no-gutters slider-text justify-content-center align-items-center">
			<div class="col-lg-8 col-md-6 ftco-animate d-flex align-items-end">
				<div class="text text-center">
					<h1 class="py-2"
						style="background: #0E21A0; border-top-right-radius: 50px; border-top-left-radius: 50px; color: skyblue">
						<b>sahabatabk.com</b></h1>
					<p style="padding:10px; font-size: 18px;color: white;background: #0E21A0; border-bottom-right-radius: 50px;border-bottom-left-radius: 50px;">
						sahabatabk.com adalah ensiklopedia yang menyediakan informasi pembelajaran daring yang user friendly untuk anak berkebutuhan khusus (ABK).
					</p>
					<form action="<?= base_url("search#result") ?>" class="search-location mt-md-5">
						<div class="row justify-content-center">
							<div class="col-lg-10 align-items-end">
								<div class="form-group">
									<div class="form-field">
										<input type="text" class="form-control" name="q" id="pencarian"
											   placeholder="Mau baca bab mata kuliah apa ?" autocomplete="off" required
											   style="border: 1px solid #0E21A0 !important;">
										<button class="btn" style="background:#0E21A0; border: 1px solid #0E21A0 !important;"><span class="ion-ios-search"></span></button>
									</div>
									<div id="livesearch"></div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<section class="ftco-section ftco-no-pt">
	<div class="container">
		<div class="row justify-content-center my-5">
			<div class="col-md-7 heading-section text-center ftco-animate">
				<h2>Courses Offered</h2>
			</div>
		</div>
		<div>
			<?php foreach ($content as $key => $item): ?>
				<div class="d-flex ftco-animate my-4 p-2" style="border: 1px solid grey !important;">
					<div class="blog-entry justify-content-end">
						<div class="text mt-4">
							<h3><?= $item->kategori ?></h3>
							<h3 class="heading text-success flex-grow-1">
								<a href="<?= base_url("search/detail/$item->data_id") ?>"><?= $item->nama ?></a>
							</h3>
							<div class="meta">
								<div>
									<?= $item->created_at ?>
								</div>
							</div>
							<p class="text-justify"><?= strlen($item->deskripsi) > 300 ? substr($item->deskripsi, 0, 300) . " ..." : $item->deskripsi ?></p>
							<a href="<?= base_url("search/detail/$item->data_id") ?>"
							   class="btn btn-sm" style="background: #E25E3E !important; color:white !important;">Baca Selengkapnya <i
										class="icon-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
