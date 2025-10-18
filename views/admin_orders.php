<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Pesanan - Admin</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/admin.css">
</head>
<body>
    <h1>Daftar Pesanan</h1>
    <a href="<?= BASE_URL ?>/admin">Kembali</a>
    <table>
        <thead>
            <tr><th>ID</th><th>Pelanggan</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $o): ?>
            <tr>
                <td><?= htmlspecialchars($o['id']) ?></td>
                <td><?= htmlspecialchars($o['customer'] ?: 'Guest') ?></td>
                <td>Rp <?= number_format($o['total'],0,',','.') ?></td>
                <td><?= htmlspecialchars($o['status']) ?></td>
                <td><?= htmlspecialchars($o['created_at']) ?></td>
                <td>
                    <form action="<?= BASE_URL ?>/admin/orders/update" method="post" style="display:inline">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($o['id']) ?>">
                        <select name="status">
                            <option value="pending" <?= $o['status']=='pending' ? 'selected' : '' ?>>pending</option>
                            <option value="processing" <?= $o['status']=='processing' ? 'selected' : '' ?>>processing</option>
                            <option value="shipped" <?= $o['status']=='shipped' ? 'selected' : '' ?>>shipped</option>
                            <option value="completed" <?= $o['status']=='completed' ? 'selected' : '' ?>>completed</option>
                            <option value="cancelled" <?= $o['status']=='cancelled' ? 'selected' : '' ?>>cancelled</option>
                        </select>
                        <button type="submit">Simpan</button>
                    </form>
                    <a href="<?= BASE_URL ?>/admin/orders/<?= htmlspecialchars($o['id']) ?>">Detail</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>