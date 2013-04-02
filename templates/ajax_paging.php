<?php
defined('C5_EXECUTE') or die("Access Denied.");

$bt = BlockType::getByHandle('page_list');
$ajax_request_url = Loader::helper('concrete/urls')->getBlockTypeToolsURL($bt).'/ajax_request';

?>

<div class="ccm-page-list">

    <div id="ajax-pages"></div>

</div>

<script>
$(document).ready(function(ev) {
    $('#ajax-pages').text('Loading...').load(<?=$ajax_request_url?>);

    $('#ajax-paginator a').live('click', function(ev) {
        ev.preventDefault();
        var link_href = $(this).attr('href');

        $('#ajax-pages').text('Loading...').load(link_href);

        return false;
    });
});
</script>