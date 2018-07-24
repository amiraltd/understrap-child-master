jQuery(function($) {
	
	/**
	 *
	 * Nice select init
	 *
	**/
	
	$('select').niceSelect();
	
	/**
	 *
	 * Обработка форм
	 *
	**/

	$('.af_btn').on('click', function (e)
	{
		e.preventDefault();
		//e.stopPropagation();
		
		var param = {'action' : 'af_ajax_handler', 'nonce_code' : template.nonce, 'ajaxurl' : template.url};

		af_form_parse($(this),param);
	})
	
}) 


/**
 *
 * Вывод yandex карты
 *
**/

function get_map( longitude, latitude, zoom_val )
{
  ymaps.ready(init);
  var myMap,
      myPlacemark;

  function init() {
      myMap = new ymaps.Map("map", {
          center: [latitude, longitude],
          zoom: zoom_val
      });
      
      myPlacemark = new ymaps.Placemark([latitude, longitude], {
          //hintContent: '',
          //balloonContent: ''
      });
      
      myMap.geoObjects.add(myPlacemark);
  }
}