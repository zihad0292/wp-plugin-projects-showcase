# wp-plugin-projects-showcase

A simple WordPress plugin that creates a custom post type called "WPPool ZI Projects". In the "Edit Post", in addition to having default title, description and featured image fields, this custom post type also gives two additional fields for the user. One is for adding 'External URL' information and another is to add multiple 'Preview Images'. This plugin also provides the templates to view the archive page and single post page. The archive page template is setup in a way to show single post details in a modal when the user clicks on any project in the list.

## Requirements

WordPress version 5.5.12 or higher

## Installation

Download the zip file from the GitHub Repository and upload the plugin in your WordPress website's plugin page.

## Usage

Once installed and activated, you will see a menu item called "WPPool ZI Projects" in the admin dashobard of your website. You can add/edit the projects from there.

## Important Notes

```bash
This plugin adds a custom image size that is used in the archive page. It is needed to regenerate the thumbnails if you plan to use the existing images of your website. If you upload new images, then you don't need to do anything.
```

```bash
If you see "404 Not Found" error in the archive page, please flush the permalink by going to Settings->Permalinks and then Click on "Save"
```

