$(function() {

	$('div.edit').click(function() {

		var entryUrl = $("#entry_url").val();

		// classでonを持っているかチェック
		if (!$(this).hasClass('on')) {
			// 編集可能時はclassでonをつける
			$(this).addClass('on');
			var txt = $(this).text();
			var ele = $(this)[0].id;

			if (ele !== null && ele !== undefined && ele !== "") {

				var elementName = ele.split("_")[0];
				var id = ele.split("_")[1];

				$(this).html('<input type="text" value="' + txt + '" size="1" style="text-align:right;width:40px;" />');
				// 同時にinputにフォーカスをする
				$('div.edit > input').focus().blur(function() {
					var inputVal = $(this).val();
					var regex = new RegExp(/^\d{1,3}$/);
					if (inputVal.match(regex) !== null) {

						var postObj = new Object();
						postObj["id"] = id;
						postObj["ratio"] = inputVal;

						$.ajax({
							type : "POST",
							url : entryUrl+"admin/updateservice",
							data : postObj,
							success : function(res) {
								window.location.reload();
							},
							 error: function(XMLHttpRequest, textStatus, errorThrown) {
						            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
						            console.log("textStatus : " + textStatus);
						            console.log("errorThrown : " + errorThrown.message);
							 }
						});

						// 編集が終わったらtextで置き換える
						$(this).parent().removeClass('on').text(inputVal);

					}

				});
			}
		};
	});

	$("#service_add_button_id").click(function(){
		$("#service_add").dialog('open');
	});

	$('#service_add').dialog({
		  autoOpen: false,
		  title: '新サービス名を登録',
		  closeOnEscape: false,
		  modal: true
	});

});