# Genesis Custom Blocks Pro #

Contributors: lukecarbis, ryankienstra, Stino11, rheinardkorf, studiopress, wpengine
Tags: gutenberg, blocks, block editor, fields, template
Requires at least: 5.7
Tested up to: 6.5
Stable tag: 1.7.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl

Custom blocks for WordPress made easy.

## Description ##

Genesis Custom Blocks Pro provides WordPress developers with the tools they need to take control of the block-first reality of modern WordPress.

The WordPress block editor (AKA Gutenberg) opens up a whole new world for the way we build pages, posts, and websites with WordPress. Genesis Custom Blocks Pro makes it easy to harness this and build custom blocks the way you want them to be built. Whether you want to implement a custom design, deliver unique functionality, or even remove your dependence on other plugins, Genesis Custom Blocks Pro equips you with the tools you need to hit “Publish” sooner.

**Take control of design** - Implement beautiful, custom designs with fine-tuned front-end templating control.

**Build unique functionality** - Build blocks that function and behave exactly as you need.

**Extend & Integrate** - Easily extend your custom blocks to integrate with third-party apps and plugins.

## Features ##

### A Familiar Experience ###
Work within the WordPress admin with an interface you already know.

### Block Fields ###
Add from a growing list of available fields to your custom blocks.

### Simple Templating ###
Let the plugin do the heavy lifting so you can use familiar WordPress development practices to build block templates.

### Developer Friendly Functions ###
Simple to use functions, ready to render and work with the data stored through your custom block fields.

### Block-level Import & Export ###
Easily export and import individual blocks to better share block assets between projects.

## Currently available block fields ##
* Text Field
* Image Field
* URL Field
* Toggle Field
* Textarea Field
* Select Field
* Range Rield
* Radio Field
* Number Field
* Multi-select Field
* Email Field
* Color Field
* Checkbox Field
* Repeater Field
* Taxonomy Field
* Post Field
* User Field
* Rich Text Field
* Classic Text Field

## Developer docs you can rely on. ##

Developer tools are only as good as their docs. That's why we take them seriously. Here are a few links to get you started:

