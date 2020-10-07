<div class="breadcrumbs">
    <ul>
        <li style="display: inline-block;" typeof="Breadcrumb">
            <a href="/" title="Home" rel="v:url" property="title">Home</a>
            <span>/</span>
        </li>
        <?php foreach((array) $breadcrumbs as $link => $title): ?>
            <li style="display: inline-block;" typeof="Breadcrumb">
                <a href="<?php echo $link ?>" title="<?php echo $title ?>" rel="url" property="title"><?php echo $title ?></a>
                <span>/</span>
            </li>
        <?php endforeach; ?>
        <li style="display: inline-block;">
            <strong><?php echo $item->title; ?></strong>
        </li>
    </ul>
</div>