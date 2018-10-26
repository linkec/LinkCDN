<?php !defined('IN_APP') && exit('[XDDrive] Access Denied!'); ?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		×
	</button>
	<h4 class="modal-title" id="myModalLabel">查看 {$ip} 的TOP | Freq:<input id="top_freq" value="3" onchange="change_freq()" style="width:25px;">Switch:<input id="top_switch" value="1" onchange="change_switch()" style="width:25px;"></h4>
</div>
<pre id='top' style='font-family:vt7X13,"Courier New";font-size:11px;line-height:14px;word-break:break-all;word-break:break-all;height:500px;color: white;background: black;margin: 0px;border: none;border-radius: 0px;'>

</pre>
<script>
var Top_running = false;
var Top_clock = setInterval(function(){gettop()},3000);
gettop();
function change_freq(){
	clearInterval(Top_clock);
	gettop();
	Top_clock = setInterval(function(){gettop()},$('#top_freq').val()*1000);
}
function change_switch(){
	var tswitch = $('#top_switch').val();
	if(tswitch==1){
		gettop();
		Top_clock = setInterval(function(){gettop()},$('#top_freq').val()*1000);
	}else{
		clearInterval(Top_clock);
	}
}
function gettop(){
	if(Top_running==false){
		Top_running = true;
		$.ajax({
		  url: '/admin/api/',
		  dataType: 'json',
		  data:'url=http://{$node['ip']}:{$node['port']}/gettop&password={#md5($node['password'])#}',
		  success: function(data){
			  if(data.status=='success'){
				$('#top').html(data.data);
			  }else{
				$('#top').html('API Communicate Faild.');
			  }
		  },
		  error:function(){
			$('#top').html('API Communicate Faild.');
		  },
		  complete:function(){
			  Top_running = false;
		  }
		});
	}
	if(!$('#remoteModal').hasClass('in')){
		clearInterval(Top_clock);
	}
}
// gettop();
</script>