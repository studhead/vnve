$(document).ready(CheckInput);
$(document).ready(DatePicker);  //datepicker not found?????
//$(document).ready(TimePicker);
$(document).ready(DisableEnterKey);	// disable enter key for forms
$(document).ready(ShowImage);
function CheckInput()
{
	$(".checkemail").on("change",function()
	{ 
		var regex = new RegExp(/\S+@\S+\.\S+/);
		PranaWarning(this,regex);
	});
	$(".checkphone").on("change",function()
	{ 
		var regex = /(^\+[0-9]{2}|^\+[0-9]{2}\(0\)|^\(\+[0-9]{2}\)\(0\)|^00[0-9]{2}|^0)([0-9]{9}$|[0-9\-\s]{10}$)/i;
		PranaWarning(this,regex);
	});
	$(".checkbankrekening").on("change",function()
	{ 
		var regex = /(^[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}$)/i;
		PranaWarning(this,regex);
	});
}
function PranaWarning(element,regex)
{
	var cssborder = { 'borderColor': 'red' };
	var cssoldborder = { 'borderColor': '' };
	var error = $(element).siblings("span").text();
	if(regex.test($(element).val()) == false)
	{
		alert (error);
		$(element).css(cssborder);
		$(element).addClass("invalid");
		$(element).removeClass("valid");
	}
	else
	{
		$(element).addClass("valid");
		$(element).removeClass("invalid");
		$(element).css(cssoldborder);
	}
}

function DatePicker()
{
	$('.datepicker').datepicker(
	
	{
		dateFormat : 'yy-mm-dd',
		monthNames : ['januari', 'februari', 'maart', 'april', 'mei', 'juni','juli', 'augustus', 'september', 'oktober', 'november', 'december']
	}
	);
}

function TimePicker()
{
	$('#fromtime').timepicker(
	{
		timeFormat: 'HH:mm',
			interval: 60,
			defaultTime: '08:00',
			startTime: '08:00',
			minTime: '08:00',
			maxTime: '17:00',
			dynamic: false,
			dropdown: true,
			scrollbar: true
	}
	);
	$('#tilltime').timepicker(
		{
			timeFormat: 'HH:mm',
			interval: 60,
			defaultTime: '17:00',
			startTime: '08:00',
			minTime: '08:00',
			maxTime: '17:00',
			dynamic: false,
			dropdown: true,
			scrollbar: true
		}
	);
}
/*
function TimePicker()
{
	$('.timepicker').pDatepicker();
}
*/


function SetPopover() 
{
	$('[data-toggle="popover"]').popover();
}
/**
 * Disable the enter key for submitting a form
 */
function DisableEnterKey()
{
	$("form").keypress(function(e) 
	{
		//Enter key
		if (e.which == 13) {
	 	 return false;
		}
  	});
}

function DragCrop()
{
	$( "#crop_div" ).draggable({ containment: "parent" });
}
 
function crop()
{
	var posi = document.getElementById('crop_div');
	document.getElementById("top").value=posi.offsetTop;
	document.getElementById("left").value=posi.offsetLeft;
	document.getElementById("right").value=posi.offsetWidth;
	document.getElementById("bottom").value=posi.offsetHeight;
	return true;
}
//
// change the src of the image with id = showphoto to the file which is choosen by file element with class showfile
//
function ShowImage(event)
{
	$(".showimage").on("change",function(event)
	{
		$(this).siblings("img").attr('src',URL.createObjectURL(event.target.files[0]));
	});
}