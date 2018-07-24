<?php
	
	/**
	 *
	 * Работа с постами
	 *
	**/
		
	class Post {
		
		static function getParam( $param )
		{
			( isset($param['post_id']) ) ? $post_id = $param['post_id']: $post_id = get_the_id();
			
			$post_type = get_post_type( $post_id );
			$meta      = '';
			$content   = '';

			if( $param['meta'] && $post_type == 'realty' )
			{
				$meta = new AcfFieldsParse( ['key' => '__realty_param', 'data' => $post_id] );
				if( $meta ) $meta = $meta->param;
			}
			
			//ob_start(); the_content();
			//$content = ob_get_clean();
			
			$data['title']   = get_the_title($post_id);
			$data['link']    = get_the_permalink($post_id);
			$data['post_id'] = $post_id;
			//$data['content'] = $content;
			$data['excerpt'] = get_the_excerpt($post_id);
			$data['thumb']   = get_the_post_thumbnail_url( $post_id, 'realty-thumb' ); //Миниатюра записи;
			$data['meta']    = $meta;
			
			return $data;
		}
		
		static function getRealtyFromCity( $cityId, $param = false )
		{
			$queryParam = ['per_page' => 8];
			
			$queryData = self::getRealtyFromCityQuery( $cityId, $queryParam );
			
			ob_start();
			
			while( $queryData->have_posts() ) : $queryData->the_post();
					
				Loop::getRealtyBox($param);
				
			endwhile;
			
			$realtyPosts = ob_get_clean();
			
			wp_reset_postdata();
			
			return ['posts' => $realtyPosts,'found_posts' => $queryData->found_posts];
		}
		
		static function getRealtyFromCityQuery( $cityId, $queryParam )
		{
			$args = [
				'post_type'			 => 'realty',
				'posts_per_page' => $queryParam['per_page'],
				'meta_query'   => array(
					array(
						'key'     => '__realty_param_city',
						'value'   => $cityId,
						'compare' => 'LIKE',
					),
				),
			];
			
			return DB::getRequest('wp_query',['data' => $args]);
		}
		
	}