<?php
	
	require get_theme_file_path() . '/template/shortcodes/shortcodes.php';
	
	//Свои размеры картинок для WP
	add_image_size( 'realty-thumb', 260, 200, true );
	
	/**
	 *
	 * Custom print to array
	 *
	 **/
	 
	function dump( $args = false )
	{
		if( !$args || empty($args) ) return $args;
		
		echo '<pre style="background:#000;color:#fff;padding:20px;">';
		print_r($args);
		echo '</pre>';
	}

	/**
	 *
	 * Получаем координаты по адресу
	 *
	**/

	function getMapPosition( $address )
	{
		//Запрос к Яндексу
		$response = wp_remote_request('https://geocode-maps.yandex.ru/1.x/?format=json&geocode=' . $address);
		$body     = wp_remote_retrieve_body( $response );
		
		//Разбор
		$result = explode(' ',json_decode( $body )->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos);
		
		$data['longitude']  = $result[0];
		$data['latitude'] = $result[1];
		
		return $data;
	}
	

