$("#dashboardTab").click(function() 
{
	ClearActiveTab();
	//d3.selectAll("svg > *").remove();
  	$("#dashboardTab").addClass('active');
});

$("#sleepdataTab").click(function() 
{
	ClearActiveTab();
	$("#sleepdataTab").addClass('active');
});

$("#brand").click(function() 
{
	ClearActiveTab();
	//d3.selectAll("svg > *").remove();
  	$("#dashboardTab").addClass('active');
});

$("#accountTab").click(function() 
{
	d3.selectAll("svg > *").remove();
	ClearActiveTab();
	$("#accountTab").addClass('active');
});

function ClearActiveTab() 
{
	$("#dashboardTab").removeClass('active');
	$("#sleepdataTab").removeClass('active');
}

$('#logoutBtn').click(function() {

	delete_cookie('loginId');
	$('#login-modal').modal('show');
	$('#userText').val('');
	$('#passText').val('');

	if(!$('wrongPasswordAlert').hasClass('hidden'))
		$('#wrongPasswordAlert').addClass('hidden');
})

var delete_cookie = function(name) {
    document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
};

$('#loginSubmitBtn').click(function()
{
	var url = "http://drewswinney.com:8080/api/loginauth"
	var data =
	{
	    "username": $('#userText').val(),
	    "password": $('#passText').val()
	};
	
	var success_func = function(data)
	{
		if(data != 'NOTFOUND')
		{
		    //do what you want with the returned data
		    document.cookie = "loginId=" + data;
		    $('#login-modal').modal('hide');
		    window.location.replace("/#/dashboard");
		}
		else
		{
			$('#wrongPasswordAlert').removeClass('hidden');
		}
	};
	
	$.post(url, data, success_func);
});

$('#userText').bind("enterKey",function(e){
   var url = "http://drewswinney.com:8080/api/loginauth"
	var data =
	{
	    "username": $('#userText').val(),
	    "password": $('#passText').val()
	};
	
	var success_func = function(data)
	{
		if(data != 'NOTFOUND')
		{
		    //do what you want with the returned data
		    document.cookie = "loginId=" + data;
		    $('#login-modal').modal('hide');
		    window.location.replace("/#/dashboard");
		}
		else
		{
			$('#wrongPasswordAlert').removeClass('hidden');
		}
	};
	
	$.post(url, data, success_func);
});

$('#userText').keyup(function(e){
    if(e.keyCode == 13)
    {
        $(this).trigger("enterKey");
    }
});

$('#passText').bind("enterKey",function(e){
   var url = "http://drewswinney.com:8080/api/loginauth"
	var data =
	{
	    "username": $('#userText').val(),
	    "password": $('#passText').val()
	};
	
	var success_func = function(data)
	{
		if(data != 'NOTFOUND')
		{
		    //do what you want with the returned data
		    document.cookie = "loginId=" + data;
		    $('#login-modal').modal('hide');
		    window.location.replace("/#/dashboard");
		}
		else
		{
			$('#wrongPasswordAlert').removeClass('hidden');
		}
	};
	
	$.post(url, data, success_func);
});

$('#passText').keyup(function(e){
    if(e.keyCode == 13)
    {
        $(this).trigger("enterKey");
    }
});