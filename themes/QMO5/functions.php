<?php
/*********
* Load various JavaScripts
*/
function qmo_load_scripts() {
  // Load the default jQuery
  wp_enqueue_script('jquery');

  // Load the threaded comment reply script
  if ( get_option('thread_comments') && is_singular() ) {
    wp_enqueue_script( 'comment-reply' );
  }
  
  // Check required fields on comment form
  wp_register_script( 'checkcomments', get_template_directory_uri() . '/js/fc-checkcomment.js' );
  if ( get_option('require_name_email') && is_singular() ) {
    wp_enqueue_script('checkcomments');
  }
}
add_action( 'wp_enqueue_scripts', 'qmo_load_scripts' );


/*********
* Allow uploading some additional MIME types
*/
function fc_add_mimes( $mimes=array() ) {
  $mimes['ogv'] = 'video/ogg';
  $mimes['mp4'] = 'video/mp4';
  $mimes['m4v'] = 'video/mp4';
  $mimes['flv'] = 'video/x-flv';
  return $mimes;
}
add_filter('upload_mimes', 'fc_add_mimes');


/*********
* Customize the login screen
*/
function fc_custom_login() {
  echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_directory') . '/css/login.css">';
}
add_action('login_head', 'fc_custom_login');


/*********
* Activate post thumbnails
*/
add_theme_support( 'post-thumbnails' );


/*********
* Thumbnail size for small Team icons
*/
add_image_size( 'team-icon-small', 80, 80, true );


/*********
* Add default posts and comments RSS feed links to head
*/
add_theme_support( 'automatic-feed-links' );


/*********
* Remove WP version from head
*/
remove_action('wp_head', 'wp_generator');


/*********
 * Kill the WordPress admin bar (new in 3.1)
 */
add_filter( 'show_admin_bar', '__return_false' );


/*********
* Style the visual editor to match the theme styles
*/
function my_editor_style($url) {
  if ( !empty($url) ) {
    $url .= ',';
  }
  $url .= trailingslashit( get_stylesheet_directory_uri() ) . '/css/editor-style.css';
  return $url;
}
add_filter('mce_css', 'my_editor_style');


/*********
* Add more-links to excerpts
*/
function qmo_excerpt_more($post) {
  return '&hellip; <a class="more-link" href="'.get_permalink($post->ID).'" title="Read the rest of &#8220;'.get_the_title_rss($post->ID).'&#8221;">'.'Read more'.'</a>';
}
add_filter('excerpt_more', 'qmo_excerpt_more');


/*********
* Make theme available for translation
* Translations can be filed in the /languages/ directory
*/
load_theme_textdomain( 'qmo', TEMPLATEPATH . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) ) {
  require_once( $locale_file );
}


/*********
* Register widget areas
*/
if ( function_exists('register_sidebars') ) :

  /** Home page */
  register_sidebar(array(
  'name' => 'Home/News Sidebar',
  'id' => 'sidebar-home',
  'description' => 'Shown on the QMO home page (featuring QMO news and events).',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div>'
  ));

  /** Teams index page */
  register_sidebar(array(
  'name' => 'Teams Index Sidebar',
  'id' => 'sidebar-teams',
  'description' => 'Shown on the Teams index page.',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div>'
  ));

  /** Team pages */
  register_sidebar(array(
  'name' => 'Team Page Sidebar',
  'id' => 'sidebar-team-single',
  'description' => 'Shown on individual Team pages. Use Widget Logic to match specific widgets to specific teams.',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div>'
  ));

  /** Community page */
  register_sidebar(array(
  'name' => 'Community Sidebar',
  'id' => 'sidebar-community',
  'description' => 'Shown on Community pages.',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div>'
  ));

  /** Event post */
  register_sidebar(array(
  'name' => 'Events Sidebar',
  'id' => 'sidebar-event',
  'description' => 'Shown on the events list, calendar, and the details page for a single event.',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div>'
  ));

  /** Doc landing page */
  register_sidebar(array(
  'name' => 'Main Docs Landing Sidebar',
  'id' => 'sidebar-docs-landing',
  'description' => 'Shown on the main Docs page.',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div>'
  ));

  /** Doc section pages */
  register_sidebar(array(
  'name' => 'Docs Section Overview Sidebar',
  'id' => 'sidebar-docs-section',
  'description' => 'Shown on section overview pages within Docs.',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div>'
  ));

  /** Single doc pages */
  register_sidebar(array(
  'name' => 'Single Doc Sidebar',
  'id' => 'sidebar-doc-single',
  'description' => 'Shown on individual Doc pages. You can use Widget Logic to match specific widgets to specific docs.',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div>'
  ));

  /** Archive pages */
  register_sidebar(array(
  'name' => 'Archive Page Sidebar',
  'id' => 'sidebar-archive',
  'description' => 'Shown on blog archive pages (category, date, author, tag).',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div>'
  ));

  /** Search results */
  register_sidebar(array(
  'name' => 'Search Results Sidebar',
  'id' => 'sidebar-search',
  'description' => 'Shown on search results.',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div>'
  ));

