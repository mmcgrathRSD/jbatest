<div>

    <div>
    
        <?php if (!empty($paginated->items)) { ?>
    
        <?php foreach($paginated->items as $item) { 
            $item->_url = './pages/page/' . $item->slug; 
        ?>
        <article id="page-<?php echo $item->id; ?>" class="page-<?php echo $item->id; ?>">

            <div class="entry-header">
                <?php if ($item->{'featured_image.slug'}) { ?>
                <a href="<?php echo $item->_url; ?>">
                <img class="entry-featured img-responsive" width="100%" src="./asset/<?php echo $item->{'featured_image.slug'} ?>">
                </a>
                <?php } ?>
            
                <?php if ($item->{'metadata.creator.image'}) { ?>
                <img class="entry-avatar" alt="<?php echo $item->{'metadata.creator.name'}; ?>" height="52" width="52"
                    src="<?php echo $item->{'metadata.creator.image'}; ?>">
                <?php } ?>
                
                <h2 class="entry-title">
                    <a href="<?php echo $item->_url; ?>">
                    <?php echo $item->{'title'}; ?>
                    </a>
                </h2>
                <div class="entry-meta lead">
                    <a href="<?php echo $item->_url; ?>" title="<?php echo date( 'g:i a', $item->{'publication.start.time'} ); ?>"
                        rel="bookmark"><?php echo date( 'F j, Y', $item->{'publication.start.time'} ); ?></a>
                    /
                    <span class="byline">
                        <span class="author vcard">
                            <a class="url fn n" href="./pages/author/<?php echo $item->{'metadata.creator.id'}; ?>"
                                title="View all pages by <?php echo $item->{'metadata.creator.name'}; ?>" rel="author"><?php echo $item->{'metadata.creator.name'}; ?></a>
                        </span>
                    </span>
                    /
                    <span class="comments-link">
                        <a href="<?php echo $item->_url; ?>#respond" title="Comment on <?php echo $item->{'title'}; ?>">0 comments</a>
                    </span>
                    
                    <?php if (!empty($item->{'tags'})) { ?>
                    <p class="tag-links">
                        <?php foreach ($item->{'tags'} as $tag) { ?>
                        <a class="label label-info" href="./pages/tag/<?php echo $tag; ?>" rel="tag"><?php echo $tag; ?></a>
                        <?php } ?>
                    </p>
                    <?php } ?>
                </div>
            </div>

            <div class="entry-description">
                <?php echo $item->{'copy'}; ?>
            </div>

            <div class="entry-meta">
                
                <?php if (!empty($item->{'categories'})) { ?>
                <p class="cat-links">
                    <?php foreach ($item->{'categories'} as $category) { ?>
                    <a class="label label-primary" href="./pages/category/<?php echo $category['slug']; ?>"
                        title="View all pages in <?php echo $category['title']; ?>" rel="category tag"><?php echo $category['title']; ?></a>
                    <?php } ?>
                </p>
                <?php } ?>

            </div>
            
            <hr />
            
        </article>
        <?php } ?>
        
        <?php } else { ?>
            
                <div class="">No items found.</div>
            
        <?php } ?>

    
    </div>

    <div class="dt-row dt-bottom-row">
        <div class="row">
            <div class="col-sm-10">
                <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                    <?php echo $paginated->serve(); ?>
                <?php } ?>
            </div>
            <div class="col-sm-2">
                <div class="datatable-results-count pull-right">
                    <span class="pagination">
                        <?php echo (!empty($paginated->total_pages)) ? $paginated->getResultsCounter() : null; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

</div>