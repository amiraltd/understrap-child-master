<?php
	//Add post-type - City
	
	add_action( 'init', 'add_city' ); 
	
	function add_city() 
	{ 
		$labels = array( 
			'name' => 'Город', 
			'singular_name' => 'Город',
			'add_new' => 'Добавить', 
			'add_new_item' => 'Добавить', 
			'edit_item' => 'Редактировать', 
			'new_item' => 'Новый', 
			'all_items' => 'Показать все', 
			'view_item' => 'Просмотр', 
			'search_items' => 'Искать', 
			'not_found' => 'Не найдено.', 
			'menu_name' => 'Город'
		); 
		$args = array( 
			'labels' => $labels, 
			'public' => true,
			'menu_icon' => 'dashicons-admin-site',
			'menu_position' => 2, 
			'has_archive' => true, 
			'supports' => array( 'title', 'editor', 'thumbnail'), 
			//'taxonomies' => array('company_type')
		); 
		register_post_type('city', $args); 
	}