endif;


/*********
* Prints the page number currently being browsed, with a pipe before it.
* Used in header.php to add the page number to the <title>.
*/
if ( !function_exists( 'qmo_page_number' ) ) :
function qmo_page_number() {
  global $paged; // Contains page number.
  if ( $paged >= 2 ) {
    echo ' | ' . sprintf( __( 'Page %s' , 'qmo' ), $paged );
  }
}
endif;

/*********
* Enable excerpts for Pages
*/
add_post_type_support( 'page', 'excerpt' );

/*********
* Use auto-excerpts for meta description if hand-crafted exerpt is missing
*/
function fc_meta_desc() {
	$post_desc_length  = 25; // auto-excerpt length in number of words

	global $cat, $cache_categories, $wp_query, $wp_version;
	if(is_single() || is_page()) {
		$post = $wp_query->post;
		$post_custom = get_post_custom($post->ID);

    if(!empty($post->post_excerpt)) {
			$text = $post->post_excerpt;
		} else {
			$text = $post->post_content;
		}
		$text = str_replace(array("\r\n", "\r", "\n", "  "), " ", $text);
		$text = str_replace(array("\""), "", $text);
		$text = trim(strip_tags($text));
		$text = explode(' ', $text);
		if(count($text) > $post_desc_length) {
			$l = $post_desc_length;
			$ellipsis = '...';
		} else {
			$l = count($text);
			$ellipsis = '';
		}
		$description = '';
		for ($i=0; $i<$l; $i++)
			$description .= $text[$i] . ' ';

		$description .= $ellipsis;
	}
	elseif(is_category()) {
	  $category = $wp_query->get_queried_object();
	  if (!empty($category->category_description)) {
	    $description = trim(strip_tags($category->category_description));
	  } else {
	    $description = single_cat_title('Articles posted in ');
	  }
  }
	else {
		$description = trim(strip_tags(get_bloginfo('description')));
	}

	if($description) {
		echo $description;
	}
}


