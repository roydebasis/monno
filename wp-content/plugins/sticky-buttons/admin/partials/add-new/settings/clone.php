<?php
/**
 * Clone Elements Settings
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Elements for clone Menu 1
$menu_1_item_icon        = array(
	'name'   => 'param[menu_1][item_icon][]',
	'class'  => 'icons',
	'type'   => 'select',
	'val'    => 'fas fa-hand-point-up',
	'option' => $icons_new,
);

$menu_1_item_tooltip     = array(
	'name'  => 'param[menu_1][item_tooltip][]',
	'class' => 'item-tooltip',
	'type'  => 'text',
	'val'   => '',
);


$menu_1_item_type = array(
	'name'   => 'param[menu_1][item_type][]',
	'type'   => 'select',
	'val'    => 'link',
	'class'  => 'item-type',
	'option' => array(
		'link'         => esc_attr__( 'Link', $this->plugin['text'] ),
	),
	'func'   => 'itemtype(this);',
);

$menu_1_item_link = array(
	'name' => 'param[menu_1][item_link][]',
	'type' => 'text',
	'val'  => '',
);



// Text color
$menu_1_color = array(
	'name' => 'param[menu_1][color][]',
	'type' => 'color',
	'val'  => '#383838',
);

// Background color
$menu_1_bcolor = array(
	'name' => 'param[menu_1][bcolor][]',
	'type' => 'color',
	'val'  => '#81d742',
);

$menu_1_button_id = array (
	'name' => 'param[menu_1][button_id][]',
	'type' => 'text',
	'val'  => '',
);

$menu_1_button_id_help = array (
	'text' => esc_attr__( 'Set ID for element.', $this->plugin['text'] ),
);

$menu_1_button_class = array (
	'name' => 'param[menu_1][button_class][]',
	'type' => 'text',
	'val'  => '',
);

$menu_1_button_class_help = array (
	'title' => esc_attr__( 'Set Class for element.', $this->plugin['text'] ),
	'ul' => array(
		esc_attr__( 'You may enter several classes separated by a space.', $this->plugin['text'] ),
	)
);


$menu_1_item_icon_help = array(
	'title' => esc_attr__( 'Set the icon for menu item. If you want use the custom item:', $this->plugin['text'] ),
	'ul'    => array(
		esc_attr__( '1. Check the box on "custom"', $this->plugin['text'] ),
		esc_attr__( '2. Upload the icon in Media Library', $this->plugin['text'] ),
		esc_attr__( '3. Copy the URL to icon', $this->plugin['text'] ),
		esc_attr__( '4. Paste the icon URL to field', $this->plugin['text'] ),
	),
);

$menu_1_item_tooltip_help = array(
	'text' => esc_attr__( 'Set the text for menu item. Left empty, if you want use item without tooltip.', $this->plugin['text'] ),
);

$menu_1_item_type_help = array(
	'title' => esc_attr__( 'Types of the button which can be select', $this->plugin['text'] ),
	'ul'    => array(
		esc_attr__( 'Link - insert any link', $this->plugin['text'] ),
		esc_attr__( 'Share - share the page in selected social network', $this->plugin['text'] ),
		esc_attr__( 'Print - print the page', $this->plugin['text'] ),
		esc_attr__( 'Scroll to Top - go to header of the site', $this->plugin['text'] ),
		esc_attr__( 'Go Back - the previous URL in the history list', $this->plugin['text'] ),
		esc_attr__( 'Go Forward - the next URL in the history list', $this->plugin['text'] ),
		esc_attr__( 'Smooth Scroll - scroll the page to the element with ID', $this->plugin['text'] ),
	),
);

$menu_1_hold_open_help = array(
	'text' => esc_attr__( 'Hold open button label.', $this->plugin['text'] ),
);