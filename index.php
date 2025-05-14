<?php 

require_once 'partials/header.php';
include base_path('partials/navbar.php');
include base_path('partials/hero.php');

$article = new Article();

$articles = $article->get_all();

?>

<main class="container my-5">
    <?php if(!empty($articles)) : ?>
        <?php foreach($articles as $articleItem):?>
            <div class="row mb-4">
                <div class="col-md-4">
                    <?php if(!empty($articleItem->image)): ?>
                        <a href="<?= base_url("article.php?id=$articleItem->id") ?>"><img
                                src="<?= htmlspecialchars($articleItem->image) ?>"
                                class="img-fluid"
                                alt="Blog Post Image"
                                style="width: 350px;height: 200px"
                        ></a>
                    <?php else: ?>
                        <a href="<?= base_url("article.php?id=$articleItem->id") ?>"> <img
                            src="https://placehold.co/350x200"
                            class="img-fluid"
                            alt="Blog Post Image"
                        ></a>
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <h2><?= htmlspecialchars($articleItem->title) ?></h2>
                    <p><?= htmlspecialchars($article->getExcerpt($articleItem->content, 70)) ?></p>
                    <a href="article.php?id=<?= $articleItem->id ?>" class="btn btn-primary">Read More</a>
                </div>
            </div>
        <?php endforeach;?>
    <?php endif; ?>
</main>

<?php include 'partials/footer.php';?>