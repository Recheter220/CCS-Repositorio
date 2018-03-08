<script>/*
	function modalAcaoResp(resp) {
		var old = $("#modalAcao").data("resposta");
		if (old == -1) {
			$("#modalAcao").data("resposta", resp);
		}
	}*/
</script>

<!-- Modal Ação -->
<div class="modal fade" id="modalAcao" role="dialog" data-resposta="-1">
	<div class="modal-dialog">
		<!-- Modal Ação content-->
		<div class="modal-content">
			<div class="modal-header" id="modalAcao-title">
				
			</div>
			<div class="modal-body" id="modalAcao-body">
				
			</div>
			<div class="modal-footer" id="modalAcao-footer">
				<button data-dismiss="modal" class="btn-lg btn btn-success" onclick="$('#modalAcao').data('resposta', 1)"> Sim </button>
				<button data-dismiss="modal" class="btn-lg btn btn-danger"  onclick="$('#modalAcao').data('resposta', 0)"> Não </button> 
			</div>
		</div>
	</div>
</div>