<?php 

require_once 'partials/header.php';
include base_path('partials/navbar.php');
include base_path('partials/hero.php');

$articleId = isset($_GET['id']) ? (int)$_GET['id'] : null;

if($articleId) {
    $article = new Article();

    $articleData = $article->getArticleWithOwnerById($articleId);
} else {
    echo "Article is not defined";
}

?>

<main class="container my-5">
    <div class="mb-4">
        <?php if(!empty($articleData->image)): ?>
            <img
                src="<?php echo htmlspecialchars($articleData->image) ?>"
                class="img-fluid w-100"
                alt="Featured Image"
                style="max-height: 600px"
            >

        <?php else: ?>
            <img
                src="https://placehold.co/1200x600"
                class="img-fluid w-100"
                alt="Featured Image"
                style="max-height: 600px"
            >
        <?php endif; ?>
    </div>

    <section>
        <div class="container">
            <h1 class="display-4"><?php echo $articleData->title; ?></h1>
            <small>
                By <a href=""><?php echo $articleData->username; ?></a>
                <span><?php echo $article->formatData($articleData->created_at); ?></span>
            </small>
        </div>
    </section>

    <article class="container my-5">
        <?php echo htmlspecialchars($articleData->content); ?>
    </article>

    <section class="mt-5">
        <h3>Comments</h3>
        <p>
            Comments functionality will be implemented here.
        </p>
    </section>

    <div class="mt-4">
        <a href="<?php echo base_url("index.php"); ?>" class="btn btn-secondary">← Back to Home</a>
    </div>
</main>

<?php include 'partials/footer.php';?>
