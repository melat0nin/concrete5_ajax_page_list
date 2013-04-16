concrete5_ajax_page_list
========================

An AJAXified page list block for concrete5. Navigate a Page List block's pages and apply attribute filters without reloading the whole page.

Tested with v5.6 but may work with earlier versions.

* [Installation](#installation)
* [Filtering by custom select attributes](#filtering-by-custom-select-attributes)
* [Screenshots](#screenshots)


Installation
------------

1. Download the package [ZIP file](https://github.com/melat0nin/concrete5_ajax_page_list/archive/master.zip) 
2. Extract the `concrete5_ajax_page_list-master` directory and rename it to `ajax_page_list`
3. Upload to your concrete5 installation's `/packages` directory
4. Install the package through the Extend concrete5 page of the Dashboard
5. Select the Ajax Page List custom template for your Page List block
6. Have a nice cup of tea :)


Filtering by custom select attributes
-------------------------------------

No configuration beyond the above is required to render and paginate the Page List (this is the default behaviour). concrete5's Page List objects are powerful things though, and sometimes we want to filter them using custom select attributes.

To enable filtering by custom select attributes:

1. Create your select attributes in the Page Attributes page of the Dashboard and note the handles you give each attribute. 

2. Add your custom select attributes' handles to the `$filter_attributes` array near the top of the `ajax_page_list.php` custom template file (which can be found in the `/packages/ajax_page_list/blocks/page_list` directory):
    
  For example, if your select attributes' handles are `my_colour_attribute` and `my_size_attribute`, the `$filter_attributes` array will look like this:
  
  > $filter_attributes = array('my_colour_attribute', 'my_size_attribute');

  The package will automatically render each attribute's options as links above the AJAXified Page List. 
  **Note:** The filters are cumulative, i.e. the Page List can filter by more than one select attribute simultaneously.
  
  
Screenshots
-----------

Click the thumbnails for larger versions.

###No filters -- just paging

  <img src="https://googledrive.com/host/0B3Ekginw8kODUnEzc0Y1TkIzSms/no-filters.png" width="300" />

***

###Filters displayed but not applied

  <img src="https://googledrive.com/host/0B3Ekginw8kODUnEzc0Y1TkIzSms/filters.png" width="300" />

***

###Filters applied

  <img src="https://googledrive.com/host/0B3Ekginw8kODUnEzc0Y1TkIzSms/filters-applied.png" width="300" />
