<div class="breadcrumbs">
    <ul>
        <li style="display: inline-block;" typeof="v:Breadcrumb">
            <a href="/" title="Home" rel="v:url" property="v:title">Home</a>
            <span>/</span>
        </li>
        <?php foreach((array) $breadcrumbs as $link => $title): ?>
            <li style="display: inline-block;" typeof="v:Breadcrumb">
                <a href="<?php echo $link ?>" title="<?php echo $title ?>" rel="v:url" property="v:title"><?php echo $title ?></a>
                <span>/</span>
            </li>
        <?php endforeach; ?>
        <li style="display: inline-block;">
            <strong><?php echo $item->title; ?></strong>
        </li>
    </ul>
</div>