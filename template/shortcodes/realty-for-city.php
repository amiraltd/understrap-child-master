<?php
	
	function realty_for_city_short( $attr )
	{
			$city = DB::getRequest('wp_query',['data' => ['post_type' => 'city', 'posts_per_page' => -1]]);

			$i = 1;
			
			while( $city->have_posts() ) : $city->the_post();

			$post_id = get_the_id();
		?>
		
			<?php 
				
				/**
				 *
				 * Вывод недвижимости связанную с текущим городом
				 *
				 * Post::getRealtyFromCity - возвращает массив
				 * Полученные записи		 ( $result['posts'] )
				 * Сколько всего найдено ( $result['found_posts'] )
				 *
				**/
				
				$result = Post::getRealtyFromCity( $post_id, ['class' => 'col-md-3'] );
			?>

			<?php ob_start(); ?>
				<li class="nav-item">
			    <a class="nav-link <?php if($i == 1) echo 'active' ?>" data-toggle="tab" href="#tab<?= $post_id ?>" role="tab">
				    <?= get_the_title( $post_id ) ?> <span class="realty__count"><?= $result['found_posts'] ?></span>
				  </a>
			  </li>
			<?php $tabNav .= ob_get_clean(); ?>
			
			<?php ob_start(); ?>
				<div class="tab-pane fade <?php if($i == 1) echo 'show active' ?>" id="tab<?= $post_id ?>" role="tabpanel">
					<div class="row">
						<?= $result['posts'] ?>
					</div>
				</div>
			<?php $tabContent .= ob_get_clean(); ?>
		
		<?php $i++; endwhile; wp_reset_postdata();?>
		
		<ul class="nav nav-tabs justify-content-center realty__tab" id="myTab" role="tablist">
		  <?= $tabNav ?>
		</ul>
		<div class="tab-content" id="myTabContent">
		  <?= $tabContent ?>
		</div>

		<?php
	}