## Links ##
* [Getting Started](https://developer.wpengine.com/genesis-custom-blocks/get-started/)
* [FAQs](https://developer.wpengine.com/genesis-custom-blocks/faqs/)
* [Block Fields](https://developer.wpengine.com/genesis-custom-blocks/fields/)
* [PHP Functions](https://developer.wpengine.com/genesis-custom-blocks/functions/)

## Installation ##
* Upload the plugin directory to `/wp-content/plugins/`
* Activate this plugin via `/wp-admin` > Plugins
* There's no need to also install the [free Genesis Custom Blocks plugin](https://wordpress.org/plugins/genesis-custom-blocks)

## Frequently Asked Questions ##
**Q: Do I need to work with the Genesis Framework or any of the other Genesis plugins/themes to use this plugin?**
A: No. You can use this plugin completely independently. All you need is to have the block editor enabled on your WordPress site.

**Q: Do I need to have the free Genesis Custom Blocks Pro plugin installed (from wordpress.org) to be able to use Pro?**
A: No. The Pro plugin contains all the features of the free plugin as well as the advanced features. This is the only plugin you need installed/active.

## Changelog ##

### 1.7.0 - 2023-3-20
* Fix a WP 6.5 compatibility bug in the GCB editor. [PR 178](https://github.com/studiopress/genesis-custom-blocks/pull/178)

### 1.6.1 - 2023-12-20
* In Post field, show more suggestions for `<input>` when they've searched for something. [PR 87](https://github.com/studiopress/genesis-custom-blocks-pro/pull/87)

### 1.6.0 - 2023-11-09
* Fix an error in previewing blocks in the editor. [PR 170](https://github.com/studiopress/genesis-custom-blocks/pull/170)

### 1.5.3 - 2023-08-16
* Allow reordering Classic Text fields in the Repeater. [PR 75](https://github.com/studiopress/genesis-custom-blocks/pull/75)

### 1.5.2 - 2023-08-03
* Bump minimum PHP version to 7.0. [PR 141](https://github.com/studiopress/genesis-custom-blocks/pull/141)

### 1.5.1 - 2022-08-09 ###
* PHP 8.1 compatibility fixes. [PR 119](https://github.com/studiopress/genesis-custom-blocks/pull/119)

### 1.5.0 - 2022-03-24 ###

* Allow previewing InnerBlocks in the block editor. [PR 114](https://github.com/studiopress/genesis-custom-blocks/pull/114)
* Allow opting in to analytics. [PR 101](https://github.com/studiopress/genesis-custom-blocks/pull/101)
* Remove some Settings page logic, as it's moved to the free plugin. [PR 42](https://github.com/studiopress/genesis-custom-blocks-pro/pull/42)

### 1.4.1 - 2021-10-21 ###

* Classic Text field: prevent converting links to relative URLs. [PR 39](https://github.com/studiopress/genesis-custom-blocks-pro/pull/39)

### 1.4.0 - 2021-10-14 ###

* Optional modal for editor fields. [PR 93](https://github.com/studiopress/genesis-custom-blocks/pull/93)

### 1.3.1 - 2021-08-26 ###

Fix for fields with long strings

* Fix a regression that caused editor errors for fields with long strings. [PR 89](https://github.com/studiopress/genesis-custom-blocks/pull/89)
* Update e2e tests for WP 5.8. [PR 35](https://github.com/studiopress/genesis-custom-blocks-pro/pull/35)

### 1.3.0 - 2021-07-20 ###

InnerBlocks, File field, WP 5.8 compatibility

* Add InnerBlocks, allowing any block inside a GCB block. [PR 68](https://github.com/studiopress/genesis-custom-blocks/pull/68)
* Add a file field, like for .pdf or .zip files. [PR 74](https://github.com/studiopress/genesis-custom-blocks/pull/74)
* In WP 5.8, prevent a PHP notice by using the new filter 'block_categories_all'. [PR 85](https://github.com/studiopress/genesis-custom-blocks/pull/85)

### 1.2.0 - 2021-06-28 ###

New Template Editor, Editor Preview, and Front-end Preview

* Template Editor UI, though the PHP templates still work just like before. [PR 65](https://github.com/studiopress/genesis-custom-blocks/pull/65), [PR 69](https://github.com/studiopress/genesis-custom-blocks/pull/69), [PR 72](https://github.com/studiopress/genesis-custom-blocks/pull/72)
* Add Editor Preview and Front-end Preview. [PR 63](https://github.com/studiopress/genesis-custom-blocks/pull/63)
* Don't display the editor form if there's no editor field. [PR 64](https://github.com/studiopress/genesis-custom-blocks/pull/64)
* Fix an issue with the default value of 'Checkbox' and 'Toggle' fields. [PR 60](https://github.com/studiopress/genesis-custom-blocks/pull/60)
* Display the help text in the TextareaArray setting. [PR 62](https://github.com/studiopress/genesis-custom-blocks/pull/62)
* Improved abstraction by removing duplicated copy. [PR 78](https://github.com/studiopress/genesis-custom-blocks/pull/78)
* In Post field, allow more than 5-6 CPTs. [PR 29](https://github.com/studiopress/genesis-custom-blocks-pro/pull/29)
* Display a message if there's no subscription key, still make the request. [PR 27](https://github.com/studiopress/genesis-custom-blocks-pro/pull/27)

### 1.1.0 - 2021-01-27 ###

Completely redesigned, more effortless editor! With undo/redo buttons and a separate editor/inspector.

* Restore and update e2e tests. [PR 24](https://github.com/studiopress/genesis-custom-blocks-pro/pull/24)
* Add settings components for pro fields. [PR 55](https://github.com/studiopress/genesis-custom-blocks-pro/pull/16)
* Repeater improvements for new editor. [PR 23](https://github.com/studiopress/genesis-custom-blocks-pro/pull/23)

### 1.0.4 - 2020-12-09 ###

More reliable checks for subscription key, bump 'Tested up to' to 5.6

* Improve checks for subscription key. [PR 18](https://github.com/studiopress/genesis-custom-blocks-pro/pull/18)
* Bump the 'Tested up to' version to 5.6. [PR 21](https://github.com/studiopress/genesis-custom-blocks-pro/pull/21)

### 1.0.3 - 2020-10-21 ###

Allow more text and improve activation experience

* Allow fields with a huge amount of text. [PR 39](https://github.com/studiopress/genesis-custom-blocks/pull/39)
* Ensure that all submenus appear on first installation. [PR 40](https://github.com/studiopress/genesis-custom-blocks/pull/40)

### 1.0.2 - 2020-09-16 ###

Bugfixes, including saving the subscription key

* Fix an error in saving the subscription key. [PR 11](https://github.com/studiopress/genesis-custom-blocks-pro/pull/11)
* Fix a PHP notice on /wp-admin > Plugins > Add New. [PR 12](https://github.com/studiopress/genesis-custom-blocks-pro/pull/12)

### 1.0.1 - 2020-09-01 ###

Fix an error if Block Lab 1.5.6 is also active

* Fixes an error with Block Lab 1.5.6, where it defines functions twice
* Error does not occur with latest Block Lab

### 1.0.0 - 2020-09-01 ###

Plugin released!

* 19 fields, including a Repeater, Post, and User.
* Easy templating, with PHP files.
* Block-level importing and exporting.
