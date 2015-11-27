$(function() {

	var entryUrl = $("#entry_url").val();

	$("#UserRoomId").change(function(){
		$("#roomChange").submit();
	});

	$("#reserve_dialog_button").click(function(){

		//開始時刻
		$("#UserStartDatePullDownId option").remove();
		var startDateEle =  $("#UserStartDatePullDownId");
		var startDate = Date.create().format('{yyyy}-{MM}-{dd}');
		var startDateEle = makeDateOption(startDateEle, startDate)

		$("#UserStartHourPullDownId option").remove();
		var startHourEle =  $("#UserStartHourPullDownId");
		var startHour = Date.create().format('{yyyy}-{MM}-{dd} 00:00:00');
		var startHoourEle = makeHourOption(startHourEle, startHour);

		//終了時間
		$("#UserEndDatePullDownId option").remove();
		var EndDateEle =  $("#UserEndDatePullDownId");
		var EndDate = Date.create().format('{yyyy}-{MM}-{dd}');
		var EndtDateEle = makeDateOption(EndDateEle, EndDate)

		$("#UserEndHourPullDownId option").remove();
		var EndHourEle =  $("#UserEndHourPullDownId");
		var EndHour = Date.create().format('{yyyy}-{MM}-{dd} 00:00:00');
		var EndHoourEle = makeHourOption(EndHourEle, EndHour);

		$("#time_question").dialog('open');

	});

	$('#time_question').dialog({
		  autoOpen: false,
		  title: 'チャットルーム予約',
		  closeOnEscape: false,
		  modal: true,
		  buttons: {
		    "予約する": function(){
		    	 $("#reserveRoomErrorMessage").html("");
		    	 var errorMessage = checkReserve();
		    	 if( errorMessage !== ""){
		    		 $("#reserveRoomErrorMessage").html(errorMessage);
		    	 }else{
		    		 alert("success");
		    		 $("#roomReserve").submit();
		    	 }
		    }
		  }
	});

	function checkReserve() {

		var userId =$("#UserUserId").val();
		var roomId=$("#UserRoomId").val();

		var startDate = $("#UserStartDatePullDownId").val();
		var startHour = $("#UserStartHourPullDownId").val();
		var startDateTime = startDate + " " + startHour;

		var endDate = $("#UserEndDatePullDownId").val();
		var endHour = $("#UserEndHourPullDownId").val();
		var endDateTime = endDate + " " + endHour;

		var errorMessage="";
		// 開始時刻が過去でないか
		if (isFutureStartTime(startDateTime) === false) {
			errorMessage = "予約開始時間が過去になっています。"
			return errorMessage;
		}

		// 開始日>終了日となっているか
		if (isOrderDate(startDateTime, endDateTime) === false) {
			errorMessage = "終了時刻が開始時刻より過去になっています。"
			return errorMessage;
		}

		// すでに予約済みでないか
		if( canReserveDate(userId,roomId,startDateTime,endDateTime) === false){
			errorMessage = "すでに予約済みの時間帯です。"
			return errorMessage;
		}

		return errorMessage;
	}

	/**
	 * 開始日、終了日が過去か
	 * @param datetime 開始時刻
	 * @return true(OK)/false(不正)
	 */
	function isFutureStartTime(datetime){
		return Date.create(datetime).isFuture();
	}

	/**
	 * 開始時刻<終了時刻となっているか
	 * @param startDateTime 開始時刻
	 * @param endDateTime 終了時刻
	 * @return true(OK)/false(不正)
	 */
	function isOrderDate(startDateTime,endDateTime){
		return Date.create(startDateTime).isBefore(endDateTime);
	}

	/**
	 * 予約済みでないか
	 * @param userId ユーザーID
	 * @param roomId ルームID
	 * @param endDateTime 終了時刻
	 * @return true(OK)/false(不正)
	 */
	function canReserveDate(userId, roomId, startDateTime, endDateTime) {

		var postData = {
			"user_id" : userId,
			"room_id" : roomId,
			"start_reserve_time" : startDateTime,
			"end_reserve_time" : endDateTime
		};

		var canReserve;

		$.ajax({
			type : "POST",
			async:false,
			url : entryUrl + "users/canReserveDate",
			data : postData,
			success : function(res) {
				if (res === "fail") {
					canReserve = false;
				} else {
					canReserve = true;
				}
			}
		});
		return canReserve;
	}

	$("#UserStartTimePullDownId").change(function(){
		var startTime = $("#UserStartTimePullDownId").val();
		$("#UserEndTimePullDownId option").remove();
		var endSelectEle = $("#UserEndTimePullDownId");
		endSelectEle = makeTimeOption(endSelectEle,startTime)

	});

	/**
	 * 時間のプルダウン
	 *
	 * @param parentEle 親のプルダウンの要素
	 * @param startTime 開始時刻
	 * @return 親要素
	 */
	function makeHourOption(parentEle,startTime) {
		for (var i = 0; i < 24; i++) {
			var optionEle = $("<option>");
			var timeEle = (i).hoursAfter(startTime).format('{24hr}:00');
			optionEle.val(timeEle).text(timeEle);
			parentEle.append(optionEle);
		}
		return parentEle;
	}

	function makeDateOption(parentEle,startTime){
		for (var i = 0; i < 7; i++) {
			var optionEle = $("<option>");
			var timeEle = (i).daysAfter(startTime).format('{yyyy}/{MM}/{dd}');
			optionEle.val(timeEle).text(timeEle);
			parentEle.append(optionEle);
		}
		return parentEle;
	}

});