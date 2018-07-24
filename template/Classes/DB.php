<?php
	
	/**
	 *
	 * Заппросы в БД
	 *
	**/
	
	class DB {
		
		protected $name;
		protected $param;
		
		public function __construct($name, $param)
		{
			$this->name  = $name;
			$this->param = $param;
		}
		
		static function getRequest($name, $param)
		{
			$request = new DB($name, $param);
			
			return $request->request();
		}
		
		public function request()
		{
			return $this->{$this->name}();
		}
		
		/**
		 *
		 * Получаем посты
		 *
		**/
	
		protected function wp_query()
		{
			$request = new WP_query( $this->param['data'] );
			
			return $request;
		}
		
		/**
		 *
		 * Получаем категории ( таксономия )
		 *
		**/
		
		protected function get_terms()
		{
			$request = get_terms( $this->param['data'] );
			
			return $request;
		}
	}