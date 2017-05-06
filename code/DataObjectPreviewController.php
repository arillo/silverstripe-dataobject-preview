<?php
class DataObjectPreviewController extends Controller {

    public $dataobject;

    public static $allowed_actions = array(
        'show'
    );

    private static $url_handlers = array(
        'show/$ClassName/$ID/$OtherClassName/$OtherID' => 'show'
    );

    public function show($request){
        if (class_exists('Fluent')) {
            Fluent::set_persist_locale(Session::get('FluentLocale_CMS'));
        }

        $class = $request->param('ClassName');
        if (!class_exists($class)){
            throw new InvalidArgumentException(sprintf(
                'DataObjectPreviewController: Class of type %s doesn\'t exist',
                $class
            ));
        }

        $id = $request->param('ID');
        if (!ctype_digit($id)){
            throw new InvalidArgumentException('DataObjectPreviewController: ID  needs to be an integer');
        }

        $this->dataobject = $class::get()->filter(array('ID' => $id))->First();

        if ($this->dataobject->hasMethod('previewRender')) {
            return $this->customise(array(
                'Rendered' => $this->dataObject->previewRender()
            ))->renderWith('PreviewDataObject');
        } else {
            return $this->customise(array(
                'Rendered' => $this->dataobject->renderWith($class)
            ))->renderWith('PreviewDataObject');
        }
    }
}