/*********
* Comment Template for QMO theme
*/
if ( ! function_exists( 'qmo_comment' ) ) :
function qmo_comment($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
  $comment_type = get_comment_type();
?>

 <li id="comment-<?php comment_ID(); ?>" <?php comment_class('hentry'); ?>>
  <?php if ( $comment_type == 'trackback' ) : ?>
    <h2 class="entry-title"><?php _e( 'Trackback from ', 'qmo' ); ?> <cite><?php comment_author_link(); ?></cite>
      <span class="comment-meta">on <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>" rel="bookmark" title=" <?php _e('Permanent link to this comment by ','qmo'); comment_author(); ?>"><time class="published" pubdate datetime="<?php comment_date('Y-m-d'); ?>"><?php comment_date('F jS, Y'); ?></time> at <?php comment_time(); ?></a>:</span>
    </h2>
  <?php elseif ( $comment_type == 'pingback' ) : ?>
    <h2 class="entry-title"><?php _e( 'Pingback from ', 'qmo' ); ?> <cite><?php comment_author_link(); ?></cite>
      <span class="comment-meta">on <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>" rel="bookmark" title="<?php _e('Permanent link to this comment by ','qmo'); comment_author(); ?>"><time class="published" pubdate datetime="<?php comment_date('Y-m-d'); ?>"><?php comment_date('F jS, Y'); ?></time> at <?php comment_time(); ?></a>:</span>
    </h2>
  <?php else : ?>
    <?php if ( ( $comment->comment_author_url != "http://" ) && ( $comment->comment_author_url != "" ) ) : // if author has a link ?>
     <h2 class="entry-title vcard">
       <a href="<?php comment_author_url(); ?>" class="url" rel="nofollow external" title="<?php comment_author_url(); ?>">
         <?php if (function_exists('get_avatar')) : echo ('<span class="photo">'.get_avatar( $comment, 48 ).'</span>'); endif; ?>
         <cite class="author fn"><?php comment_author(); ?></cite>
       </a>
       <span class="comment-meta">wrote on <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>" rel="bookmark" title="<?php _e('Permanent link to this comment by ','qmo'); comment_author(); ?>"><time class="published" pubdate datetime="<?php comment_date('Y-m-d'); ?>"><?php comment_date('F jS, Y'); ?></time> at <?php comment_time(); ?></a>:</span>
     </h2>
    <?php else : // author has no link ?>
      <h2 class="entry-title vcard">
        <?php if (function_exists('get_avatar')) : echo ('<span class="photo">'.get_avatar( $comment, 48 ).'</span>'); endif; ?>
        <cite class="author fn"><?php comment_author(); ?></cite>
        <span class="comment-meta">wrote on <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>" rel="bookmark" title="<?php _e('Permanent link to this comment by ','qmo'); comment_author(); ?>"><time class="published" pubdate datetime="<?php comment_date('Y-m-d'); ?>"><?php comment_date('F jS, Y'); ?></time> at <?php comment_time(); ?></a>:</span>
      </h2>
    <?php endif; ?>
  <?php endif; ?>

    <?php if ($comment->comment_approved == '0') : ?>
      <p class="mod"><strong><?php _e('Your comment is awaiting moderation.'); ?></strong></p>
    <?php endif; ?>

    <blockquote class="entry-content">
      <?php comment_text(); ?>
    </blockquote>

  <?php if ( (get_option('thread_comments') == true) || (current_user_can('edit_post', $comment->comment_post_ID)) ) : ?>
    <p class="comment-util"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?> <?php if ( current_user_can('edit_post', $comment->comment_post_ID) ) : ?><span class="edit">[<?php edit_comment_link(__('Edit Comment','qmo'),'',''); ?>]</span><?php endif; ?></p>
  <?php endif; ?>
<?php
} /* end qmo_comment */
endif;


/*********
* Removes the embedded style when adding galleries to posts
*/
add_filter('gallery_style', create_function('$a', 'return preg_replace("%<style type=\'text/css\'>(.*?)</style>%s", "", $a);'));


/*********
* Removes the default styles that are packaged with the Recent Comments widget.
*/
function qmo_remove_recent_comments_style() {
  global $wp_widget_factory;
  remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'qmo_remove_recent_comments_style' );


/**********
* Determine if the page is paged and should show posts navigation
*/
function fc_show_posts_nav() {
 global $wp_query;
 return ($wp_query->max_num_pages > 1) ? TRUE : FALSE;
}


/*********
* Determine if a previous post exists (i.e. that this isn't the first one)
*
* @param bool $in_same_cat Optional. Whether link should be in same category.
* @param string $excluded_categories Optional. Excluded categories IDs.
*/
function fc_previous_post($in_same_cat = false, $excluded_categories = '') {
  if ( is_attachment() )
    $post = & get_post($GLOBALS['post']->post_parent);
  else
    $post = get_previous_post($in_same_cat, $excluded_categories);
  if ( !$post )
    return false;
  else
    return true;
}


