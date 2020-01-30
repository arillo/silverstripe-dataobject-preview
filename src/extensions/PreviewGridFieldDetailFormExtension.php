<?php

namespace SilverStripe\DataObjectPreview\Extensions;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Extension;
use SilverStripe\CMS\Controllers\SilverStripeNavigator;
use SilverStripe\ORM\CMSPreviewable;
use SilverStripe\Forms\LiteralField;

class PreviewGridFieldDetailFormExtension extends Extension
{
    public function updateItemEditForm(&$form)
    {
        $fields = $form->Fields();
        if ($this->owner->record instanceof CMSPreviewable && !$fields->fieldByName('SilverStripeNavigator'))
        {
            $fields->removeByName('SilverStripeNavigator');
            $ctrl = Controller::curr();
            if(!$ctrl instanceof ModelAdmin){
                $navigator = new SilverStripeNavigator($this->owner->record);
                $field = new LiteralField('SilverStripeNavigator', $navigator->renderWith($ctrl->getTemplatesWithSuffix('_SilverStripeNavigator')));
                $field->setAllowHTML(true);
                $fields->push($field);
                $form->addExtraClass('cms-previewable');
                $form->addExtraClass('cms-previewabledataobject');
                $form->removeExtraClass('cms-panel-padded center');
            }
        }
    }
}
