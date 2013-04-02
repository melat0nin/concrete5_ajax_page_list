<?php
defined('C5_EXECUTE') or die("Access Denied.");

// Vars
$c_id = Page::getCurrentPage()->getCollectionID();
$rssUrl = $showRss ? $controller->getRssUrl($b) : '';

// Helpers
$th = Loader::helper('text');

// Get block tools URL for ajax request script
$bt = BlockType::getByHandle('page_list');
$block_tools_dir = Loader::helper('concrete/urls')->getBlockTypeToolsURL($bt);
$ajax_request_url = $block_tools_dir . '/ajax_request';

/*
 *  Add filters to query string according to block parameters set by user
 */
$ajax_request_url .= '?bID=' . $controller->bID;			    // # of results per page
$ajax_request_url .= '&num=' . $controller->num;			    // # of results per page
$ajax_request_url .= '&cParentID=' . $controller->cParentID;		    // Parent page ID
$ajax_request_url .= '&cThis=' . $controller->cThis;			    // Filter beneath this page
$ajax_request_url .= '&orderBy=' . $controller->orderBy;		    // Order results
$ajax_request_url .= '&ctID=' . $controller->ctID;			    // Page type ID
//$ajax_request_url .= '&rss=' . $controller->rss;			    // RSS
$ajax_request_url .= '&displayAliases=' . $controller->displayAliases;	    // Display aliases
$ajax_request_url .= '&displayFeaturedOnly=' . $controller->displayFeaturedOnly;	    // Display aliases
$ajax_request_url .= '&cID=' . $controller->cID;					    // Current page's ID
$ajax_request_url .= '&truncateSummaries=' . $controller->truncateSummaries;		    // Truncate page descriptions
$ajax_request_url .= '&truncateChars=' . $controller->truncateChars;		    // # of description chars to display

?>

<div class="ccm-page-list">
    <div id="ajax-pages"></div>
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
		'background' : 'url(<?=$this->getBlockURL()?>/loading.gif) 0 0 no-repeat'
	    });
	    $('#ajax-pages').load(link_href, function() {
		$('#ajax-article-list').css('min-height', ajaxHeight).fadeTo('fast',1).parent().css('background', 'none');
	    });
	});
	
        return false;
    });
});
</script>