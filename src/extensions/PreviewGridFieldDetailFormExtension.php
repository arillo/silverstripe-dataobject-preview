<?php

namespace SilverStripe\DataObjectPreview\Extensions;

use SilverStripe\Control\Controller;
use SilverStripe\Core\Extension;
use SilverStripe\CMS\Controllers\SilverStripeNavigator;
use SilverStripe\ORM\CMSPreviewable;
use SilverStripe\Forms\LiteralField;
use SilverStripe\View\Requirements;

class PreviewGridFieldDetailFormExtension extends Extension
{
    /**
     * Shall the legacy pre ss 4.11 preview code be used?
     *
     * @var boolean
     */
    private static $inject_legacy_code = true;

    public function updateItemEditForm(&$form)
    {
        $fields = $form->Fields();
        if (
            $this->owner->record instanceof CMSPreviewable &&
            !$fields->fieldByName('SilverStripeNavigator')
        ) {
            $this->injectNavigatorAndPreview($form, $fields);
        }
    }

    private function injectNavigatorAndPreview(&$form, &$fields)
    {
        if (!$this->owner->config()->inject_legacy_code) {
            return;
        }

        Requirements::javascript(
            'arillo/silverstripe-dataobject-preview:client/javascript/GridField.Preview.js'
        );
        //@TODO: Do we need to verify we are in the right controller?
        $template = Controller::curr()->getTemplatesWithSuffix(
            '_SilverStripeNavigator'
        );
        $navigator = new SilverStripeNavigator($this->owner->record);
        $field = new LiteralField(
            'SilverStripeNavigator',
            $navigator->renderWith($template)
        );
        $field->setAllowHTML(true);
        $fields->push($field);
        $form->addExtraClass('cms-previewable');
        $form->addExtraClass('cms-previewabledataobject');
        $form->removeExtraClass('cms-panel-padded center');
    }
}
