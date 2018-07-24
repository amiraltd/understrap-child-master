<?php
	add_action( 'init', 'property_tax', 0 );
	 
	function property_tax() {
	     
	  $labels = array(
	    'name' => _x( 'Тип недвижимости', 'taxonomy general name' ),
	    'singular_name' => _x( 'Topic', 'taxonomy singular name' ),
	    'search_items' =>  __( 'Искать' ),
	    'all_items' => __( 'Все' ),
	    'parent_item' => __( 'Parent Topic' ),
	    'parent_item_colon' => __( 'Parent Topic:' ),
	    'edit_item' => __( 'Edit Topic' ),
	    'update_item' => __( 'Update Topic' ),
	    'add_new_item' => __( 'Добавить' ),
	    'new_item_name' => __( 'New Topic Name' ),
	    'menu_name' => __( 'Тип недвижимости' ),
	  );
	 
		// Теперь регистрируем таксономию
	  register_taxonomy('property_tax','realty', array(
	    'labels'            => $labels,
	    'public'                => true,
			//'publicly_queryable'    => null, // равен аргументу public
	    'hierarchical'      =>  true,
	    'show_in_nav_menus' =>  true,
	    'has_archive'       =>  true,
	    //'query_var' => true,
	    'rewrite' => array( 'slug' => 'property' )
	  ));
	}