<?php
	/**
	 *
	 * Конструктор форм
	 *
	**/
	
	class Forms {
		
		protected $formFields;
		protected $formKey;
		
		public function __construct( $type )
		{
			$name = 'fields_' . $type;
			
			$this->formKey    = $type;
			$this->formFields = $this->$name();
		}
		
		static function build( $type )
		{
			$form = new Forms($type);
		
			return $form->start();
		}
		
		//Собираем форму
		public function start()
		{
			$fields = ''; $btn = '';
			
			foreach( $this->formFields['fields'] as $key => $form )
			{
				if( $key == 'af_btn' )
				{
					$btn = self::btn_generate( $key, $form ); continue;
				}
				
				$form['key'] = $key;
				
				$fields .= $this->template_default($form);
			}
			
			$html = '<form class="af_form" data-form="'.$this->formKey.'" action="af_ajax_handler"><div class="row">'. $fields . $btn .'</div></form>';
			
			return $html;
		}
		
		static function btn_generate( $key, $form )
		{
			return '<div class="af_form--btn"><button data-check="on" class="'.$key.' '.$form['class'].'" type="submit">'.$form['text'].'</button></div>';
		}
		
		//Вывод полей формы
		public function template_default( $form )
		{
			( isset($form['hidden']) ) ? $hidden = ' d-none' : $hidden = '';
			
			$require = $dataStatus = '';
			
			$type = 'get_' . $form['type'];
			
			if( $form['require'] )
			{
				$require    = ' <span class="require">*</span>';
				$dataStatus = ' data-status="required"';
			}
			
			ob_start(); ?>
			
			<div class="af_form_field <?= $form['class'] . $hidden ?>"<?= $dataStatus ?> data-type="<?= $form['type'] ?>" data-key="<?= $form['key'] ?>">
				
				<?php if( isset($form['title']) ): ?>
			  	<div class="form--input-title">
			  		<label for="<?= $form['key'] ?>"><?= $form['title'].' '.$require ?></label>
			  	</div>
			  <?php endif; ?>
			  
		  	<div class="af_form_field--block" >
			  	<?php $this->$type($form) ?>
				</div>
					
			</div><!-- //End af_form_field -->
			<?php return ob_get_clean();
		}
		
		//Вывод checkbox
		public function get_checkbox($form)
		{
			?>
			<ul class="<?= $form['key'] ?>">
				<?php foreach( $form['value'] as $key => $item ) $this->get_checkbox_item($key,$item); ?>
			</ul>
			<?php
		}
		
		//Вывод checkbox
		public function get_checkbox_item($key,$item) {
			?>
			<li>
				<input id="term_<?= $key ?>" data-field="af-field" type="checkbox" class="checkbox checkbox-list" value="<?= $key ?>">
				<label for="term_<?= $key ?>"><?= $item ?></label>
			</li>
			<?
		}
		
		//Вывод select
		public function get_select($form)
		{
			?>
			<select class="form-control <?= $form['key'] ?>" data-field="af-field">
				<option data-display="Выбрать">Выбрать</option>
				<?php foreach( $form['value'] as $key => $item ) $this->get_select_item($key,$item); ?>
			</select>
			<?php
		}
		
		//Вывод select
		public function get_select_item($key,$item) {
			?>
			<option value="<?= $key ?>"><?= $item ?></option>
			<?
		}
		
		//Вывод input
		public function get_input($form)
		{
			( isset($form['placeholder']) ) ? $ph     = 'placeholder="'.$form['placeholder'].'" ' : $ph = '';
			( isset($form['value']) )       ? $value  = 'value="'.$form['value'].'"' : $value = '';
			( isset($form['hidden']) )      ? $hidden = 'd-none' : $hidden = '';
			
			?>
			<div class="input_item">
				<input type="text" id="<?= $form['key'] ?>" class="form-control <?= $hidden ?>" data-field="af-field" autocomplete="on" <?= $ph . $value ?>>
			</div>
			<?php
		}
		
		//Вывод textarea
		public function get_textarea($form)
		{
			$placeholder = 'placeholder="'.$form['placeholder'].'"';
			?>
			<textarea rows="<?= $form['rows'] ?>" id="<?= $form['key'] ?>" class="form-control" data-field="af-field" autocomplete="on" <?= $placeholder ?>></textarea>
			<?php
		}
		
		//Блок для загрузки файлов
		public function get_files($form)
		{
			$key = $form['key'];
			
			( $form['multiple'] ) ? $multiple = 'multiple="true"' : $multiple = '';
			?>
			<div class="files-block">
        
        <input id="<?= $key ?>" data-field="af-field" type="file" class="form-input" <?= $multiple ?>>
        
        <label for="<?= $key ?>">
        	<?= $form['label_text'] ?>
          <span class="s"><span class="icon-plus icon-secondary-btn"></span> Выберите файл</span>
        </label>

        <div class="uploadImagesList"></div>
	    	<script>
		    	var <?= $key ?> = {};
		    	
		    	$('#<?= $key ?>').on('change', function () {
							
							files_handler($(this),this.files,<?= $key ?>);

					    var thisF_<?= $key ?> = { thisF: $(this) };
					    		
					    $.each( <?= $key ?>, function( key, value ) {
					    		previewImages(thisF_<?= $key ?>,value);
					    });
					    
					    $(this).val('');
					});
	    	</script>  
      </div>
			<?php
		}
		
		/**
		 *
		 * Поля формы
		 *
		**/
		
		public function fields_add_realty()
		{
			$fields = [
					'title' => 'Разместить объявление',
					'fields' => [
						'area' => [
							'type'        => 'input',
							'require'     => true,
							'class'       => 'col-md-3',
							'title'       => 'Площадь (м2)',
							'placeholder' => 'Например, 90',
						],
						'living_space' => [
							'type'        => 'input',
							'require'     => false,
							'class'       => 'col-md-3',
							'title'       => 'Жилая площадь (м2)',
							'placeholder' => 'Например, 30',
						],
						'address' => [
							'type'        => 'input',
							'require'     => true,
							'class'       => 'col-md-6',
							'title'       => 'Адрес',
							'placeholder' => 'Например, Лермонтова 1',
						],
						'floor' => [
							'type'        => 'input',
							'require'     => false,
							'class'       => 'col-md-3',
							'title'       => 'Этаж',
							'placeholder' => 'Например, 1',
						],
						'city' => [
							'type'        => 'select',
							'require'     => true,
							'class'       => 'col-md-3',
							'title'       => 'Город',
							'placeholder' => 'Например, Москва',
							'value'       => self::getPostsCity(),
						],
						'property' => [
							'type'        => 'select',
							'require'     => true,
							'class'       => 'col-md-3',
							'title'       => 'Тип недвижимости',
							'placeholder' => 'Например, Москва',
							'value'       => self::getRealtyTax(),
						],
						'price' => [
							'type'        => 'input',
							'require'     => true,
							'class'       => 'col-md-3',
							'title'       => 'Стоимость (руб.)',
							'placeholder' => 'Например, 6 000 000',
						],
						'content' => [
							'type'        => 'textarea',
							'require'     => false,
							'class'       => 'col-md-12',
							'title'       => 'Описание',
							'placeholder' => 'Например, ...',
							'rows'        => 4,
						],
						'thumbnail' => [
							'type'        => 'files',
							'require'     => false,
							'class'       => 'col-md-5',
							'multiple'    => false,
							'label_text'  => 'Перетащите фото сюда',
							'title'       => 'Основное фото',
						],
						'gallery' => [
							'type'        => 'files',
							'require'     => false,
							'class'       => 'col-md-7',
							'multiple'    => true,
							'label_text'  => 'Перетащите файлы сюда',
							'title'       => 'Галерея',
						],
						'post_type' => [
							'type'        => 'input',
							'require'     => true,
							'class'       => 'col-md-3',
							'hidden'      => true,
							'value'       => 'realty',
						],
						'af_btn' => [
							'class'      => 'btn btn-success',
							'text'       => 'Разместить',
						],
					],
				];
				
			return $fields;
		}

		//Получаем список городов
		static function getPostsCity()
		{
			//Запрос
			$query_data = DB::getRequest('wp_query',['data' => ['post_type' => 'city', 'posts_per_page' => -1]]);

			while( $query_data->have_posts() ) : $query_data->the_post();
				
				$city[get_the_id()] = get_the_title(); 
	
			endwhile;
			
			return $city;
		}
		
		//Получаем список городов
		static function getRealtyTax()
		{
			$terms = DB::getRequest('get_terms',['data' => ['taxonomy' => 'property_tax', 'hide_empty' => false]]);
			
			foreach( $terms as $term )
			{
				$result[$term->term_id] = $term->name;
			}
			return $result;
		}
	}