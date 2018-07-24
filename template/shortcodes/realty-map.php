<?php
	
	function realty_map_short( $attr )
	{
		$data    = Post::getParam( ['post_id' => $attr['id'], 'meta' => true] );		
		$city    = get_the_title( $data['meta']['city']['value'] );
		$address = $data['meta']['address']['value'];
		$map     = getMapPosition($city . ',' . $address);
		?>
		<div id="map" style="width: 100%; height: 450px"></div>
		<script>
			jQuery( document ).ready( function($) {
				get_map( <?= $map['longitude'] .', '. $map['latitude'] .', '. $attr['zoom']  ?>);
			})
		</script>
		<?php
	}