# Dataobject preview

[![Latest Stable Version](https://poser.pugx.org/arillo/silverstripe-dataobject-preview/v/stable?format=flat)](https://packagist.org/packages/arillo/silverstripe-dataobject-preview)
[![Total Downloads](https://poser.pugx.org/arillo/silverstripe-dataobject-preview/downloads?format=flat)](https://packagist.org/packages/arillo/silverstripe-dataobject-preview)

Shows a preview of your dataobjects like the one you get for pages. Works for GridField and ModelAdmin. Works only for Versioned DataObjects.

For the preview to work you need to implement the CMSPreviewable interface on your DataObject and declare the methods getMimeType, CMSEditLink and PreviewLink($action = null).

PreviewLink is the only link we are interested for the preview to work. The DataObjectPreviewController will listen for this links to render your MyDataObject with the MyDataObject.ss template in your theme/templates/* folder.

## Example
```php
<?php
class MyDataObject extends DataObject implements CMSPreviewable
{
	...
    private static $extensions = array(
        Versioned::class
    );

    private static $versioning = array(
        "Stage",  "Live"
    );

	public function PreviewLink($action = null){
		return Controller::join_links(Director::baseURL(), 'cms-preview', 'show', $this->ClassName, $this->ID);
	}

    public function getMimeType()
    {
        return 'text/html';
    }

	public function CMSEditLink(){
		...
	}
    ...
}
```

## Requirements

SilverStripe 3.1 or higher

## Installation

    composer require arillo/silverstripe-dataobject-preview:1.0.*

## Overwrites

We override following core template to include the preview toolbar also when previewing a DataObject: silverstripe-admin/templates/SilverStripe/Admin/Includes/LeftAndMain_EditForm.ss

## Usage

By default, the dataobject preview will look for templates with the dataobject classname directly in the templates folder. So for the example above it will look for themes/yourtheme/templates/MyDataObject.ss.
If you would like to customise this behaviour you can do so by implementing your own renderPreview method on the DataObject.

```php
class MyDataObject extends DataObject implements CMSPreviewable
{
	...
    public function renderPreview()
	{
        // this will look for themes/yourtheme/templates/Includes/MyDataObject.ss
		return $this->renderWith('Includes/'.MyDataObject::class);
	}
}
```

You can overwrite the main template by placing it either in themes/yourtheme/templates/PreviewDataObject.ss or mysite/PreviewDataObject.ss.

- PreviewDataObject.ss -> Container for MyDataObject preview (Like the main Page.ss)


Tip: If you are using [silverstripe-gridfield-betterbuttons](https://github.com/unclecheese/silverstripe-gridfield-betterbuttons) you can disable the dataobject preview links since they are no longer needed. Just add this to your config.yml.

```
BetterButtonsActions:
  edit:
    BetterButtonFrontendLinksAction: false
  versioned_edit:
    BetterButtonFrontendLinksAction: false
```

## Changelog

V 4.0.0
- renamed method previewRender to renderPreview
