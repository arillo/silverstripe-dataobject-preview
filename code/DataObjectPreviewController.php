<?php
class DataObjectPreviewController extends Controller {

	public $dataobject;

	public static $allowed_actions = array(
		'show'
		);

	public function show($request){
		$class = $request->param('OtherID');
		// @TODO: check if using fluent, set locale to current CMS locale
		// Fluent::set_persist_locale(Session::get('FluentLocale_CMS'));
		$this->dataobject = $class::get()->filter(array('ID' => $request->param('ID')))->First();
		if ( is_null($this->dataobject) ) {
			return $this->customise(array(
				'Layout' => $this->renderWith('DataObjectNotFound')
				))->renderWith('PreviewDataObject');
		}
		return $this->customise(array(
			'Layout' => $this->dataobject->renderWith($class)
			))->renderWith('PreviewDataObject');
	}

}
