<?php
defined('C5_EXECUTE') or die("Access Denied.");

/**
 * Filter by attribute
 * -------------------
 *
 * Enter the handles for select page attributes you'd like to filter by below and these
 * will be displayed above the Page List as a list of links which can be clicked to apply filters
 * e.g. $filter_attributes = array("my_attribute_1", "my_attribute_2");
 **/
$filter_attributes = array();


// Vars
$c_id = Page::getCurrentPage()->getCollectionID();
$rssUrl = $controller->rss ? $controller->getRssUrl($b) : '';

// Helpers
$uh = Loader::helper('concrete/urls');

// Get block tools URL for ajax request script
$ajax_request_url = $uh->getToolsURL('ajax_request', 'ajax_page_list');

/*
 *  Add filters to query string according to block parameters set by user
 */
$ajax_request_url .= '?bID=' . $controller->bID;			    // # of results per page
$ajax_request_url .= '&num=' . $controller->num;			    // # of results per page
$ajax_request_url .= '&cParentID=' . $controller->cParentID;		    // Parent page ID
$ajax_request_url .= '&cThis=' . $controller->cThis;			    // Filter beneath this page boolean
$ajax_request_url .= '&orderBy=' . $controller->orderBy;		    // Order results
$ajax_request_url .= '&ctID=' . $controller->ctID;			    // Page type ID
$ajax_request_url .= '&displayAliases=' . $controller->displayAliases;	    // Display aliases boolean
$ajax_request_url .= '&displayFeaturedOnly=' . $controller->displayFeaturedOnly;	    // Display is_featured pages only boolean
$ajax_request_url .= '&cID=' . $controller->cID;					    // Current page's ID
$ajax_request_url .= '&truncateSummaries=' . $controller->truncateSummaries;		    // Truncate page descriptions boolean
$ajax_request_url .= '&truncateChars=' . $controller->truncateChars;		    // # of description chars to display
$ajax_request_url .= '&paginate=' . $controller->paginate;		    // Whether to paginate or not

// Custom select filters
if ( count($filter_attributes) > 0 ) {
    foreach ($filter_attributes as $handle) {
	$ajax_request_url .= '&displayAttributes[]=' . $handle;
    }
}
?>

<div class="ccm-page-list">

    <div id="ajax-pages"></div>

    <?php  if ($controller->rss): ?>
	    <div class="ccm-page-list-rss-icon">
		<a href="<?php  echo $rssUrl ?>" target="_blank"><img src="<?php  echo $rssIconSrc ?>" width="14" height="14" alt="<?php  echo t('RSS Icon') ?>" title="<?php  echo t('RSS Feed') ?>" /></a>
	    </div>
	    <link href="<?php  echo BASE_URL.$rssUrl ?>" rel="alternate" type="application/rss+xml" title="<?php  echo $controller->rssTitle; ?>" />
    <?php  endif; ?>

</div>

<script>
$(document).ready(function() {
    var ajaxHeight;

    $('#ajax-pages')
	.load('<?php echo $ajax_request_url ?>', function() {
	    ajaxHeight = $('#ajax-article-list').height();
	    $('#ajax-article-list').css('min-height', ajaxHeight).css('opacity',1);
	    $('#ajax-paginator a').each(function() {
		$(this).attr({
		    'data-href' : $(this).attr('href'),
		    'href' : 'javascript:;'
		});
	    });
    });

    $('#ajax-paginator a, .page-list-filter a').live('click', function(ev) {
	ev.preventDefault();
	
	//if category is already selected don't load again // return
	if($(this).hasClass('selected') ){
		return;
	}
	var link_href = $(this).attr('data-href').replace(/\s/g,"%20");
	
	$('#ajax-article-list').fadeTo('fast', 0, function() {
	    $('.page-list-filters').css({'background': 'url(<?php echo DIR_REL ?>/packages/ajax_page_list/loading.gif) 0 bottom no-repeat'});
	    $('#ajax-pages').load(link_href, function() {
		$('#ajax-article-list').css('min-height', ajaxHeight).fadeTo('fast',1).parent().css('background', 'none');
		$('#ajax-paginator a').each(function() {
		    $(this).attr({
			'data-href' : $(this).attr('href'),
			'href' : 'javascript:;'
		    });
		});
	    });
	});

	return false;
    });
});
</script>
