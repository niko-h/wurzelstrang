/**
 * Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

// This file contains style definitions that can be used by CKEditor plugins.
//
// The most common use for it is the "stylescombo" plugin, which shows a combo
// in the editor toolbar, containing all styles. Other plugins instead, like
// the div plugin, use a subset of the styles on their feature.
//
// If you don't have plugins that depend on this file, you can simply ignore it.
// Otherwise it is strongly recommended to customize this file to match your
// website requirements and design properly.

CKEDITOR.stylesSet.add( 'default', [
	/* Block Styles */

	// These styles are already available in the "Format" combo ("format" plugin),
	// so they are not needed here by default. You may enable them to avoid
	// placing the "Format" combo in the toolbar, maintaining the same features.
	
	{ name: 'Paragraph',		element: 'p' },
	{ name: 'Clarendon 36px',	element: 'h1',
			styles: {
				'font-size': '36px',
				'line-height': '40px',
				'font-family': 'ClarendonBT-Light'
			} },
	{ name: 'Clarendon 24',	element: 'h2',
			styles: {
				'font-size': '24px',
				'line-height': '27px',
				'font-family': 'ClarendonBT-Light'
			} },
	{ name: 'Clarendon 18',	element: 'h3',
			styles: {
				'font-size': '18px',
				'line-height': '20px',
				'font-family': 'ClarendonBT-Light'
			} },
	{ name: 'Vorformatiert',	element: 'pre' },
	// { name: 'Address',			element: 'address' },
	{ name: 'Normal',			element: 'div' },
	

	// { name: 'Italic Title',		element: 'h2', styles: { 'font-style': 'italic' } },
	// { name: 'Subtitle',			element: 'h3', styles: { 'color': '#aaa', 'font-style': 'italic' } },
	// {
	// 	name: 'Special Container',
	// 	element: 'div',
	// 	styles: {
	// 		padding: '5px 10px',
	// 		background: '#eee',
	// 		border: '1px solid #ccc'
	// 	}
	// },
	{
		name: 'Bildunterschrift',
		element: 'div',
		attributes: {
			'class': 'bildunterschrift'
		},
		styles: {
			background: '#eee',
			border: '1px solid #ccc'
		}
	},

	/* Inline Styles */

	// These are core styles available as toolbar buttons. You may opt enabling
	// some of them in the Styles combo, removing them from the toolbar.
	// (This requires the "stylescombo" plugin)
	/*
	{ name: 'Strong',			element: 'strong', overrides: 'b' },
	{ name: 'Emphasis',			element: 'em'	, overrides: 'i' },
	{ name: 'Underline',		element: 'u' },
	{ name: 'Strikethrough',	element: 'strike' },
	{ name: 'Subscript',		element: 'sub' },
	{ name: 'Superscript',		element: 'sup' },
	*/

	// { name: 'Marker: Yellow',	element: 'span', styles: { 'background-color': 'Yellow' } },
	// { name: 'Marker: Green',	element: 'span', styles: { 'background-color': 'Lime' } },

	// { name: 'Big',				element: 'big' },
	// { name: 'Small',			element: 'small' },
	// { name: 'Typewriter',		element: 'tt' },

	// { name: 'Computer Code',	element: 'code' },
	// { name: 'Keyboard Phrase',	element: 'kbd' },
	// { name: 'Sample Text',		element: 'samp' },
	// { name: 'Variable',			element: 'var' },

	// { name: 'Deleted Text',		element: 'del' },
	// { name: 'Inserted Text',	element: 'ins' },

	// { name: 'Cited Work',		element: 'cite' },
	// { name: 'Inline Quotation',	element: 'q' },

	// { name: 'Language: RTL',	element: 'span', attributes: { 'dir': 'rtl' } },
	// { name: 'Language: LTR',	element: 'span', attributes: { 'dir': 'ltr' } },

	/* Object Styles */

	// {
	// 	name: 'Styled image (left)',
	// 	element: 'img',
	// 	attributes: { 'class': 'left' }
	// },

	// {
	// 	name: 'Styled image (right)',
	// 	element: 'img',
	// 	attributes: { 'class': 'right' }
	// },

	// {
	// 	name: 'Compact table',
	// 	element: 'table',
	// 	attributes: {
	// 		cellpadding: '5',
	// 		cellspacing: '0',
	// 		border: '1',
	// 		bordercolor: '#ccc'
	// 	},
	// 	styles: {
	// 		'border-collapse': 'collapse'
	// 	}
	// },

	// { name: 'Borderless Table',		element: 'table',	styles: { 'border-style': 'hidden', 'background-color': '#E6E6FA' } },
	// { name: 'Square Bulleted List',	element: 'ul',		styles: { 'list-style-type': 'square' } }
]);

