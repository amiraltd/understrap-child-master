<?php

	class Autoload {
		
		//Список папок для поиска файлов
		protected $dir = [
			'template',
			//'template/Classes',
		];
		
		//Массив с именами и расположением файлов которые были подключены
		public $files;
		
		public function __construct()
		{
			//Сканируем директорию
			$this->scan( $this->dir );
		}
		
		//Сканируем директорию
		protected function scan( $dir )
		{
			$theme_file_path = get_theme_file_path();
			
			foreach( $dir as $item )
			{
				//Путь до папки с файлами
				$path = $theme_file_path . '/'.$item.'/';
				
				//Пропускаем если папки нету
				if( !is_dir($path) ) continue;
				
				//Сканируем на наличие файлов
				$files = scandir( $path );
				
				//Удаляем ненужное
				unset($files[0],$files[1]);
				
				//Оправляем массив с файлами для подключения
				if( count($files) > 0 ) $this->file_include( $files, $path, $item );
			}
		}
		
		//Подключаем файлы
		protected function file_include( $files, $path, $item )
		{
			foreach( $files as $file )
			{
				//Проверка файла на исключение
				if( !self::exceptions( $file ) ) continue;
				
				//Путь до файла
				$getFile = $path . $file;
				
				if( strpos($file, '.php') != false ) {
					
					//Записываем в массив какой файл был подключен
					$this->files[] = ['name' => $file, 'path' => $getFile];
					
					//Подключаем
					require_once $getFile;
					
				} else {
					
					//Если это папка, запускаем сканирование
					if( is_dir($getFile) ) $this->scan( [$item.'/'.$file] );
				}
			}
		}
		
		//Если нужно исключить определенный файл
		static function exceptions( $name )
		{
			$name = str_replace('.php', '', $name);
			
			//Список файлов которые нужно исключить
			$exceptions = [
				'assets',
				'shortcodes'
			];
			
			foreach( $exceptions as $item )
			{
				if(strtolower($name) == strtolower($item)) return false;
			}
			return true;
		}
		
		public function __destruct()
		{
			//Какие файл были подключены
			//dump($this->files);
		}
	}
	
	$load = new Autoload();
	