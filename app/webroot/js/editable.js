$(function() {

	$('div.edit').click(function() {
		// classでonを持っているかチェック
		if (!$(this).hasClass('on')) {
			// 編集可能時はclassでonをつける
			$(this).addClass('on');
			var txt = $(this).text();
			var ele = $(this)[0].id;

			if (ele !== null && ele !== undefined && ele !== "") {

				var elementName = ele.split("_")[0];
				var worktimeId = ele.split("_")[1];

				$(this).html('<input type="text" value="' + txt + '" />');
				// 同時にinputにフォーカスをする
				$('div.edit > input').focus().blur(function() {
					var inputVal = $(this).val();
					var regex = new RegExp(/^\d{4}\-\d{2}\-\d{2} \d{2}\:\d{2}:\d{2}$/);
					if (inputVal.match(regex) !== null) {

						var updateFieldKey = elementName + "_time";
						var postObj = new Object();
						postObj["id"] = worktimeId;
						postObj[updateFieldKey] = inputVal;

						$.ajax({
							type : "POST",
							url : "/fm-liveworks/admin/updateworkdata",
							data : postObj,
							success : function(res) {
								window.location.reload();
							}
						});


						// 編集が終わったらtextで置き換える
						$(this).parent().removeClass('on').text(inputVal);

					}

				});
			}
		};
	});
});