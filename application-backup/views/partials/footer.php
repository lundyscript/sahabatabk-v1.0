<footer class="ftco-footer ftco-section" id="contact">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<p class="py-0 my-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
					Copyright &copy;<script>document.write(new Date().getFullYear());</script>
					All rights reserved</p>
			</div>
		</div>
	</div>
</footer>


<!-- loader -->
<div id="ftco-loader" class="show fullscreen">
	<svg class="circular" width="48px" height="48px">
		<circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
		<circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
				stroke="#F96D00"/>
	</svg>
</div>
<script src="<?= base_url() ?>front-end/js/jquery.min.js"></script>
<script src="<?= base_url() ?>front-end/js/jquery-migrate-3.0.1.min.js"></script>
<script src="<?= base_url() ?>front-end/js/popper.min.js"></script>
<script src="<?= base_url() ?>front-end/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>front-end/js/jquery.easing.1.3.js"></script>
<script src="<?= base_url() ?>front-end/js/jquery.waypoints.min.js"></script>
<script src="<?= base_url() ?>front-end/js/jquery.stellar.min.js"></script>
<script src="<?= base_url() ?>front-end/js/owl.carousel.min.js"></script>
<script src="<?= base_url() ?>front-end/js/jquery.magnific-popup.min.js"></script>
<script src="<?= base_url() ?>front-end/js/aos.js"></script>
<script src="<?= base_url() ?>front-end/js/jquery.animateNumber.min.js"></script>
<script src="<?= base_url() ?>front-end/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>front-end/js/jquery.timepicker.min.js"></script>
<script src="<?= base_url() ?>front-end/js/scrollax.min.js"></script>
<!--<script src="--><? //=base_url()?><!--front-end/https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>-->
<!--<script src="--><? //=base_url()?><!--front-end/js/google-map.js"></script>-->
<script src="<?= base_url() ?>front-end/js/main.js"></script>
<?php if ($this->uri->segment(1) == "search"): ?>
	<script>
		$(".tf-idf").on("click", function (e) {
			e.preventDefault();
			var data_id = $(this).data("id");
			var cosine = $(this).data("cosine");
			$("#modal-tf-id").modal("show");
			$(".res-tf-id").html('<h3 class="text-center text-danger"><em>Sedang mengambil data . . .</em></h3>');
			$.ajax({
				url: '<?=base_url("search/tf_idf/")?>' + data_id,
				method: 'GET',
				dataType: "HTML",
				success: function (html) {
					$(".res-tf-id").html(html);
					$(".cosine").html("<p class='text-success font-bold'>Cosine: " + cosine + "</p>");
				}
			})
		});
	</script>
	<style>
		.modal-dialog {
			overflow-y: initial !important
			/*width: 800 !important;*/
		}

		.modal-body {
			height: 390px;
			overflow-y: auto;
		}
	</style>
	<div class="modal fade" role="dialog" id="modal-tf-id">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header text-center bg-success">
					<h2 class="modal-title text-white text-center">HASIL KOMPUTASI TF-IDF</h2>
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body res-tf-id">

				</div>
				<div class="modal-footer text-center" style="justify-content: center!important;">
					<p class="cosine"></p>
					<!--				<button class="btn btn-success btn-lg" data-dismiss="modal">Tutup Modal</button>-->
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<script>

	// suggestion
	$('#pencarian').on('keyup', function () {
		function timer() {
			var site = "<?php echo base_url('Suggestion/autocomplete'); ?>";
			var str = $("#pencarian").val();
			if (str.length < 1) {
				$("#livesearch").html("");
			} else {
				$.ajax({
					type: "GET",
					url: site,
					data: {search: str},
					success: function (html) {
						$("#livesearch").html(html).show();
					}
				});
			}

		}

		setTimeout(timer, 1000);
	});
</script>
</body>
</html>
