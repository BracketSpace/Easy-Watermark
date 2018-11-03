(function($){

	ewAjax = $.ewAjax = {
		init: function(action){
			this.progressbar = $('#ew-progress');
			this.console = $('#ew-console');
			this.status = $('#ew-status');
			this.statusProgress = this.status.find('.progress');
			this.statusTotal = this.status.find('.total');
			this.finished = $('#ew-finished');
			this.timeContainer = $('#ew-status .ew-timer');

			this.label = $('.progress-label');

			this.action = action;

			this.progressbar.progressbar({
				value: false,
				change: function() {
					ewAjax.label.text(Math.round(ewAjax.progressbar.progressbar('value')) + '%');
				},
				complete: function() {
					ewAjax.label.text(ewData.complete);
				}
			});

			this.start();
		},

		start: function(){

			$('#ew-info').fadeOut(300, function(){
				ewAjax.progressbar.fadeIn(100);

				ewAjax.statusTotal.html(ewData.total_items);
				ewAjax.statusProgress.html(0);
				ewAjax.status.fadeIn(100);
			});

			this.time = 0;
			this.deltaTime = 1000;
			this.timer = setInterval(this.showTimer, this.deltaTime);

			this.process();
		},

		showTimer: function(){
			ewAjax.time += ewAjax.deltaTime;

			var time = ewAjax.msToTime(ewAjax.time);

			ewAjax.timeContainer.html(time);
		},

		process: function(action){

			$.ajax(ewData.ajaxurl, {
				data: {
					_ewnonce: ewData.nonce,
					action: this.action
				},
				dataType: 'json',
				success: function(data){
					console.log(data);

					if(data){
						ewAjax.statusProgress.html(data.progress);
						ewAjax.progress = data.progress;
						var val = (data.progress / ewData.total_items) * 100;

						ewAjax.progressbar.progressbar('value', val);

						ewAjax.console.append(data.message);

						if(data.progress < ewData.total_items){
							ewAjax.process();
						}
						else {
							ewAjax.finish();
						}
					}
				},
				error: function(){
					alert('error!');
				}
			});
		},

		finish: function(){
			this.finished.find('.count').html(this.progress);

			clearInterval(this.timer);

//			this.status.slideUp(200);
			this.finished.slideDown(200);
		},

		msToTime: function(time){
			var ms = time % 1000;
			time = (time - ms) / 1000;
			var s = time % 60;
			time = (time - s) / 60;
			var m = time % 60;
			var h = (time - m) / 60;

			var sufix = '',
				result = '';

			if(h > 0){
				if(m < 10){
					m = '0' + m;
				}

				if(s < 10){
					s = '0' + s;
				}
				result = h + ':' + m + ':' + s;
				sufix = 'h';
			}
			else if(m > 0){
				if(s < 10){
					s = '0' + s;
				}

				result = m + ':' + s;
				sufix = 'm';
			}
			else {
				result = s;
				sufix = 's';
			}

			return result + ' ' + sufix;
		}
	}

})(jQuery)
