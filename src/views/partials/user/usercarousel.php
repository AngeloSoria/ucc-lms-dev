<?php

require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['Carousel']);
$database = new Database();
$pdo = $database->getConnection();


try {
    // Fetch selected images with view_type 'dashboard' and is_selected = 1 for the carousel display
    $stmt = $pdo->query("SELECT carousel_id, title, image_path, view_type FROM carousel WHERE view_type = 'dashboard' AND is_selected = 1 ORDER BY created_at DESC LIMIT 4");
    $dashboardImages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Database query failed: ' . htmlspecialchars($e->getMessage()) . '</div>';
    $dashboardImages = [];
}
?>

<div id="dashboardCarousel" class="carousel slide carousel-fade rounded border shadow-sm" data-bs-ride="carousel">
    <!-- controls -->
    <div class="carousel-indicators">
        <?php if (!empty($dashboardImages)): ?>
            <?php foreach ($dashboardImages as $index => $single_user): ?>
                <button type="button" data-bs-target="#dashboardCarousel" data-bs-slide-to="<?= $index ?>"
                    class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                    aria-label="Slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        <?php else: ?>
            <button type="button" data-bs-target="#dashboardCarousel" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
        <?php endif; ?>
    </div>
    <!-- images -->
    <div class="carousel-inner">
        <?php if (!empty($dashboardImages)): ?>
            <?php foreach ($dashboardImages as $index => $single_user): ?>
                <div class="carousel-item rounded <?= $index === 0 ? 'active' : '' ?>">
                    <img src="<?= BASE_PATH_LINK . htmlspecialchars($single_user['image_path']); ?>" class="d-block"
                        alt="<?= htmlspecialchars($single_user['title']); ?>" />
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="carousel-item active">
                <img src="<?php echo UPLOAD_PATH['System'] . "/img/placeholder-1.jpg" ?>" class="d-block" alt="No images available" />
            </div>
        <?php endif; ?>
    </div>
    <!-- left & right controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#dashboardCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#dashboardCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>