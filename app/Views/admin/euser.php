
<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 text-center">
                <h2 class="mb-0">Edit User</h2>
            </div>
        </div>
    </div>
</header>

<section class="latest-podcast-section d-flex align-items-center justify-content-center pb-5" id="section_2">
    <div class="container">
        <div class="col-lg-12 col-12 mt-5 mb-4 mb-lg-4">
                <div class="custom-block d-flex flex-column">
               
		<form action="<?= base_url('admin/simpan_user')?>" method="POST">
			<table>
				<tr>
					<td>Username</td>
					<td><input type="text" class="form-control" id="username" name="username"  value="<?=$child->username?>"></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><input type="text" class="form-control" id="email" name="email" value="<?=$child->email?>"></td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password"></td>
				</tr>
				<tr>
					<td>Level</td>
					<td><input type="text" class="form-control" id="level"name="level" value="<?= $child->level ?>"></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type="hidden" value="<?=$child->id_user?>" name="id">
						<button type="submit" class="btn btn-primary">Simpan</button>
						<button type="reset" class="btn btn-secondary">Reset</button>
						<button type="button" class="btn btn-secondary">Kembali</button>
					</td>
				</tr>
			</table>
		</form>
		</div>
</div>
</div>  
</section>