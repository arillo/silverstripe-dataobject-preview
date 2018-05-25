<?php
namespace SilverStripe\DataObjectPreview\Controllers;

use SilverStripe\Control\Controller;
use InvalidArgumentException;

class DataObjectPreviewController extends Controller
{
    protected $dataobject;

    private static
        $allowed_actions = [
            'show'
        ],
        $url_handlers = [
            'show/$ClassName/$ID/$OtherClassName/$OtherID' => 'show'
        ]
    ;

    public static function strip_namespacing($namespaceClass)
    {
        if (strrpos($namespaceClass, '\\')) {
            return substr($namespaceClass, strrpos($namespaceClass, '\\') + 1);
        }
        return $namespaceClass;
    }

    public function show($request)
    {
        $class = $request->param('ClassName');
        $class = str_replace('-', '\\', $class);
        if (!class_exists($class)){
            throw new InvalidArgumentException(sprintf(
                'DataObjectPreviewController: Class of type %s doesn\'t exist',
                $class
            ));
        }

        $id = $request->param('ID');
        if (!ctype_digit($id))
        {
            throw new InvalidArgumentException('DataObjectPreviewController: ID needs to be an integer');
        }

        $this->dataobject = $class::get()->filter(array('ID' => $id))->First();

        $r = false;
        switch (true)
        {
            case (!$this->dataobject):
                $r = false;
                break;
            case ($this->dataobject->hasMethod('renderPreview')):
                $r = $this->dataobject->renderPreview();
                break;

            default:
                $r = $this->dataobject->renderWith([
                    $class,
                    self::strip_namespacing($class)
                ]);
                break;
        }

        return $this->customise([
            'Rendered' => $r
        ])->renderWith('PreviewDataObject');
    }
}
