<?php
/**/
namespace SilverStripe\DataObjectPreview\Extensions;
/**/
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\GridField\GridFieldDetailForm_ItemRequest;
/**/
class PreviewGridFieldDetailForm_ItemRequest extends GridFieldDetailForm_ItemRequest{
    /**/
    private static $allowed_actions = [
        'ItemEditForm',
    ];
  /**/
  public function ItemEditForm(){

    $form = parent::ItemEditForm();
    $formActions = $form->Actions();

    $html = $this->customise([
      "SelectID" => "preview-mode-dropdown-in-content"
    ])->renderWith(array('SilverStripe\\Admin\\LeftAndMain_ViewModeSelector'));

    $PreviewLink = LiteralField::create('', $html);
    $formActions->push($PreviewLink);

    $form->setActions($formActions);

    return $form;
  }
}