/*********
* Determine if a next post exists (i.e. that this isn't the last post)
*
* @param bool $in_same_cat Optional. Whether link should be in same category.
* @param string $excluded_categories Optional. Excluded categories IDs.
*/
function fc_next_post($in_same_cat = false, $excluded_categories = '') {
  $post = get_next_post($in_same_cat, $excluded_categories);
  if ( !$post )
    return false;
  else
    return true;
}


/*********
* Determines if the current page is the result of paged comments.
* This lets us prevent search engines from indexing lots of duplicate pages
* (since the post is repeated on every paged comment page).
*/
function is_comments_paged_url() {
  $pos = strpos($_SERVER['REQUEST_URI'], "comment-page");
  if ($pos === false) { return false; }
  else { return true; }
}


/*********
* Tests if any of a post's assigned categories are descendants of target categories
*
* @param int|array $cats The target categories. Integer ID or array of integer IDs
* @param int|object $_post The post. Omit to test the current post in the Loop or main query
* @return bool True if at least 1 of the post's categories is a descendant of any of the target categories
* @see get_term_by() You can get a category by name or slug, then pass ID to this function
* @uses get_term_children() Passes $cats
* @uses in_category() Passes $_post (can be empty)
* @version 2.7
* @link http://codex.wordpress.org/Function_Reference/in_category#Testing_if_a_post_is_in_a_descendant_category
*/
function fc_in_descendant_category( $cats, $_post = null ) {
  foreach ( (array) $cats as $cat ) {
    $descendants = get_term_children( (int) $cat, 'category'); // get_term_children() accepts integer ID only
    if ( $descendants && in_category( $descendants, $_post ) )
      return true;
  }
  return false;
}


/*********
* Determines if a page is the child of another page
*/
function fc_is_subpage() {
  global $post;
  if ( is_page() && $post->post_parent ) {
    $parentID = $post->post_parent;
    return $parentID;
  }
  else {
    return false;
  };
};


/*********
 * Tests whether the current post is a child of the given parent post. By
 * default it will test parent pages up to the root but this can be
 * disabled by setting depthTest to false.
 *
 * Originally by Christian Schenk http://www.christianschenk.org/blog/wordpress-is_child-function/
 * Embedded because the theme requires this function (disabling the plugin would break the theme)
 * Renamed to avoid conflicts.
 */
if (!function_exists('fc_is_child')) :

  function fc_is_child($parent, $depthTest = true) {
    if (empty($parent)) return false;

    $id = $parent;
    if (!is_numeric($id))
      $id = fc_get_parent_by_name($id);
    if (empty($id)) return false;

    global $post;
    if ($post->post_parent == $id)
      return true;

    if ($depthTest) {
      $parents = fc_get_all_parents($post->post_parent);
      return in_array($id, $parents);
    }

    return false;
  }

  // Returns the parent page for a given page.
  function fc_get_parent($id) {
    global $wpdb;
    return $wpdb->get_var('SELECT post_parent FROM '.$wpdb->posts.' WHERE ID = "'.$id.'"');
  }

   // In case the user supplied a post/page by name we'll retrieve the ID using this function.
  function fc_get_parent_by_name($name) {
    global $wpdb;
    return $wpdb->get_var('SELECT ID FROM '.$wpdb->posts.' WHERE post_name = "'.$name.'"');
  }

  // Returns all parents up to the root node for the given page.
  function fc_get_all_parents($id) {
    $parents = array();
    $curId = $id;
    while ($curId != 0) {
      $curId = fc_get_parent($curId);
      if ($curId != 0)
        $parents[] = $curId;
    }
    return $parents;
  }
endif;


/*********
 * Add an excerpt field to Pages
 */
