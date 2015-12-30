$(function() {

	$("#all_check").click(function(){
			//ここがチェックされたら
		$(".account_status_check_box").prop('checked',true);
			return false;
	});

	$("#all_check_out").click(function(){
		//ここがチェックされたら
		$(".account_status_check_box").prop('checked',false);
		return false;
	});

});

var PassSec=0;
var started=false;
var startDate;
var WorkManagement=function() {

	this.PassageID;
	// 秒数カウント用変数
	// すでに開始しているかいなか


	// 一番最初に行う処理
	// すでに勤務開始であればスタートdateがあるはずなのでその時点から勤務開始
	// 勤務をこれから始める場合は、何もしない。
	this.init = function() {

		var startDateEle = window.document.getElementById("start_time");
		// 一度ログインしてあとに時間を取得する場合はこちらの処理を行う
		if (startDateEle !== null && startDateEle !== undefined
				&& startDateEle !== "") {
			started = true;
			startDate = startDateEle.innerHTML;
			console.log(startDate);
			this.startShowing();

		}
	}

	// 繰り返し処理の中身
	this.showPassage = function() {
		// カウントアップ
		if (this.started === true) {
			PassSec = getLoginedTime()
		} else {
			PassSec++;
		}

		var msg = this.convertSecondTohms(PassSec); // 表示文作成
		document.getElementById("PassageArea").innerHTML = msg; // 表示更新
	}

	// 繰り返し処理の開始
	this.startShowing = function() {
		PassSec=0;
		this.PassageID = setInterval(function() {
			// カウントアップ
			if (started === true) {
				PassSec = getLoginedTime()
			} else {
				PassSec++;
			}

			var msg = convertSecondTohms(PassSec); // 表示文作成
			document.getElementById("PassageArea").innerHTML = msg; // 表示更新
			console.log(PassSec);

		}, 1000); // タイマーをセット(1000ms間隔)
	}

	this.testAlert = function() {
		alert("test");
	}

	// 繰り返し処理の中止
	this.stopShowing = function() {
		clearInterval(this.PassageID); // タイマーのクリア
	}

};

/**
 * グローバルにしたいので外に記述
 *
 * 秒を時分秒に変換
 *
 * @param seconds
 *            秒
 */
function convertSecondTohms(seconds) {

	var tmpHours = seconds / 3600;
	var hours = (tmpHours).floor();
	var tmpMinutes = (seconds - hours * 3600) / 60;
	var minutes = (tmpMinutes).floor();
	var lastSeconds = seconds - (hours * 60 * 60 + minutes * 60);

	var hms;
	if (seconds >= 3600) {
		hms = hours + "時間" + minutes + "分" + lastSeconds + "秒";
	} else if (seconds >= 60) {
		hms = minutes + "分" + lastSeconds + "秒";
	} else {
		hms = lastSeconds + "秒";
	}
	return hms;
}

// ログインしてからの時間を記述する
function getLoginedTime() {
	var startDateFormatted = Date.create(startDate).format(
			"{yyyy}/{MM}/{dd} {HH}:{mm}:{ss}");
	var startTime = Date.parse(startDateFormatted);

	var nowDate = Date.create(new Date(), "ja")
	var nowTime = Date.parse(nowDate);
	this.PassSec = (nowTime - startTime) / 1000;
	return PassSec;
}