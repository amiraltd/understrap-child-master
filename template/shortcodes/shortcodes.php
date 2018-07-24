<?php
	
	function connectShortcode()
	{
		$path = dirname( __FILE__ );
	
	  $files = scandir( $path );
	  
	  //Удаляем ненужное
		unset($files[0],$files[1]);
			
	  foreach( $files as $file )
	  {
		  if( $file != 'shortcodes.php' ) {
			  
			  require_once $path . '/' . $file;
			  
			  $name     = str_replace('.php', '', $file);
			  $nameFunc = str_replace('-', '_', $name);
			  
			  add_shortcode($name, $nameFunc . '_short');			  
		  }
	  }
	}
	
	connectShortcode();