<?php
	/**
	 *
	 * Поключаем скрипты и стили ( файлы определены в классе Vars (template/Classes/Vars.php) )
	 *
	**/

	add_action( 'wp_enqueue_scripts', function() {
		
		foreach( Vars::getCssJsFiles() as $key => $file )
		{
			if( !isset($file['css']) && !isset($file['js']) ) continue;
			
			( isset($file['ver']) )       ? $ver       = $file['ver']       : $ver = '1.0.0';
			( isset($file['in_footer']) ) ? $in_footer = $file['in_footer'] : $in_footer = true;
			
			if( !empty($file['css']) ) wp_enqueue_style ( $key, $file['css'], array(), $ver );
			if( !empty($file['js'])  ) wp_enqueue_script( $key . '-js', $file['js'],  array(), $ver, $in_footer );
		}

		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', Vars::$jQuery);
		wp_enqueue_script( 'jquery' );

		$data = [
			'url'         => admin_url('admin-ajax.php'),
			'nonce'  		  => wp_create_nonce('my-nonce'),
			'maxFileSize'	=> Vars::$maxFileSize, // (байт) Максимальный размер файла (10мб)
		];
		
		wp_localize_script( 'nice-js', 'template', $data );
		
	});
	

