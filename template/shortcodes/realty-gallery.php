<?php
	
	function realty_gallery_short( $attr )
	{
		$data = Post::getParam( ['post_id' => $attr['id'], 'meta' => true] ); //dump($data['meta']['gallery']['value']); 
					
		foreach( $data['meta']['gallery']['value'] as $image )
		{
			$img .= '<div class="col-md-'.$attr['col'].'"><a data-fancybox="gallery" href="'.$image['url'].'" class="gallery__item d-flex"><img src="'.$image['sizes']['realty-thumb'].'"></a></div>';
		}
		
		return '<div class="row">'.$img.'</div>';
	}