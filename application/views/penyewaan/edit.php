<?php

$this->load->view('layout/header'); ?>
<div class="container-fluid">
	<h1 class="h3 mb-4 text-gray-800">Edit Penyewaan</h1>
	<!-- Card -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Edit Penyewaan Form</h6>
		</div>
		<div class="card-body">
			<form method="post">

				<div class="form-group">
					<label for="id_kat_barang">Nama Barang</label>
					<select class="form-control" id="id_barang" name="id_barang" required>
						<option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
						<?php foreach ($barang as $b): ?>
							<option value="<?= $b['id'] ?> " <?= $b['id'] == $penyewaan['id_barang'] ? 'selected' : '' ?>><?= $b['name'] ?> - <?= $b['stok'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="id_customer">Nama Customer</label>
					<select class="form-control" id="id_customer" name="id_customer" required>
						<option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
						<?php foreach ($customer as $c): ?>
							<option value="<?= $c['id'] ?>" <?= $c['id'] == $penyewaan['id_customer'] ? 'selected' : '' ?>><?= $c['kode'] ?> || <?= $c['nama'] ?> </option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="id_kat_barang">Nama Supir</label>
					<select class="form-control" id="id_supir" name="id_supir">
						<option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
						<?php foreach ($supir as $s): ?>
							<option value="<?= $s['id'] ?>" <?= $s['id'] == $penyewaan['id_supir'] ? 'selected' : '' ?>><?= $s['nama']  ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="jumlah_keluar">Stok Keluar</label>
					<input type="number" min="1" class="form-control" id="jumlah_keluar" name="jumlah_keluar" value="<?= $penyewaan['jumlah_keluar'] ?>" required>
				</div>
				<div class="form-group">
					<label for="tanggal">Tanggal Jual</label>
					<input type="date" min="<?= date('Y-m-d') ?>" class="form-control" id="tanggal" name="tanggal" value="<?= $penyewaan['tanggal'] ?>" required>
				</div>
				<button type="submit" class="btn btn-primary">Update</button>
			</form>
		</div>

	</div>
</div>
<?php $this->load->view('layout/footer'); ?>