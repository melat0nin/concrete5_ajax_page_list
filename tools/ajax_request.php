<?php 

defined('C5_EXECUTE') or die("Access Denied.");

// Helpers
$nh = Loader::helper('navigation');

// Instantiate PageList object
Loader::model('page_list');
$db = Loader::db();
$bID = $_GET['bID'];
if ($bID) {
    $q = "select num, cParentID, cThis, orderBy, ctID, displayAliases, rss, displayFeaturedOnly, includeAllDescendents from btPageList where bID = '$bID'";
    $r = $db->query($q);
    if ($r) {
	$row = $r->fetchRow();
    }
    $row['cID'] = $_GET['cID'];
} else {
    $row = $_GET;
}

$pl = new PageList();
$pl->setNameSpace('b' . $bID);

/*
 *  Apply filters
 */

// Sort results
switch($row['orderBy']) {
    case 'display_asc':
	$pl->sortByDisplayOrder();
	break;
    case 'display_desc':
	$pl->sortByDisplayOrderDescending();
	break;
    case 'chrono_asc':
	$pl->sortByPublicDate();
	break;
    case 'alpha_asc':
	$pl->sortByName();
	break;
    case 'alpha_desc':
	$pl->sortByNameDescending();
	break;
    default:
	$pl->sortByPublicDateDescending();
	break;
}

// Filter by is_featured attribute
if ($row['displayFeaturedOnly'] == 1) {	    
    Loader::model('attribute/categories/collection');
    $cak = CollectionAttributeKey::getByHandle('is_featured');
    if (is_object($cak)) {
	$pl->filterByIsFeatured(1);
    }
}

// Display page aliases
if (!$row['displayAliases']) {	    
    $pl->filterByIsAlias(0);
}
$pl->filter('cvName', '', '!=');

// Filter by page type ID
if ($row['ctID']) {	
    $pl->filterByCollectionTypeID($row['ctID']);
}

// Filter by exclude from page list
$columns = $db->MetaColumns(CollectionAttributeKey::getIndexedSearchTable());	
if (isset($columns['AK_EXCLUDE_PAGE_LIST'])) {
    $pl->filter(false, '(ak_exclude_page_list = 0 or ak_exclude_page_list is null)');
}

// Set parent page
if ( intval($row['cParentID']) != 0) {
    $c = Page::getCurrentPage();
    $cParentID = ($row['cThis']) ? $row['cID'] : $row['cParentID'];
    if ($row['includeAllDescendents']) {
	$pl->filterByPath(Page::getByID($cParentID)->getCollectionPath());
    } else {
	$pl->filterByParentID($cParentID);
    }
}

/*
 *  Set up pagination
 */
$num = (int) $row['num'];
$pl->setItemsPerPage($num);
$current_page_get_var = 'ccm_paging_p_b' . $bID;
$current_page = intval( $_GET[$current_page_get_var] );		// Requested page of results
$current_page = empty($current_page) ? 1 : $current_page;	// Paginator returns this page of results

if ($pl->getSummary()->pages > 1) {	// Retrieve pagination links ready for display
    $paginator = $pl->getPagination();
    $paginator_links = $paginator->getPages();
}

/*
 * Retrieve and output pages and pagination
 */
$pages = $pl->getPage($current_page);

echo '<div id="ajax-article-list" style="opacity: 0">';
foreach ($pages as $page) {
  echo '<article>';
  echo '<a href="' . $nh->getLinkToCollection($page) . '">' . htmlentities($page->getCollectionName()) . '</a>';  
  echo '</article>';
}
echo '</div>';

if ( !empty($paginator_links) ) {   // Output pagination
    echo '<div id="ajax-paginator" class="pagination">';
    echo $paginator_links;
    echo '</div>';
}

exit;