<?php 
    include 'partials/admin/header.php';
    include 'partials/admin/navbar.php';

    $article = new Article();
    $userId = $_SESSION['user_id'];
    $userArticles = $article->getArticleByUser($userId);
?>

<main class="container my-5">
    <h2 class="mb-4">Welcome <?php echo $_SESSION['username']  ?> to your Admin Dashboard</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Published Date</th>
                <th>Excerpt</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
                <?php if(!empty($userArticles)): ?>
                    <?php foreach ($userArticles as $articleItem): ?>
                    <tr>
                        <td><input type="checkbox" class="articleCheckbox" value="<?= $articleItem->id ?>"></td>
                        <td><?= $articleItem->id ?></td>
                        <td><?= $articleItem->title ?></td>
                        <td><?= $_SESSION['username'] ?></td>
                        <td><?= formatData($articleItem->created_at) ?></td>
                        <td>
                            <?= $article->getExcerpt($articleItem->content) ?>
                        </td>
                        <td>
                            <a href="edit-article.php?id=<?= $articleItem->id ?>" class="btn btn-sm btn-primary me-1">Edit</a>
                        </td>

                        <td>
                            <form onsubmit="confirmDelete(<?= $articleItem->id ?>)" method="POST" action="<?= base_url("delete_article.php") ?>">
                                <input name="id" value="<?= $articleItem->id ?>" type="hidden">
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include 'partials/admin/footer.php';?>