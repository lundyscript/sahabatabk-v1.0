<div class="hero-wrap" style="background-image: url('<?= base_url() ?>front-end/bg/bg.jpg');"
	 data-stellar-background-ratio="0.5">
	<div class=""></div>
	<div class="container">
		<div class="row no-gutters slider-text justify-content-center align-items-center">
			<div class="col-lg-8 col-md-6 ftco-animate d-flex align-items-end">
				<div class="text text-center">
					<h1 class="py-2"
						style="background: #0E21A0; border-top-right-radius: 50px; border-top-left-radius: 50px; color: skyblue">
						<b>E-Study</b></h1>
					<p class="py-2" style="font-size: 18px;color: white;background: #0E21A0; border-bottom-right-radius: 50px;border-bottom-left-radius: 50px;">
						E-Study adalah tempat sarana belajar online. Selain itu kamu bisa melakukan pencarian data sub bab mata kuliah dibawah ini.
					</p>
					<form action="<?= base_url("search#result") ?>" class="search-location mt-md-5">
						<div class="row justify-content-center">
							<div class="col-lg-10 align-items-end">
								<div class="form-group">
									<div class="form-field">
										<input type="text" class="form-control" name="q" id="pencarian"
											   placeholder="Mau baca bab mata kuliah apa ?" value="<?=$this->input->get('q', true)?>" autocomplete="off" required style="border: 1px solid #0E21A0 !important;">
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
<hr>
<div class="mt-5"></div>
<section class="ftco-section ftco-no-pt">
	<div class="container mt-5">
		<div class="row justify-content-center mb-2">
			<div class="col-md-7 heading-section text-center ftco-animate">
				<h4 class="">Hasil Pencarian "<?= $this->input->get("q", true) ?>"</h4>
			</div>
		</div>
		<div class="row mb-5">
			<div class="col-md-12 text-center">
				<h5>Ditemukan <?= $content["ditemukan"] ?> data, (<?= $content["proses"] ?> detik)</h5>

			</div>
		</div>
		<div>
			<?php if (empty($content["result"] )):?>
			<div class="col-md-12 alert alert-danger"><h3>Pencarian tidak ada. <br>#Gunakan kata kunci yang lebih umum</h3></div>
			<?php else:?>
			<?php foreach ($content["result"] as $key => $item): ?>
				<div class="d-flex ftco-animate my-4 p-2" style="border: 1px solid grey !important;">
					<div class="blog-entry justify-content-end">
						<div class="text">
							<h3 class="heading text-success flex-grow-1">
								<a
									href="<?= base_url("search/detail/$item->data_id") ?>"><?= strlen($item->nama)< 26 ? $item->nama : substr($item->nama, 0,26)."..." ?></a>
								<sup class="text-danger pull-right">(<?=$key+1?>)</sup></a>
							</h3>
							<div class="meta mb-3">
								<div>
									<a href="<?= base_url("search/detail/$item->data_id") ?>"><?= $item->created_at ?></a>
								</div>
							</div>
							<?php
							$foto = $this->db->where("id_master", $item->data_id)->get("ensiklopedia_foto")->row();
							?>
							<!-- <a href="<?= base_url("search/detail/$item->data_id") ?>" class="block-20 img"
							   style="background-image: url('<?= base_url("img/$foto->jenis_foto") ?>');">
							</a> -->
							<p class="text-justify"><?= strlen($item->deskripsi) > 200 ? substr($item->deskripsi, 0, 200) . " ..." : $item->deskripsi ?></p>
							<hr>
							<div class="my-2">
								<a href="#" data-id="<?=$item->data_id?>" data-cosine="<?=$item->cosine?>" class="btn tf-idf" style="background: #0E21A0 !important; color:white !important;">TF-IDF</a>
								<!-- <button class="btn btn-outline-success">Cosine: <?=$item->cosine?></button> -->
							</div>
							<a href="<?= base_url("search/detail/$item->data_id") ?>" class="btn btn-sm" style="background: #E25E3E !important; color:white !important;">Baca Selengkapnya <i class="icon-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
			<?php endforeach; endif; ?>
		</div>
	</div>
</section>
