<?php
/*
/**
 * The main front page file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query. 
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Colorway
 * @since Colorway 1.0
 */
?>
<?php get_header(); ?>
<!--Start Slider-->
<div class="grid_24 slider">
  <div class="slider-container">
    <div id="slides">
      <div id="slide-box">
        <div class="slides_container col-full" >
         
          <div class="slide slide-1" >
            <div class="slide-content entry fl">
			 <?php if ( inkthemes_get_option('colorway_slideheading1') !='' ) {?>
              <h2 class="title"><a href="<?php echo inkthemes_get_option('colorway_slidelink1'); ?>"><?php echo inkthemes_get_option('colorway_slideheading1'); ?></a></h2>
			  <?php } else { ?>
			  <h2 class="title"><a href="#">Beauty at its best</a></h2>
			   <?php } ?> 
			    <?php if ( inkthemes_get_option('colorway_slidedescription1') !='' ) {?>
              <p><?php echo inkthemes_get_option('colorway_slidedescription1'); ?></p>
			   <?php } else { ?>
			 <p>What happens when beauty and simplicity connects. We tried to give you a slight hint of that with the Colorway Theme.</p>
			   <?php } ?>
            </div>
            <!-- /.slide-content -->
			<?php if ( inkthemes_get_option('colorway_slideimage1') !='' ) {?>
            <div class="slide-image fl"><img  src="<?php echo inkthemes_get_option('colorway_slideimage1'); ?>" class="slide-img" alt="Slide 1"/> </div>
			 <?php } else { ?>
			 <div class="slide-image fl"><img  src="<?php echo get_template_directory_uri(); ?>/images/slide-img-1.jpg" class="slide-img" alt="Slide 1"/> </div>
			 <?php } ?>
            <!-- /.slide-image -->
            <div class="fix"></div>
          </div>         
        </div>
        <!-- /.slides_container -->
      </div>
      <!-- /#slide-box -->
    </div>
    <!-- /#slides -->
  </div>
