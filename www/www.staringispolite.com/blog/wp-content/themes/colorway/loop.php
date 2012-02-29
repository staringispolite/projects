<ul class="blog_post">
  <!-- Start the Loop. -->
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
    <a href="<?php the_permalink(); ?>">
    <?php the_post_thumbnail( 'post_thumbnail', array('class' => 'postimg'  )); ?>
    </a>
    <?php
			} else {
			   echo inkthemes_main_image();
			} 
		  ?>
    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
    Posted on
    <?php the_time('F j, Y'); ?>
    by
    <?php the_author_posts_link() ?>
    in
    <?php the_category(', '); ?>
    <?php the_excerpt(); ?>
    <div class="clear"></div>
    <div class="tags">
      <?php the_tags('Post Tagged with ',', ',''); ?>
    </div>
    <div class="clear"></div>
    <?php comments_popup_link('No Comments.', '1 Comment.', '% Comments.'); ?>
    <div class="clear"></div>
    <a href="<?php the_permalink() ?>">Continue Reading...</a> </li>
  <!-- End the Loop. -->
  <?php endwhile; else: ?>
  <li>
    <p> <?php echo ('Sorry, no posts matched your criteria.'); ?> </p>
  </li>
  <?php endif; ?>
</ul>
