<?php
namespace SilverStripe\DataObjectPreview\Controllers;

use PageController;
use SilverStripe\Control\Middleware\HTTPCacheControlMiddleware;
use SilverStripe\Versioned\Versioned;
use InvalidArgumentException;

class DataObjectPreviewController extends PageController
{
    protected $dataobject;

    private static $allowed_actions = [ 'show' ];
    private static $url_segment = 'show';

    private static $url_handlers = [
        'show/$ClassName/$ID/$OtherClassName/$OtherID' => 'show'
    ];

    public static function strip_namespacing($namespaceClass)
    {
        if (strrpos($namespaceClass, '\\')) {
            return substr($namespaceClass, strrpos($namespaceClass, '\\') + 1);
        }
        return $namespaceClass;
    }

    protected function init()
    {
        parent::init();

        // In the CMS Preview or draft contexts, we never want to cache page output.
        if (
            $this->getRequest()->getVar('CMSPreview') == '1' ||
            $this->getRequest()->getVar('stage') == Versioned::DRAFT
        ) {
            HTTPCacheControlMiddleware::singleton()->disableCache(true);
        }
    }

    public function show($request)
    {
        $class = urldecode($request->param('ClassName'));
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
