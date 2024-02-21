<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<h3><?=$content->jenis?></h3>
				<h1><?=$content->nama?></h1>
				<?php foreach ($foto as $item):?>
					<img src="<?=base_url("img/$item->jenis_foto")?>" alt="Foto" style="padding-bottom: 20px;">
				<?php endforeach;?>
				<p class="text-justify"><?=$content->deskripsi?></p>
				<?=$content->video?>
			</div>
		</div>
	</div>
</div>
