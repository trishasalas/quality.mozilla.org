<?php 
$events_cat = get_category_by_slug('events')->cat_ID;
get_header(); ?>

<div id="content-main" class="hfeed" role="main">
<?php if (have_posts()) : while (have_posts()) : the_post(); // The Loop ?>

  <div id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
    <h1 class="entry-title section-title"><?php the_title(); ?></h1>

  <?php if (in_category($events_cat)) : ?>
    <div class="entry-meta">
      <p class="event-flag">Event</p>
      <p class="vcard">Posted by <a class="fn url author" title="See all <?php the_author_posts() ?> posts by <?php the_author(); ?>" href="<?php echo get_author_posts_url($authordata->ID, $authordata->user_nicename); ?>"><?php the_author(); ?></a> 
      on <?php the_time(get_option('date_format')); ?> at <abbr class="updated" title="<?php the_time('Y-m-d\TH:i:sP'); ?>"><?php the_time(); ?></abbr>.
      <?php if ( current_user_can( 'edit_page', $post->ID ) ) : ?><span class="edit"><?php edit_post_link('Edit', '', ''); ?></span><?php endif; ?>
      </p>
    </div>
  <?php else : ?>
    <div class="entry-meta">
      <p class="entry-posted">
        <a class="posted-month" href="<?php echo get_month_link(get_the_time('Y'), get_the_time('m')); ?>" title="See all posts from <?php echo get_the_time('F, Y'); ?>"><?php the_time('M'); ?></a>
        <span class="posted-date"><?php the_time('j'); ?></span>
        <span class="posted-year"><?php the_time('Y'); ?></span>
        <abbr class="updated" title="<?php the_time('Y-m-d\TH:i:sP'); ?>"><?php the_time(); ?></abbr>
      </p>
      <p class="vcard"><?php _e('Posted by','qmo') ?> <a class="fn url author" title="See all <?php the_author_posts(); ?> posts by <?php the_author(); ?>" href="<?php echo get_author_posts_url($authordata->ID, $authordata->user_nicename); ?>"><?php the_author(); ?></a> 
      in <?php the_category(', ', ''); ?>.
      <?php if ( current_user_can( 'edit_page', $post->ID ) ) : ?><span class="edit"><?php edit_post_link(__('Edit','qmo'), '', ''); ?></span><?php endif; ?>
      </p>
    </div>
  <?php endif; ?>

    <?php if (function_exists('the_post_thumbnail') && has_post_thumbnail() ) : ?>
      <p class="main-image"><?php the_post_thumbnail('thumbnail', array('alt' => "", 'title' => "")); ?></p>
    <?php endif; ?>

    <div class="entry-content">
      <?php the_content(__('Read more&hellip;', 'qmo')); ?>
      <?php wp_link_pages(array('before' => '<p class="pages"><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'next', 'link_before' => '<b>', 'link_after' => '</b>')); ?>
    </div>
    
    <?php if (get_the_tags()) : ?>
      <?php the_tags('<p class="entry-tags"><strong>'.__('Tags:','qmo').'</strong> ',', ',''); ?>
    <?php endif; ?>
  </div><!-- /post -->

  <?php endwhile; ?>
  
  <?php comments_template(); ?>
  
  <?php else : // if there are no posts ?>

  <h1 class="page-title"><?php _e('Sorry, there&#8217;s nothing to see here.','qmo'); ?></h1>

<?php endif; ?>

</div><?php /* end #content-main */ ?>

<div id="content-sub">
  <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('sidebar-home') ) : else : endif; ?>
</div>

<?php get_footer(); ?>