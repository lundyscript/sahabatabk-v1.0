<section class="ftco-section ">
	<div class="container">
		<div class="row p-5">
			<div class="p-5 order-md-last ftco-animate">
				<h2 class="mb-3"><?= $content->kategori ?></h2>
				<h3><?= $content->nama ?></h3>
				<?php
				$foto = $this->db->where("id_master", $content->data_id)->get("ensiklopedia_foto")->result();
				?>
				<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
					<ol class="carousel-indicators">
						<?php foreach ($foto as $key => $item): ?>
							<li data-target="#carouselExampleIndicators"
								data-slide-to="<?= $key ?>" <?= $key == 0 ? 'class="active"' : '' ?>></li>
						<?php endforeach; ?>
					</ol>
					<div class="carousel-inner">
						<?php foreach ($foto as $key => $item): ?>
							<div class="carousel-item <?= $key == 0 ? 'active' : '' ?>">
								<img class="d-block w-100" src="<?= base_url("img/$item->jenis_foto") ?>" alt="First slide">
							</div>
						<?php endforeach; ?>
					</div>
					<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
				<p class="text-justify"><?= $content->deskripsi ?></p>
				<?= $content->video ?>
			</div>
		</div>
	</div>
</section>
