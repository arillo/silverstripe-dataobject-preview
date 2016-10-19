# silverstripe-dataobject-preview

Shows a preview of your dataobjects like the one you get for pages. Works for GridField and ModelAdmin. Works currently only for Versioned DataObjects.

_Pending fix_: If you switch to "Edit mode" you will not be able to show the preview again since the button gets removed. Will fix it soon.

For the preview to work you need to implement the CMSPreviewable interface on your DataObject and declare the methods Link, CMSEditLink and PreviewLink($action = null).

PreviewLink is the only link we are interested for the preview to work. The DataObjectPreviewController will listen for this links to render your MyDataObject with the MyDataObject.ss template in your theme/templates/* folder.

## Example
```
<?php
class MyDataObject extends DataObject implements CMSPreviewable
{

	...

    private static $extensions = array(
        'Versioned("Stage","Live")'
    );

	public function PreviewLink($action = null){
		return Controller::join_links(Director::baseURL(), 'cms-preview', 'show', $this->ID, $this->ClassName);
	}

    public function Link() {
        ...
    }

	public function CMSEditLink(){
		...
	}

}
```

## Requirements

SilverStripe 3.1 or higher

## Installation

    composer require arillo/silverstripe-dataobject-preview:1.0.*


## Usage

You can overwrite the templates by placing them in the templates folder of your theme or in your mysite folder.

- PreviewDataObject.ss -> Container for MyDataObject preview (Like the main Page.ss)
- DataObjectPreviewNotFound.ss -> DataObject not found or not yet created.

Tip: If you are using [silverstripe-gridfield-betterbuttons](https://github.com/unclecheese/silverstripe-gridfield-betterbuttons) you can disable the dataobject preview links since they are no longer needed. Just add this to your config.yml.

```
BetterButtonsActions:
  edit:
    BetterButtonFrontendLinksAction: false
  versioned_edit:
    BetterButtonFrontendLinksAction: false
```
