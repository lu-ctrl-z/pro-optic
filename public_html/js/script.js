// JavaScript Document

$(function(){
	$('a.clickToolTip').click(function(e){
		e.preventDefault();
		var targetNote = $(this).attr('href');
		var position = $(this).position();
		var newPositionTop = position.top +10;	
		var newPositionLeft = position.left + 35;
		$('p'+targetNote).css({'top': newPositionTop + 'px', 'left': newPositionLeft + 'px'});
		$('p'+targetNote).removeClass('invisible');
	});
	$('html').mousedown(function(){
		$('p.toolTip').addClass('invisible');
	});
});

$(function(){
	$("#accordion .heading").on("click", function() {
		$(this).next().slideToggle(150);
		$(this).toggleClass("active");
	});
});
