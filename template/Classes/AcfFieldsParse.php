<?php
	
	/**
	 *
	 * Парсим мета поля поста ( ACF )
	 *
	**/
	
	class AcfFieldsParse {
		
		public $param;
		
		public function __construct( $data = false )
		{
			if( !$data ) return;
			
			$object = get_field_object($data['key'], $data['data']);
			
			//dump($object);
			
			if( !$object ) return;
			
			$this->param = $this->acf_fields_parse($object);
		}
		
		protected function acf_fields_parse( $object )
		{
			foreach( $object['value'] as $key => $item )
			{
				foreach( $object['sub_fields'] as $i => $field )
				{
					if( $key == $field['name'] )
					{
						$args[$key]['label'] = $field['label'];
						$args[$key]['value'] = $item;
					}
				}
			}
			return $args;
		}
	}