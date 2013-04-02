<?php
defined('C5_EXECUTE') or die("Access Denied.");

// Vars
$c_id = Page::getCurrentPage()->getCollectionID();

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
?>

<div class="ccm-page-list">
    <div id="ajax-pages"></div>
</div>

<script>
$(document).ready(function() {
    $('#ajax-pages').html('<img src="<?=$this->getBlockURL()?>/loading.gif" alt="Loading pages..." />').load('<?=$ajax_request_url?>');

    $('#ajax-paginator a').live('click', function(ev) {
        ev.preventDefault();
        var link_href = $(this).attr('href');

        $('#ajax-pages').html('<img src="<?=$this->getBlockURL()?>/loading.gif" alt="Loading pages..." />').load(link_href);

        return false;
    });
});
</script>