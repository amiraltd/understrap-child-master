<?php
	add_action( 'wp_footer', function() { ?>
	
	<!-- Modal -->
	<div class="modal fade" id="successfullyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Ваше объявление успешно добавлено!</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        ...
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">закрыть</button>
	      </div>
	    </div>
	  </div>
	</div>
	
	<?php }, 1 );
		
		
	