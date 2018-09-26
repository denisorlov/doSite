<?php

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<button onclick="postAjax('')">postAjax</button>
<button onclick="postAjax('json')">postAjax json</button>
<button onclick="postAjax('script')">postAjax script</button>
<button onclick="postAjax('xml')">postAjax xml</button>
  <button onclick="postAjax('text')">postAjax text</button>
<div id="infoDiv"></div>

<script>
function postAjax(dataType){
	$('#infoDiv').html('');
	$.ajax({
		url: '/user/list?page=30&sort=desc&only_girl=0',             // указываем URL и
		dataType : dataType,                     // тип загружаемых данных
		success: function (data, textStatus) { // вешаем свой обработчик на функцию succes
		   console.dir(arguments);
		} 
	});
}

</script>