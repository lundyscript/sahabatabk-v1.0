<div class="card">
	<div class="card-body">
		<?php
		if ($this->session->flashdata("msg")) : ?>
			<div class="alert alert-info"><?= $this->session->flashdata("msg") ?></div>
		<?php endif; ?>
		<div class="form-group">
			<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#tambah">Tambah Data</a>
		</div>
		<div class="table-responsive">
			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>Judul</th>
						<th width="500px">Deskripsi Mata Kuliah
						</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($content as $key => $value) :
						$foto = $this->db->get_where("ensiklopedia_foto", array("id_master" => $value->data_id))->row();
					?>
						<tr>
							<td><?= $key + 1 ?></td>
							<td>
								<?= $value->jenis ?>
								<h4><?= $value->nama ?></h4>
							</td>
							<td><?= strlen($value->deskripsi) > 200 ? substr($value->deskripsi, 0, 200) . " ..." : $value->deskripsi ?></td>
							<td>
								<a href="#" class="btn btn-sm btn-outline-dark btn-block detail" data-toggle="modal" data-target="#detail" data-id="<?= $value->data_id ?>">DETAIL</a>
								<a href="#" class="btn btn-sm btn-info btn-block edit" data-toggle="modal" data-target="#edit" data-id="<?= $value->data_id ?>">EDIT</a>
								<a href="<?= base_url("admin/buah/delete/$value->data_id") ?>" onclick="return confirm('Yakin ?')" class="btn btn-sm btn-danger btn-block">HAPUS</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!--modal tambah data-->
<style>
	/*.modal {*/
	/*	display: block !important; !* I added this to see the modal, you don't need this *!*/
	/*}*/

	/* Important part */
	.modal-dialog {
		overflow-y: initial !important
			/*width: 800 !important;*/
	}

	.modal-body {
		height: 450px;
		overflow-y: auto;
	}
</style>
<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">Tambah Data Baru</h3>
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<form action="<?= base_url("admin/buah/save_post") ?>" method="post" enctype="multipart/form-data">
			<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">Judul Bab</label>
								<input type="text" name="jenis" class="form-control" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">Judul Sub Bab</label>
								<input type="text" name="nama" class="form-control" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="">Deskripsi</label>
								<textarea name="deskripsi" id="" class="form-control" rows="4" placeholder="Masukkan deskripsi"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="">Embed Video</label>
								<input type="text" name="video" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="">Gambar</label><br/>
								<input type="file" name="userfile[]" multiple >
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary btn-md">Simpan</button>
					<button type="reset" class="btn btn-danger btn-md">Reset</button>
					<button type="reset" class="btn btn-warning btn-md" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!--modal edit-->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content result_edit" style="background: whitesmoke!important;">

		</div>
	</div>
</div>

<!--modal Data detail-->
<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content" style="background: whitesmoke!important;">
			<div class="modal-header">
				<h3 class="modal-title">Detail Data</h3>
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body result">
			</div>
		</div>
	</div>
</div>