</div>
<div class="clear"></div>
<!--End Slider-->
<!--Start Content Grid-->
<div class="grid_24 content">
  <div class="content-wrapper">
    <div class="content-info">
      <h1>
        <center>
          <?php if ( inkthemes_get_option('inkthemes_mainheading') !='' ) {?>
          <?php echo inkthemes_get_option('inkthemes_mainheading'); ?>
          <?php } else {?>
          Your Main Heading Comes Here! Set It From Themes Options Panel
          <?php } ?>
        </center>
      </h1>
    </div>
    <div  id="content">
      <div class="columns">
        <div class="one_fourth"> <a href="<?php echo inkthemes_get_option('inkthemes_link1'); ?>" class="bigthumbs">
          <?php if ( inkthemes_get_option('inkthemes_fimg1') !='' ) {?>
          <img src="<?php echo inkthemes_get_option('inkthemes_fimg1'); ?>"/>
          <?php } else { ?>
          <img src="<?php echo get_template_directory_uri(); ?>/images/1.jpg"/>
          <?php } ?>
          </a>
          <?php if ( inkthemes_get_option('inkthemes_headline1') !='' ) {?>
          <h3><a href="<?php echo inkthemes_get_option('inkthemes_link1'); ?>"><?php echo inkthemes_get_option('inkthemes_headline1'); ?></a></h3>
          <?php } else { ?>
          <h3><a href="#">Power of Easiness</a></h3>
          <?php } ?>
          <?php if ( inkthemes_get_option('inkthemes_feature1') !='' ) { ?>
          <p><?php echo inkthemes_get_option('inkthemes_feature1'); ?></p>
          <?php } else { ?>
          <p>This Colorway Wordpress Theme gives you the easiness of building your site without any coding skills required.</p>
          <?php } ?>
        </div>
        <div class="one_fourth"> <a href="<?php echo inkthemes_get_option('inkthemes_link2'); ?>" class="bigthumbs">
          <?php if ( inkthemes_get_option('inkthemes_fimg2') !='' ) {?>
          <img src="<?php echo inkthemes_get_option('inkthemes_fimg2'); ?>"/>
          <?php } else { ?>
          <img src="<?php echo get_template_directory_uri(); ?>/images/2.jpg"/>
          <?php } ?>
          </a>
          <?php if ( inkthemes_get_option('inkthemes_headline2') !='' ) {?>
          <h3><a href="<?php echo inkthemes_get_option('inkthemes_link2'); ?>"><?php echo inkthemes_get_option('inkthemes_headline2'); ?></a></h3>
          <?php } else { ?>
          <h3><a href="#">Power of Speed</a></h3>
          <?php } ?>
          <?php if ( inkthemes_get_option('inkthemes_feature2') !='' ) { ?>
          <p><?php echo inkthemes_get_option('inkthemes_feature2'); ?></p>
          <?php } else { ?>
          <p>The Colorway Wordpress Theme is highly optimized for Speed. So that your website opens faster than any similar themes.</p>
          <?php } ?>
        </div>
        <div class="one_fourth"> <a href="<?php echo inkthemes_get_option('inkthemes_link3'); ?>" class="bigthumbs">
          <?php if ( inkthemes_get_option('inkthemes_fimg3') !='' ) {?>
          <img src="<?php echo inkthemes_get_option('inkthemes_fimg3'); ?>"/>
          <?php } else { ?>
          <img src="<?php echo get_template_directory_uri(); ?>/images/3.jpg"/>
          <?php } ?>
          </a>
          <?php if ( inkthemes_get_option('inkthemes_headline3') !='' ) {?>
          <h3><a href="<?php echo inkthemes_get_option('inkthemes_link3'); ?>"><?php echo inkthemes_get_option('inkthemes_headline3'); ?></a></h3>
          <?php } else { ?>
          <h3><a href="#">Power of SEO</a></h3>
          <?php } ?>
          <?php if ( inkthemes_get_option('inkthemes_feature3') !='' ) { ?>
          <p><?php echo inkthemes_get_option('inkthemes_feature3'); ?></p>
          <?php } else { ?>
          <p>Visitors to the Website are very highly desirable. With the SEO Optimized Themes, You get more traffic from Google.</p>
          <?php } ?>
        </div>
        <div class="one_fourth last"> <a href="<?php echo inkthemes_get_option('inkthemes_link4'); ?>" class="bigthumbs">
          <?php if ( inkthemes_get_option('inkthemes_fimg4') !='' ) {?>
          <img src="<?php echo inkthemes_get_option('inkthemes_fimg4'); ?>"/>
          <?php } else { ?>
          <img src="<?php echo get_template_directory_uri(); ?>/images/4.jpg"/>
          <?php } ?>
          </a>
          <?php if ( inkthemes_get_option('inkthemes_headline4') !='' ) {?>
          <h3><a href="<?php echo inkthemes_get_option('inkthemes_link4'); ?>"><?php echo inkthemes_get_option('inkthemes_headline4'); ?></a></h3>
          <?php } else { ?>
          <h3><a href="#">Ready Contact Form</a></h3>
          <?php } ?>
          <?php if ( inkthemes_get_option('inkthemes_feature4') !='' ) { ?>
          <p><?php echo inkthemes_get_option('inkthemes_feature4'); ?></p>
          <?php } else { ?>
          <p>Let your visitors easily contact you. The builtin readymade contact form makes it easier for clients to contact.</p>
          <?php } ?>
        </div>
      </div>
      <div class="clear"></div>
    </div>
    <?php if ( inkthemes_get_option('inkthemes_testimonial') !='' ) {?>
    <blockquote><?php echo inkthemes_get_option('inkthemes_testimonial'); ?></blockquote>
    <?php }  else{?>
    <blockquote>Theme from InkThemes.com are based on P3+ Technology, giving high speed, easiness to built &amp; power of SEO for lending trustworthiness and experience to a customer. The Themes are really one of the best we saw everywhere.<br />
      - Neeraj Agarwal</blockquote>
    <?php }?>
  </div>
</div>
<div class="clear"></div>
<!--End Content Grid-->
</div>
<!--End Container Div-->
<?php get_footer(); ?>
