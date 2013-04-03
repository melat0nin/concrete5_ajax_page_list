<?php
defined('C5_EXECUTE') or die("Access Denied.");

// Helpers
$nh = Loader::helper('navigation');
$th = Loader::helper('text');

// Instantiate PageList object
Loader::model('page_list');
$db = Loader::db();
$bID = $_GET['bID'];
if ($bID) {
    $q = "select num, cParentID, cThis, orderBy, ctID, displayAliases, rss, displayFeaturedOnly, includeAllDescendents, truncateSummaries, truncateChars from btPageList where bID = '$bID'";
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

/*
 * Retrieve and output pages and pagination
 */
$pages = $pl->getPage($current_page);

echo '<div id="ajax-article-list" style="opacity: 0">';

foreach ($pages as $page) {
    // Prepare data for each page being listed...
    $title = $th->entities($page->getCollectionName());
    $url = $nh->getLinkToCollection($page);
    $target = ($page->getCollectionPointerExternalLink() != '' && $page->openCollectionPointerExternalLinkInNewWindow()) ? '_blank' : $page->getAttribute('nav_target');
    $target = empty($target) ? '_self' : $target;
    $description = $page->getCollectionDescription();
    $description = $row['truncateSummaries'] ? $th->shorten($description, $row['truncateChars']) : $description;
    $description = $th->entities($description);

    //Other useful page data...
    //$date = date('F j, Y', strtotime($page->getCollectionDatePublic()));
    //$last_edited_by = $page->getVersionObject()->getVersionAuthorUserName();
    //$original_author = Page::getByID($page->getCollectionID(), 1)->getVersionObject()->getVersionAuthorUserName();

    /* CUSTOM ATTRIBUTE EXAMPLES:
     * $example_value = $page->getAttribute('example_attribute_handle');
     *
     * HOW TO USE IMAGE ATTRIBUTES:
     * 1) Uncomment the "$ih = Loader::helper('image');" line up top.
     * 2) Put in some code here like the following 2 lines:
     *      $img = $page->getAttribute('example_image_attribute_handle');
     *      $thumb = $ih->getThumbnail($img, 64, 9999, false);
     *    (Replace "64" with max width, "9999" with max height. The "9999" effectively means "no maximum size" for that particular dimension.)
     *    (Change the last argument from false to true if you want thumbnails cropped.)
     * 3) Output the image tag below like this:
     *		<img src="<?php  echo $thumb->src ?>" width="<?php  echo $thumb->width ?>" height="<?php  echo $thumb->height ?>" alt="" />
     *
     * ~OR~ IF YOU DO NOT WANT IMAGES TO BE RESIZED:
     * 1) Put in some code here like the following 2 lines:
     * 	    $img_src = $img->getRelativePath();
     * 	    list($img_width, $img_height) = getimagesize($img->getPath());
     * 2) Output the image tag below like this:
     * 	    <img src="<?php  echo $img_src ?>" width="<?php  echo $img_width ?>" height="<?php  echo $img_height ?>" alt="" />
     */

    /* End data preparation. */

    /* The HTML from here through "endforeach" is repeated for every item in the list... */ ?>

    <h3 class="ccm-page-list-title">
	<a href="<?php  echo $url ?>" target="<?php  echo $target ?>"><?php  echo $title ?></a>
    </h3>
    <div class="ccm-page-list-description">
	<?php  echo $description ?>
    </div>

<?php
}

echo '</div>'; // Close #ajax-article-list

// Output pagination
if ( $pl->getSummary()->pages > 1 ) {
    echo '<div id="ajax-paginator" class="pagination">';
    $pl->displayPaging();
    echo '</div>';
}

exit;