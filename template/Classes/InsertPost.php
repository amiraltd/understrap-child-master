<?php
	
	/**
	 *
	 * Добавление записей в БД
	 *
	**/
		
	class InsertPost {
		
		protected $post_type;
		protected $data;
		protected $files;
		
		public function __construct($args, $files)
		{
			//dump($args);
			
			$this->post_type = $args['post_type'];
			$this->data      = $args;
			$this->files     = $files;
			
			$this->set_param();
		}
		
		//Установка параметров
		protected function set_param()
		{
			if( $this->post_type == 'realty' )
			{
				$this->meta_key      = '__realty_param';
				$this->taxonomy      = 'property_tax';
				$this->data['title'] = $this->data['property']['text'].', '.$this->data['address'];
				$this->data['city']  = $this->data['city']['value'];
				$this->terms         = $this->data['property']['value'];
			}
		}
		
		static function start( $args, $files = false )
		{
			$class = new InsertPost($args, $files );
			
			//Вставка записи в базу данных ( возврящает ID записи ) 
			$result = $class->insert();
			
			if( !$result ) return $result;
			
			//Записываем данные в мета поля
			$class->add_post_meta( $result );
			
			//Закрепляем запись за нужными рубриками
			$class->add_post_taxonomy( $result );
			
			//Загрузка файлов на сервер и закрепляем за нужной записью
			$class->media_upload( $result );
			
			return $result;
		}
		
		//Вставка записи в базу данных
		public function insert( $data )
		{
			if( empty($this->post_type) || empty($this->data['title']) ) return false;
			
			$post_data = array(
				'post_type'     => $this->post_type,
				'post_title'    => wp_strip_all_tags( $this->data['title'] ),
				'post_status'   => 'draft',
				//'post_author'   => 1,
				//'post_category' => array( 8,39 )
			);
			
			if( !empty($this->data['content']) ) $post_data['post_content'] = $this->data['content'];
			
			return wp_insert_post( $post_data );
		}
		
		//Закрепляем запись за нужными рубриками
		protected function add_post_taxonomy( $post_id )
		{
			wp_set_post_terms( $post_id, $this->terms, $this->taxonomy, true );
		}
		
		//Записываем данные в мета поля
		protected function add_post_meta( $post_id )
		{
			foreach( $this->data as $key => $item )
			{
				if( self::field_check( $key ) ) $value[$key] = wp_strip_all_tags($item);
			}
			
			//Сохраняем
			update_field( $this->meta_key, $value, $post_id );
		}
		
		//Загрузка файлов на сервер и закрепляем за нужной записью
		protected function media_upload( $post_id )
		{
			if( $this->post_type == 'realty' )
			{
				//Галерея
				$this->add_gallery( $post_id, $this->files['gallery'] );
			}
			
			if( isset($this->files['thumbnail']) )
			{
				//Миниатюра
				$this->add_thumbnail( $post_id, $this->files['thumbnail'] );
			}
		}
		
		//Создаем галерею
		protected function add_gallery( $post_id, $files_data )
		{
			//Загрузка файла на сервер
			$attachments_id = $this->files_upload( $files_data );
			
			//Добавляем в галерею
			update_field( $this->meta_key, ['gallery' => $attachments_id], $post_id );
		}
		
		//Добавляем миниатюру записи
		protected function add_thumbnail( $post_id, $files_data )
		{
			//Загрузка файла на сервер
			$attachments_id = $this->files_upload( $files_data );
	
			//Добавляем миниатюру
			update_post_meta( $post_id, '_thumbnail_id', $attachments_id[0] );
		}
		
		//Загрузка файлов на сервер
		protected function files_upload( $files_data )
		{
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			
			foreach( $files_data['name'] as $k => $file )
			{
				$fileType = $files_data['type'][$k];
				
				//Тип файла ( если не подходит пропускаем )
				if( $fileType != 'image/png' && $fileType != 'image/jpeg' ) continue;
				
				$file_array['name']     = $file;
				$file_array['tmp_name'] = $files_data['tmp_name'][$k];
				
				//Грузим файл
				$attachments_id[]       = media_handle_sideload( $file_array, 0 );
				
				// удалим временный файл
				@unlink( $file_array['tmp_name'] );
			}
			
			return $attachments_id;
		}
		
		//Проверка на исключение ( ненужные поля )
		static function field_check( $key )
		{
			$status = true;
			
			$exception = [
				'content',
				//'city',
				'title',
				'post_type',
			];
			
			foreach( $exception as $item )
			{
				if( $key == $item ) $status = false;
			}
			
			return $status;
		}
	}