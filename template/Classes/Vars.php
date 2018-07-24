<?php
	
	/**
	 *
	 * Настройки шаблона
	 *
	**/
	
	class Vars {
		
		static $maxFileSize = 10 * 1024 * 1024; // (байт) Максимальный размер файла (10мб) - Для JS
		static $jQuery      = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js';
		
		/**
		 *
		 * Если нету миниатюры
		 *
		**/
		
		static function defImage()
		{
			//return self::assets('img/noimage.jpg');
		}
		
		/**
		 *
		 * Для подключения файлов стилей и скриптов
		 *
		**/
	
		static function getCssJsFiles()
		{
			$data = [
				'fancybox' => [
					'css' 			=> 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css',
					'js' 			  => 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js',
					'ver' 			=> '1.0.0',
					'in_footer' => true
				],
				'nice' => [
					'css' 			=> 'https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css',
					'js' 			  => 'https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js',
					'ver' 			=> '1.0.0',
					'in_footer' => true
				],
				'ya-maps' => [
					'js' 			  => 'https://api-maps.yandex.ru/2.1/?lang=ru_RU',
					'ver' 			=> '1.0.0',
					'in_footer' => true
				],
			];
			
			return $data;
		}
	}