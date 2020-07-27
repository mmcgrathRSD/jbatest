<?php
    $cache = \Cache::instance();
    if (!$cache->exists('product_ymms_.' . (string) $item->id, $ymmMapping )) {
        $ymmMapping = $item->getYMMMapping();
        $cache->set('product_ymms_.' . (string) $item->id, $ymmMapping, 86400);
    }
?>

<?php if (!empty($ymmMapping)) : ?>
    <hr>
    <h3>This Part Fits</h3>

    <?php foreach($ymmMapping as $make => $makes) :
            foreach($makes as $model => $models) : ?>
                <div class="makeModel one_fourth <?php echo $make . '_' . $model; ?>">
                    <span class="make_model"><?php echo $make . ' ' . $model; ?></span>
                    <span class="makeModelsub">
                        <div class="make_model_border"></div>
                    </span>
                        <?php foreach($models as $subModel => $subModels) : ?>
                            <span class="makeModelsub">
                            <span class="subModel"><?php echo $subModel; ?>
                        <?php foreach($subModels as $info) : ?>
                            <a href="<?php echo $info['link'] ?>" title="We have <?php echo $info['product_count'] ?> parts that fit the <?php echo $info['year'] . " " . $make . " " . $model . " " . $subModel; ?>">
                                <div>
                                    <?php echo $info['year'] . " " . $make . " " . $model . " " . $subModel; ?>
                                </div>
                            </a>
                        <?php endforeach; ?>
                            </span>
                        </span>
                        <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

        <?php endforeach; ?>
<?php endif; ?>