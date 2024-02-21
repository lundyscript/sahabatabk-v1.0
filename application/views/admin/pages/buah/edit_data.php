<div class="modal-header">
	<h3 class="modal-title">Edit Data</h3>
	<button class="close" data-dismiss="modal">&times;</button>
</div>
<form action="<?= base_url("admin/buah/update_post") ?>" method="post" enctype="multipart/form-data">
	<div class="modal-body">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label for="">Judul Bab</label>
					<input type="text" name="jenis" value="<?= $content->jenis ?>" class="form-control"
						   placeholder="Masukkan jenis buah" autofocus="autofocus"
						   required>
				</div>
				<div class="form-group">
					<label for="">Judul Sub Bab</label>
					<input type="hidden" name="data_id" value="<?= $content->data_id ?>" required>
					<input type="text" name="nama" value="<?= $content->nama ?>" class="form-control"
						   placeholder="Masukkan nama buah.."
						   required>
				</div>
				<div class="form-group">
					<label for="">Deskripsi Mata Kuliah</label>
					<textarea name="deskripsi" id="" class="form-control" rows="8"
							  placeholder="Masukkan deskripsi"><?= $content->deskripsi ?></textarea>
				</div>
				<div class="form-group">
					<label for="">Embed Video</label>
					<textarea name="video" id="" class="form-control" rows="8"><?= $content->video ?></textarea>
				</div>
			</div>
			<div class="col-md-6">
				<h4>Gambar</h4>
				<input type="file" name="userfile" multiple>
				<div class="form-group row">
					<?php foreach ($foto as $item): ?>
						<div class="col-md-6 text-center">
							<img src="<?= base_url("img/$item->jenis_foto") ?>" alt="Foto"
								 style="height: 150px;width: 150px; padding: 3px;"><br>
							<a href="#" onclick="return confirm('Yakin ?')" class="text-danger"><span class="badge badge-danger">Hapus</span></a>
						</div>

					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-primary btn-md">Simpan</button>
		<button type="reset" class="btn btn-warning btn-md" data-dismiss="modal">Batal</button>
	</div>
</form>
