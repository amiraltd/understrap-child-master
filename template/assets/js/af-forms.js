jQuery(function($) {
	
	/**
	 *
	 * Убираем сообщения об ошибке ( в формах )
	 *
	**/

	$('.af_form_field').on('click', function (e) {
		$(this).removeClass('field-error');
	})
	
})

/**
 *
 * Парсим форму
 *
**/
	
function af_form_parse( thisBtn, param )
{	
	var thisForm  = thisBtn.closest('form');
			formData  = new FormData();
			btnStatus = thisBtn.attr('data-check');
	
	if( btnStatus != 'on' ) return;

	formData.append('nonce_code', param.nonce_code);
	formData.append('form_type', thisForm.attr('data-form'));		

	var status = af_form_parseFields(formData,thisForm);
	
	if( status )
	{
		thisBtn.attr('data-check','off');
		
		af_send_ajax(formData,param,thisBtn,thisForm);
		
		formClear(thisForm);
	}
}


/**
 *
 * Отправка ajax
 *
**/
	
function af_send_ajax(formData,param,thisBtn,thisForm)
{

	$.ajax({
		url         : param.ajaxurl + '?action=' + param.action,
		data        : formData,
		method      : 'POST',
		contentType : false,
		processData : false,
		//beforeSend  : function(){ thisBtn.attr('data-check','off'); },
		success     : function( response ) {
			
			thisBtn.attr('data-check','on');
			
			af_ajax_response(param,thisBtn,thisForm,response);
			
			//psa_response_parce(response,thisBtn,thisForm);

		},
	}) //End Ajax
}

/**
 *
 * Обработчик ответа
 *
**/

function af_ajax_response(param,thisBtn,thisForm,response)
{
	//ОБъект ответа
	var obj = JSON.parse(response);
	
	$('#successfullyModal h5.modal-title').text(obj.text);
	$('#successfullyModal').modal('show');
} 

/**
 *
 * Очистка формы после ajax
 *
**/
	
function formClear(thisForm)
{
	var input    = thisForm.find('input');
			textarea = thisForm.find('textarea');
			
	$(input).each(function( i ) {
		if( !$(this).hasClass('d-none') ) $(this).val('');
	})
	$(textarea).each(function( i ) {
		if( !$(this).hasClass('d-none') ) $(this).val('');
	})
	
	$('.uploadImagesList').html('');
}

 
/**
 *
 * Парсим поля c данными
 *
**/

function af_form_parseFields(formData,thisForm)
{
	var itemsBlock = thisForm.find('.af_form_field');
	    validate   = true;
	    validate2  = true;
	
	$(itemsBlock).each(function( i )
	{
		var th      = $(this);
				type    = th.attr('data-type');
				itemKey = th.attr('data-key');
				status  = th.attr('data-status');
		    item    = th.find('[data-field="af-field"]');

		if( item.length <= 0 ) return;
		
		if( type != 'files' && type != 'checkbox' && type != 'select'  ) {

			if( item.val().length > 0 )
			{
				formData.append(itemKey, item.val() );
				
			} else {
				
				if( status == 'required' )
				{
					th.addClass('field-error');
					validate = false;
				}
			}
			
		} //End if type != 'files'
		
		else if ( type == 'files' ) {

			af_form_parseFiles(formData,th)
			
		} 
		
		else if ( type == 'checkbox' ) {
			
			//validate = af_form_parseCheckbox(formData,th);

		}
		
		else if ( type == 'select' ) {
			 
			validate = af_form_parseSelect(formData,th);
		}
		
		if( !validate ) validate2 = false;

	})
	
	return validate2;
}


/**
 *
 * Парсим поле с select
 *
**/
	
function af_form_parseSelect(formData,th)
{
	var item     = th.find('.option.selected');
			status   = th.attr('data-status');
			validate = true;
			
	if( item.text() == 'Выбрать' )
	{
		if( status == 'required' )
		{
			th.addClass('field-error');
			validate = false;
		}
	}
	
	formData.append(th.attr('data-key') + '[value]', item.attr('data-value') );
	formData.append(th.attr('data-key') + '[text]', item.text() );
	
	return validate; 
}


/**
 *
 * Парсим поле с checkbox
 *
**/
	
