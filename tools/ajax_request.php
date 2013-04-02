<?php 

defined('C5_EXECUTE') or die("Access Denied.");
 

// Get PageList filters from query
$parentCID = intval( $_GET['parent_page_id'] );	    // Filter by parent ID
$collectionTypeHandle = $_GET['page_type'];	    // Filter by page type


// Get Pagination filters from query
$page = intval( $_GET['ccm_paging_p'] );	    // Requested page of results
$page = empty($page) ? 1 : $page;		    // Paginator returns this page of results

// Instantiate PageList object
Loader::model('page_list');
$pl = new PageList();
$pl->sortByName();

// Apply filters
if (!empty($collectionTypeHandle)) {
    $pl->filterByCollectionTypeHandle($collectionTypeHandle);
}
if (!empty($parentCID)) {
    $pl->filterByParentID($parentCID);
}

// Set up pagination
$pl->setItemsPerPage(3);
if ($pl->getSummary()->pages > 1) {
    $paginator = $pl->getPagination();
    $paginator_links = $paginator->getPages();
}

// Retrieve pages
$pages = $pl->getPage($page);
 
// Output pages
$nh = Loader::helper('navigation');
foreach ($pages as $page) {
  echo '<article>';
  echo '<a href="' . $nh->getLinkToCollection($page) . '">' . htmlentities($page->getCollectionName()) . '</a>';  
  echo '</article>';
}

// Output pagination
if ( !empty($paginator_links) ) {
    echo '<div id="ajax-paginator" class="pagination">';
    echo $paginator_links;
    echo '</div>';
}

exit;