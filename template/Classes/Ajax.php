<?php
	/**
	 *
	 * Обработчик Ajax запросов
	 *
	**/
	
	class Ajax {
		
		public function __construct()
		{
			//dump
			if( !isset($_POST) || empty($_POST) ) return;
			
			//проверяем nonce код, если проверка не пройдена прерываем обработку
			check_ajax_referer( 'my-nonce', 'nonce_code' );
			
			//Определяем какой метод
			$method = $_POST['form_type'];
			
			//Удаляем ненужное
			unset($_POST['form_type'],$_POST['nonce_code']);
			
			$this->$method( $_POST, $_FILES );
			
			wp_die();
		}
		
		//Добавить недвижимость
		protected function add_realty( $data, $files )
		{
			$result = true;
			
			if( $result ) {
				
				$type         = 'good';
				$args['text'] = 'Объявление отправлено на модерацию!';
				
			} else {
		
				$type 				= 'error';
				$args['text'] = 'Ошибка!';
			}
			
			wp_die( self::json_reply($type,$args) );
		}
		
		//Генератор ответов
		static function json_reply( $type, $args = false )
		{
			$args['reply_type'] = $type;

			ob_start();
				print_r( json_encode($args) );
			return ob_get_clean();
		}
	}
	
	function af_ajax()
	{
		$ajax = new Ajax;
	}
	
	if( wp_doing_ajax() )
	{
		add_action('wp_ajax_af_ajax_handler', 'af_ajax');
		add_action('wp_ajax_nopriv_af_ajax_handler', 'af_ajax');
	}