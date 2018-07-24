<?php
	
	/**
	 *
	 * Контент постов
	 *
	**/
		
	class Loop {

		static function loop_realty( $param )
		{
			$param['post_type'] = 'realty';
			
			$param = Post::param_parse($param);
			
			return $param;
		}

		static function getRealtyContent( $post_id = false )
		{
			if(!$post_id) $post_id = get_the_id();
			
			$data = Post::getParam( ['post_id' => $post_id, 'meta' => true] );
			
			foreach( $data['meta'] as $key => $param )
			{
				if( $key == 'gallery' ) continue;
				
				if( $key == 'city' )  $param['value'] = get_the_title( $param['value'] );
				if( $key == 'price' ) $param['value'] = number_format($param['value'], 0, ',', ' ') . ' Р.';

				$meta .= '<p>'.$param['label'] . ' - ' . $param['value'].'</p>';
			}
			?>
					
			<h1><?= the_title() ?></h1>
					
			<br>
			
			<?= $meta ?>
					
			<?= do_shortcode('[realty-gallery id="'.$post_id.'" col="3"]') ?>
			
			<?= do_shortcode('[realty-map zoom="16"]') ?>
					
			<br>
			<?php
		}
		
		
		static function getRealtyBox( $param )
		{
			$data = Post::getParam( ['post_id' => get_the_id(), 'meta' => true] );
			
			( isset($param['class']) ) ? $class = $param['class'] : $class = '';
			
			$area = $data['meta'];
			
			//dump($data['meta']);
			?>
			<div class="<?= $class ?>">
				
				<div class="realty__box d-flex">
					<a href="<?= $data['link'] ?>" class="realty__box--content">
						<span class="realty__box--title text-center d-inline-flex"><?= $data['title'] ?></span>
						<span class="realty__box--thumb d-flex">
							<span class="realty__box--price"><?= number_format($data['meta']['price']['value'], 0, ',', ' ') ?> Р.</span>
							<span class="realty__box--thumb-img"><img src="<?= $data['thumb'] ?>" alt="" class="src"></span>
						</span>
						<span class="realty__box--meta d-flex flex-column">
							<?php
								if( is_array($area) ) {
									
									foreach( $area as $key => $item )
									{
										if( !self::metaExcept($key) ) continue;

										echo '<span class="realty__box--meta-item realty-meta--'.$key.'">'.$item['label'].': '.$item['value'].'</span>';
									}
								}
							?>
						</span>
					</a>
				</div>

			</div>
			<?php
		}
		
		static function metaExcept( $key )
		{
			$status = true;
			
			$data = [
				'gallery',
				'city',
				'price',
			];
			
			foreach( $data as $item ) if( $item == $key ) $status = false;
			return $status;
		}
		
	}