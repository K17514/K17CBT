<header class="site-header d-flex flex-column justify-content-center align-items-center">
    <div class="container">
        <h2 class="mb-0">Course Info</h2>
    </div>
        
</header>
<div class="container mb-4">
    <form action="" method="POST">
        <table>
         <tr>
            <td>Created By</td>
            <td><input type="text" class="form-control" id="created_by" name="created_by" value="<?=$child->created_by_name?>" disabled></td>
        </tr>
        <tr>
            <td>Created At</td>
            <td><input type="text" class="form-control" id="created_at" name="created_at" value="<?=$child->created_at?>" disabled></td>
        </tr>
        <tr>
            <td>Updated By</td>
            <td><input type="text" class="form-control" id="updated_by" name="updated_by" value="<?=$child->updated_by_name?>" disabled></td>
        </tr>
        <tr>
            <td>Updated At</td>
            <td><input type="text" class="form-control" id="updated_at" name="updated_at" value="<?=$child->updated_at?>" disabled></td>
        </tr>
        <tr>
            <td>Deleted By</td>
            <td><input type="text" class="form-control" id="deleted_by" name="deleted_by" value="<?=$child->deleted_by_name?>" disabled></td>
        </tr>
        <tr>
            <td>Deleted At</td>
            <td><input type="text" class="form-control" id="deleted_at" name="deleted_at" value="<?=$child->deleted_at?>" disabled></td>
        </tr>

        <td></td>
        <td>
                <button class="btn btn-dark" onclick="history.back()">
    <i class="fas fa-save"></i> Kembali
</button>
            </td>
        </tr>
    </table>
</form>
</div>