function fc_add_page_excerpt_box() {
  add_meta_box( 'fc_page_excerpt', __( 'Excerpt' ), 'fc_page_excerpt_box', 'page', 'normal', 'high' );
}

function fc_page_excerpt_box() {
  global $post;
  $message = __( 'Excerpts are optional hand-crafted summaries of your content. You can <a href="http://codex.wordpress.org/Template_Tags/the_excerpt" target="_blank">use them in your template</a>' );
  print <<<EOF
  <div class="inside">
  <textarea rows="1" cols="40" name="excerpt" tabindex="3" id="excerpt">{$post->post_excerpt}</textarea>
  <p>{$message}</p>
  </div>
EOF;
}
add_action( 'admin_menu', 'fc_add_page_excerpt_box' );


/*********
* Retrieves a specific post or page to display outside a loop
*/
function fc_get_post($id='GETPOST') {
  global $post, $wpdb;
  $table = $wpdb->posts;
  $now = current_time('mysql');
  $name_or_id = '';
  $orderby = 'post_date';

  if( !$id || 'GETPOST' == $id ) {
    $query_suffix = "post_type = 'post' AND post_status = 'publish'";
  }
  elseif('GETPAGE' == $id) {
    $query_suffix = "post_type = 'page' AND post_status = 'publish'";
  }
  elseif('GETSTICKY' == $id) {
    $table .= ', ' . $wpdb->postmeta;
    $query_suffix = "ID = post_id AND meta_key = 'sticky' AND meta_value = 1";
  }
  else {
    $query_suffix = "(post_status = 'publish' OR post_status = 'static')";
    if(is_numeric($id)) {
      $name_or_id = "ID = '$id' AND";
    }
    else {
      $name_or_id = "post_name = '$id' AND";
    }
  }

  $post = $wpdb->get_row("SELECT * FROM $table WHERE $name_or_id post_date <= '$now' AND $query_suffix ORDER BY $orderby DESC LIMIT 1");
  get_post_custom($post->ID);
  setup_postdata($post);
}



/**********
* Customize the feeds
*/
function qmo_feed_content( $content ) {
  global $post, $id;
  $blog_key = substr( md5( get_bloginfo('url') ), 0, 16 );
  if ( ! is_feed() ) return $content;

// Fetch the formats
  $date_format = get_option("date_format");
  $time_format = get_option("time_format");
  $datetime = $date_format.', '.$time_format;

// Fetch the custom fields ***
  $is_event = get_post_meta($post->ID, '_isEvent', true);
  $allday = get_post_meta( $post->ID, '_EventAllDay', true);
  $event_date = date ( $date_format, strtotime( get_post_meta( $post->ID, '_EventStartDate', true ) ) );
  $event_start = date ( $datetime, strtotime( get_post_meta( $post->ID, '_EventStartDate', true ) ) );
  $event_end = date( $datetime, strtotime( get_post_meta($post->ID, '_EventEndDate', true) ) );

// Display the content ***
  // If this is an event post, add the date
  if ( $is_event ) {
    // If it's an all-day event, only show the start date and no start time
    if ( $allday ) {
      $content = $content . '<p><strong>When:</strong> '.$event_date.'.</p>';
    }
    else {
      $content = $content . '<p><strong>When:</strong><br /> Starts: '.$event_start.'<br /> Ends: '.$event_end.'</p>';
    }
  }
  else {
    $content = $content;
  }
  return $content;
} // End function
add_filter('the_content', 'qmo_feed_content');


/*********
 * Create a special role for the Twitter Bot
 */
$botcando = array(
  'read' =>  true,
  'edit_posts' => true,
  'publish_posts' => true,
  'edit_published_posts' =>  true,
  'delete_posts' => true,
  'delete_published_posts' => true
);

remove_role('twitter_bot'); // remove it first to prevent duplicates, then add
add_role( 'twitter_bot', 'Twitter Bot', $botcando );