function af_form_parseCheckbox(formData,th)
{
	var validate  = true;
			validate2 = false;
	
	var items = th.find('[data-field="af-field"]');
	
	if( items.length <= 0 ) return;
	
	$(items).each(function( i ) {
		
		if( $(this).prop('checked') )
		{
			validate2 = true;
			validate  = true;
			formData.append(th.attr('data-key') + '[]', $(this).val() );
			
		} else {
			
			if( th.attr('data-status') == 'required' && !validate2 )
			{
				th.addClass('field-error');
				validate = false;
			}
			
		}
		
	})
	
	return validate;
}

/**
 *
 * Парсим объект с файлами
 *
**/

function af_form_parseFiles(formData,th)
{
	var keys = th.attr('data-key');
      
	var obj = getObject(keys);
	
	$.each( obj, function( key, value ) {	
						  
    	var status = file_check( value.type );

			if( status == 'on' && value.size < template.maxFileSize )
			{
				formData.append(keys + '[]', value );
			}
  });
}

/* Старый парсер
	
function af_form_parseFiles(formData,th)
{
	var item = th.find('[data-field="af-field"]');
      keys = th.attr('data-key');
	
	$.each( item[0].files, function( key, value ){
	    	
    	var status = file_check( value.type );

			if( status == 'on' && value.size < template.maxFileSize )
			{
				formData.append(keys + '['+key+']', value );
			}
  });
  
  return formData;
} */


/**
 *
 * Проверка типа файла
 *
**/

function file_check( type )
{
	var status = 'on';
	
	var args = [
		'application/x-msdownload',
		'text/php',
		'text/x-asp',
		'text/html',
		'application/javascript',
		'application/json',
		'text/x-sql',
		'video/mp4',
		'video/ogg',
		'video/webm',
		//'image/svg+xml'
	];
	
	$(args).each(function(key,val)
	{
		if( type == val ) status = 'off';
	})
	
	return status;
}

/**
 *
 * Добавляем загруженные файлы в объект
 *
**/

function files_handler( thisF, files, obj )
{
	thisF.closest('.files-block').find('.uploadImagesList').html('');
	
	for (var i = 0; i < files.length; i++) {

      var file   = files[i];
          status = file_check( file.type );

			if( status == 'on' && file.size < template.maxFileSize )
			{
				//previewImages(thisF,files[i]);
				obj[file.name] = file;
				
			} else {
				
				alert( 'Размер файла не должен превышать 10 Мб' );
				continue;
			}

/*
      if ( !file.type.match(/image\/(jpeg|jpg|png|gif)/) ) {
          alert( '' );
          continue;
      }

      if ( file.size > maxFileSize ) {
          alert( '' );
          continue;
      }
*/

      
  }
}

/**
 *
 * Создаем преью загруженный файлов
 *
**/

function previewImages(thisF,file) {
  	
  	var reader     = new FileReader();
  			imagesList = thisF.thisF.closest('.files-block').find('.uploadImagesList');
  	    //imagesList = thisF.closest('.files-block').find('.uploadImagesList');
		
    reader.addEventListener('load', function(event) {
			
			/* if( !file.type.match(/image\/(jpeg|jpg|png|gif)/) ) img = event.target.result; */

			imagesList.append(create_img(event.target.result,file.name));
    });
    
    reader.readAsDataURL(file);
} 

function create_img(src,title)
{
  title = title.replace(/"/g,"&quot;");
  return '<span class="image__list-item"><span class="image__list--box"><img src="'+src+'" title="'+title+'" alt="'+title+'" /><span class="image__list--text">'+title+'</span></span><span class="image__list--delete"></span></span>';
}
		

/**
 *
 * Возврящаем объект - Имя строковая переменная
 *
**/
 
 //function getObject(s) { return [s, eval(s)]; }
 function getObject(s) { return eval(s); }
 
/**
 *
 * Удяляем картинки из загрузки файлов
 *
**/
 
 $('.uploadImagesList').on('click', '.image__list--delete', function (e) {
	 
	 var objName = $(this).closest('.af_form_field').attr('data-key');
	 		 obj     = getObject(objName);
	 		 item    = $(this).closest('.image__list-item');
	 		 name    = item.find('img').attr('title');
	 		 cloud_l = $(this).closest('.files-block').find('.cloud_link--block');
	 
	 delete obj[name];
	 
	 if( Object.keys(obj).length <= 0 )
	 {
		 cloud_l.removeClass('on');
		 cloud_l.children('input').attr('readonly', false);
	 }
	 
	 item.fadeOut('fast').remove();
 })