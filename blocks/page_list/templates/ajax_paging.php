<?php
defined('C5_EXECUTE') or die("Access Denied.");

// Vars
$c_id = Page::getCurrentPage()->getCollectionID();
$rssUrl = $controller->rss ? $controller->getRssUrl($b) : '';

// Helpers
$uh = Loader::helper('concrete/urls');
$th = Loader::helper('text');

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

    $('#ajax-pages').load('<?=$ajax_request_url?>', function() {
	ajaxHeight = $('#ajax-article-list').height();
	$('#ajax-article-list').css('min-height', ajaxHeight).css('opacity',1);
    });

    $('#ajax-paginator a').live('click', function(ev) {
        ev.preventDefault();
        var link_href = $(this).attr('href');

	$('#ajax-article-list').fadeTo('fast', 0, function() {
	    $(this).parent().css({
		'background' : 'url(<?=DIR_REL?>/packages/ajax_page_list/loading.gif) 0 0 no-repeat'
	    });
	    $('#ajax-pages').load(link_href, function() {
		$('#ajax-article-list').css('min-height', ajaxHeight).fadeTo('fast',1).parent().css('background', 'none');
	    });
	});
	
        return false;
    });
});
</script>