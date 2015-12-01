var url = "http://drewswinney.com:8080/api/login/" + getCookie('loginId');

$.get(url, function(data, status)
{
    //load the information into the view using jquery
    $('#firstname').text(data.loginFirstName);
    $('#lastname').text(data.loginLastName);
    $('#username').text(data.loginUsername);
    $('#password').text(data.loginPassword);
    $('#email').text(data.loginEmail);

    var t = data.created_at.split(/[- :]/);
	var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);

    $('#accountcreated').text(d);
});

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}