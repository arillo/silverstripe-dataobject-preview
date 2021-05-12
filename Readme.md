# Dataobject preview

[![Latest Stable Version](https://poser.pugx.org/arillo/silverstripe-dataobject-preview/v/stable?format=flat)](https://packagist.org/packages/arillo/silverstripe-dataobject-preview)
[![Total Downloads](https://poser.pugx.org/arillo/silverstripe-dataobject-preview/downloads?format=flat)](https://packagist.org/packages/arillo/silverstripe-dataobject-preview)

Shows a preview of your dataobjects like the one you get for pages. Works for GridField and ModelAdmin. Works only for Versioned DataObjects.

For the preview to work you need to implement the CMSPreviewable interface on your DataObject and declare the methods getMimeType, CMSEditLink and PreviewLink($action = null).

You also will need to declare the stages this DataObject should show in the preview pane by setting the appropiate static variables to true.

PreviewLink is the only link we are interested for the preview to work. The DataObjectPreviewController will listen for this links to render your MyDataObject with the MyDataObject.ss template in your theme/templates/\* folder.

Since activating this feature is a bit hacky, we need to also define a custom template for our CustomModelAdmin.

## Requirements

SilverStripe CMS ^4.0

For a SilverStripe 3.x compatible version of this module, please see the [1 branch, or 1.x release line](https://github.com/arillo/silverstripe-arbitrarysettings/tree/1.0).

## Installation

    composer require arillo/silverstripe-dataobject-preview:2.0.*

## Example

```php
<?php
namespace Arillo\DataObjectPreview\Models;

use SilverStripe\ORM\CMSPreviewable;
use SilverStripe\ORM\DataObject;

class MyDataObject extends DataObject implements CMSPreviewable
{
    ...

    private static $show_stage_link = true;
    private static $show_live_link = true;

    private static $extensions = array(
        Versioned::class
    );

    private static $versioning = array(
        "Stage",  "Live"
    );

    public function PreviewLink($action = null){
        return Controller::join_links(Director::baseURL(), 'cms-preview', 'show', urlencode($this->ClassName), $this->ID);
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

If our CustomModelAdmin looks like this:

```php
<?php
namespace Arillo\DataObjectPreview\Admins;

use Arillo\DataObjectPreview\Models\MyDataObject;

class CustomModelAdmin extends ModelAdmin {
    private static $managed_models = array (
		MyDataObject::class
	);
}
```

We need to create the corresponding template in mysite/templates/Arillo/DataObjectPreview/Admins/Includes/CustomModelAdmin_PreviewPanel.ss with this content copied from version 4.7.0 of the silverstripe/cms module. (Beware that this can vary depending on the version and may be changed over time.)

```html
<div
    class="cms-preview fill-height flexbox-area-grow"
    data-layout-type="border"
>
    <div class="panel flexbox-area-grow fill-height">
        <div class="preview-note">
            <span><!-- --></span><%t
            SilverStripe\CMS\Controllers\CMSPageHistoryController.PREVIEW
            'Website preview' %>
        </div>
        <div class="preview__device">
            <div class="preview-device-outer">
                <div class="preview-device-inner">
                    <iframe
                        src="about:blank"
                        class="center"
                        name="cms-preview-iframe"
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
    <div
        class="toolbar toolbar--south cms-content-controls cms-preview-controls"
    ></div>
    <div class="cms-preview-overlay ui-widget-overlay-light"></div>
</div>
```

## Usage

By default, the dataobject preview will look for templates with the dataobject classname directly in the templates folder. So for the example above it will look for themes/yourtheme/templates/Arillo/DataObjectPreview/Models/MyDataObject.ss.
If you would like to customise this behaviour you can do so by implementing your own renderPreview method on the DataObject.

```php
namespace Arillo\DataObjectPreview\Models;
class MyDataObject extends DataObject implements CMSPreviewable
{
    ...
    public function renderPreview()
    {
        // this will look for themes/yourtheme/templates/Arillo/DataObjectPreview/Models/MyDataObject.ss
        return $this->renderWith(MyDataObject::class);
    }
}
```

You can overwrite the main template by placing it either in themes/yourtheme/templates/PreviewDataObject.ss or mysite/PreviewDataObject.ss.

-   PreviewDataObject.ss -> Container for MyDataObject preview (Like the main Page.ss)

Tip: If you are using [silverstripe-gridfield-betterbuttons](https://github.com/unclecheese/silverstripe-gridfield-betterbuttons) you can disable the dataobject preview links since they are no longer needed. Just add this to your config.yml.

```
BetterButtonsActions:
  edit:
    BetterButtonFrontendLinksAction: false
  versioned_edit:
    BetterButtonFrontendLinksAction: false
```

## Changelog

V 2.0.2

-   added modeladmin support

V 2.0.0

-   renamed method previewRender to renderPreview
