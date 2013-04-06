<?php      
defined('C5_EXECUTE') or die(_("Access Denied."));

class AjaxPageListPackage extends Package {
	
	protected $pkgHandle = 'ajax_page_list';
	protected $appVersionRequired = '5.5';
	protected $pkgVersion = '0.9.0';
	
	public function getPackageName() {
		return t('Ajax Page List'); 
	}
	
	public function getPackageDescription() {
		return t('Loads and paginates Page List entries using AJAX');
	}

	public function install() {
		parent::install();
	}

}