<?php
	//Add post-type - Realty
	
	add_action( 'init', 'add_realty' ); 
	
	function add_realty() 
	{ 
		$labels = array( 
			'name' => 'Недвижимость', 
			'singular_name' => 'Недвижимость',
			'add_new' => 'Добавить', 
			'add_new_item' => 'Добавить', 
			'edit_item' => 'Редактировать', 
			'new_item' => 'Новый', 
			'all_items' => 'Показать все', 
			'view_item' => 'Просмотр', 
			'search_items' => 'Искать', 
			'not_found' => 'Не найдено.', 
			'menu_name' => 'Недвижимость'
		); 
		$args = array( 
			'labels' => $labels, 
			'public' => true,
			'menu_icon' => 'dashicons-admin-home',
			'menu_position' => 2, 
			'has_archive' => true, 
			'supports' => array( 'title', 'editor', 'thumbnail'), 
			//'taxonomies' => array('company_type')
		); 
		register_post_type('realty', $args); 
	}