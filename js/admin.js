console.log("Welcome to Admin Js");
var soldier_config = {
	role:['--Role--','Admin','User','Pending']
};

/* local development ip */
/*
var localhost_ip = 'https://192.168.43.32:8082/api/v1'; // kms wifi
var localhost_ip_socket = 'https://192.168.43.32:8082'; // kms wifi
*/


/* this is server ip , we don't need to specify the server port, just domain name */
/* */
//var localhost_ip = "https://hr-management-system-server.herokuapp.com/api/v1"; // production server
//var localhost_ip_socket = 'https://hr-management-system-server.herokuapp.com'; // production server

//*/

/* localhost */
/* */
var localhost_ip = "https://localhost:8082/api/v1"; // production server
var localhost_ip_socket = 'https://localhost:8082'; // production server

//*/

var notification_alert_count_int = 0;


var User = Backbone.Model.extend({
    defaults: {
        serial_no:null,
        db_id: null,
        mc: null,
        role: 0,
        password:null,
        created_date:null,
        modified_date:null
    },
    update_serial_no:function(value){
        console.log("user serial no is updated to "+value);
        this.set({serial_no:value});
    },
    update_mc:function(value){
        console.log("user mc is updated to "+value);
        this.set({mc:value});
    },
    update_role:function(value){
        console.log("user role is updated to "+value);
        this.set({role:value});
    },
    update_password:function(value){
        console.log("user password is updated to "+value);
        this.set({password:value});
    },
    update_created_date:function(value){
        console.log("user created_date is updated to "+value);
        this.set({created_date:value});
    },
    update_modified_date:function(value){
        console.log("user modified_date is updated to "+value);
        this.set({modified_date:value});
    }
});


var Path = Backbone.Model.extend({
    defaults: {
        serial_no:null,
        db_id: null,
        mc: null,
        date: null,
        time:null,
        distance:null,
        from_lat:null,
        from_lng:null,
        to_lat:null,
        to_lng:null,
        data:null,
        created_date:null,
        modified_date:null
    },
    update_serial_no:function(value){
        console.log("user serial no is updated to "+value);
        this.set({serial_no:value});
    },
});



var UserList = Backbone.Collection.extend({
    url: '#',
    model: User
});


var PathList = Backbone.Collection.extend({
    url: '#',
    model: Path
});

var Users = new UserList();
var Paths = new PathList();


var UserView = Backbone.View.extend({
    tagName: 'tr',

    events: {
        'click .watch_me':'watch_me',
        'click .update_button':'update_button',
        'click .delete_button':'delete_button'
    },

    template: _.template( $('#user_row').html() ),
    
    initialize: function() {
        this.listenTo(this.model, 'change', this.render),
        this.listenTo(this.model, 'destroy', this.remove)
    },

    update_button:function(){
        console.log("user update button is clicked for mc "+this.model.get('mc'));
        var new_model = this.model.clone();
        var view = new UserUpdateView({ model: new_model });
        $('#new_user_modal_body_div').html( view.render().el);
    },

    delete_button:function(){
        console.log("user delete button is clicked for db_id "+this.model.get('db_id'));
        window.delete_user(this.model.get('db_id'));
    },

    watch_me:function(){
        alert("watch me is clicked");
        console.log('watch me is clicked');
    },

    render: function() {
        console.log(this.model.get('mc')+ " 's data is changing...");
        var modelData = this.model.toJSON();
        modelData.config = soldier_config;

        this.$el.html( this.template(modelData) );
        return this;
    },
    delSelf: function() {
        // actually we have to inform to delete this model in database
        // and we need to wait that the server is completely deleted this model data
        // if ok, we destroy this model
        // if not, just inform to user what went wrong
        this.model.destroy();
    }
});



var PathView = Backbone.View.extend({
    tagName: 'tr',

    events: {
        'click .show_on_map_button':'show_on_map_button',
        'click .delete_button':'delete_button'
    },

    template: _.template( $('#path_row').html() ),
    
    initialize: function() {
        this.listenTo(this.model, 'change', this.render),
        this.listenTo(this.model, 'destroy', this.remove)
    },
    delete_button:function(){
        console.log("user delete button is clicked for db_id "+this.model.get('db_id'));
        //window.delete_user(this.model.get('db_id'));
    },
    show_on_map_button:function(){
        console.log("show_on_map_button is clicked for db_id "+this.model.get('db_id'));
        //window.delete_user(this.model.get('db_id'));
        // we just need to pass this path object as json
        draw_on_map(this.model.toJSON());

        $('html, body').animate({
                    scrollTop: $("#map").offset().top
                }, 2000);


        /* filter object array */
        /*
        var result = jsObjects.filter(obj => {
            return obj.b === 6
        })
        */
    },

    render: function() {
        console.log(this.model.get('mc')+ " 's data is changing...");
        var modelData = this.model.toJSON();
        modelData.config = soldier_config;

        this.$el.html( this.template(modelData) );
        return this;
    },
    delSelf: function() {
        // actually we have to inform to delete this model in database
        // and we need to wait that the server is completely deleted this model data
        // if ok, we destroy this model
        // if not, just inform to user what went wrong
        this.model.destroy();
    }
});



/* this is soldier update/ new view */
var UserUpdateView = Backbone.View.extend({
    tagName: '<tr>',
    events: {
        'change .new_role_select':'new_role_select',
        'change .new_password_input':'new_password_input',
        'click .insert_user_button':'insert_user_button',
        'click .update_user_button':'update_user_button'
    },

    template: _.template( $('#new_user_modal_content').html() ),
    
    initialize: function() {
        this.listenTo(this.model, 'change', this.render);
        this.render();
    },

    new_mc_input: function(){
        // yeah , we get new mc type
        var new_mc = this.$('.new_mc_input').val();
        console.log("new_mc for user is changed to "+new_mc);
        this.model.update_mc(new_mc);   
    },

    new_role_select: function(){
        var new_role = this.$('.new_role_select').val();
        console.log("new_role_select for user is changed to "+new_role);
        this.model.update_role(new_role);   
    },

    new_password_input: function(){
        var new_password = this.$('.new_password_input').val();
        console.log("new_password_input for user is changed to "+new_password);
        this.model.update_password(new_password);   
    },
    render: function() {
        var modelData = this.model.toJSON();
        modelData.soldier_config = soldier_config;
        //console.log("render is calling from soldier update view "+JSON.stringify(modelData));
        //console.log("ths.template(modelData) for soldier update view is "+this.template(modelData));
        this.$el.html( this.template(modelData) );
        //console.log("what is this.el =>  "+JSON.stringify(this.el));
        return this;
        //return this.template(modelData);
    },

    delSelf: function() {
        // actually we have to inform to delete this model in database
        // and we need to wait that the server is completely deleted this model data
        // if ok, we destroy this model
        // if not, just inform to user what went wrong
        this.model.destroy();
    },

    insert_user_button:function(){
        console.log("user update modal => insert button is clicked");
        // request insert end point with model data
        window.insert_user();
    },
    update_user_button:function(){
        console.log("user update modal => update button is clicked");
        window.update_user();
    }
});



//$(function() {

$("#new_user_button").on('click',function(){
    console.log("new user button is clicked");
    var blank_user = new User();
    var view = new UserUpdateView({ model: blank_user });
    console.log("view.render().el html  for new user modal is "+view.render().el);
    $('#new_user_modal_body_div').html( view.render().el);
});


window.insert_user = insert_user;
window.update_user = update_user;
window.delete_user = delete_user;


window.save_localStorage = save_localStorage;
window.get_localStorage = get_localStorage;



function insert_user(){

    // validate form data 
    // create soldier object
    // send to server api using new_soldier endpoint on https post method
    // show indicator to user for connecting server 
    // show server response data in proper form
    // hide the modal
    $("#new_user").modal("hide");
    update_status('validating user input data...');
    console.log('validating user input data..');

    if($('#new_mc_input').val() == ""){
        // inform to user what's wrong with their input
        alert("enter valid mc");
        // we need to focus error input element
        $("#new_mc_input").val();
        return;
    } 
    if($('#new_password_input').val() == "" ){
        // inform to user what's wrong with their input
        alert("enter valid password");
        // we need to focus error input element
        $("#new_password_input").val();
        return;
    }
    var newUser = {
            mc: $('#new_mc_input').val(),
            password: $('#new_password_input').val(),
            role: $('#new_role_select').val()
     };
    console.log("new User is "+JSON.stringify(newUser));
    show_loading_modal('uploading data to server..');
    var token = get_localStorage("token");
	var settings = {
		"async": true,
  		"crossDomain": true,
  		"url": localhost_ip+"/user",
  		"method": "POST",
  		"headers": {
    		"Content-Type": "application/x-www-form-urlencoded",
    		"x-access-token": token
  		},
  		"data": newUser
	};
	console.log('user post request is starting...');
	$.ajax(settings).done(function (response) {
  		console.log("user post response is "+JSON.stringify(response));
		setTimeout(hide_loading_modal, 1000);
  		if(response.status == "success"){
    		// just show noti
    		show_notification(response.message,'success');
  		}
  		else{
    		// show noti with error
    		show_notification(response.error_msg,'error');
  		}
	})
	.error(function(err){
		setTimeout(hide_loading_modal, 1000);
    	console.log("user post (register ) error is "+JSON.stringify(err));
    	show_notification(JSON.stringify(err),'error');
	});
	console.log("user post request is end...");
} // end for insert user function



function update_user(){
    update_status('validating user input data...');
    console.log('validating user input data..');

    if($('#new_mc_input').val() == ""){
        // inform to user what's wrong with their input
        alert("enter valid mc");
        // we need to focus error input element
        $("#new_mc_input").val();
        return;
    } 
    if($('#new_password_input').val() == "" ){
        // inform to user what's wrong with their input
        alert("enter valid password");
        // we need to focus error input element
        $("#new_password_input").val();
        return;
    }


    $("#new_soldier").modal("hide");

    var db_id = $('#user_db_id_input').val();

    var newUser = {
            db_id: $('#user_db_id_input').val(),
            mc: $('#new_mc_input').val(),
            password: $('#new_password_input').val(),
            role: $('#new_role_select').val()
     };


    var token = get_localStorage("token");
    // new api request for update soldier id 
        show_loading_modal('update user data from server..');
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": localhost_ip+"/user/"+db_id,
            "method": "PUT",
            "headers": {
                "x-access-token":token 
            },
            "data":newUser
        } ;

        console.log('user update request is starting...');
        $.ajax(settings)
        .done(function (response) {
            console.log("user update response is "+JSON.stringify(response));
            setTimeout(hide_loading_modal, 1000);
            if(response.status == "success"){
                // just show noti
                show_notification(response.message,'success');
            }
            else{
                // show noti with error
                show_notification(response.error_msg,'error');
            }
        })
        .error(function(err){
            setTimeout(hide_loading_modal, 1000);
            console.log("error is "+JSON.stringify(err));
            show_notification(JSON.stringify(err),'error');
        });
        console.log("user update request is end...");
} // end for update soldier function

// delete user request
function delete_user(db_id){

        show_loading_modal('deleting user data from server..');

    var token = get_localStorage("token");
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": localhost_ip+"/user/"+db_id,
            "method": "DELETE",
            "headers": {
                "x-access-token": token
            }
        } ;

        console.log('user delete request is starting...');
        $.ajax(settings)
        .done(function (response) {
            console.log("user delete response is "+JSON.stringify(response));
            setTimeout(hide_loading_modal, 1000);
            if(response.status == "success"){
                // just show noti
                show_notification(response.message,'success');
            }
            else{
                setTimeout(hide_loading_modal, 1000);
                // show noti with error
                show_notification(response.error_msg,'error');
            }
        })
        .error(function(err){
            setTimeout(hide_loading_modal, 1000);
            console.log("error is "+JSON.stringify(err));
            show_notification(JSON.stringify(err),'error');
        });
        console.log("user delete   request is end...");
}


function update_status(message){
    $('#status_bar').append('<li>'+message+'</li>');
}

// localStorage Section

function save_localStorage(key,value){
    // convert object to string
    localStorage.setItem(key, JSON.stringify(value));
    console.log(JSON.stringify(value) +" is saved as "+key);
}


function remove_localStorage(key){
    localStorage.removeItem(key);
    console.log("key => "+key+" is removed from local storage");
}

function get_localStorage(key){
    if(localStorage.getItem(key)){
        // key is existed
        // convert string to object
        console.log("key "+key+"is existed");
        console.log(key+" is retrieved as "+localStorage.getItem(key));
        return JSON.parse(localStorage.getItem(key));
    }
    else{
        // key is not existed
        console.log("key  "+key+" is not existed");
        return null;
    }
}


function render_users_data(current_filter){
    //$("#new_soldier").modal("hide");
    console.log('render users data is calling .. current_filter is '+current_filter);
    var new_data = get_localStorage("users");
    if(new_data == null ){
        console.log("there is no users data in localStorage");
        return;
    }
    else{
        console.log("there is some data");
        console.log('users list is empty');

        $('#user_list').empty();
        $("#user_list").append("<thead><tr><th>No.</th><th>MC</th><th>Role</th><th>Created Datd</th><th>Created Datd</th><th>#Update</th><th>#Delete</th></tr></thead><tbody>");
        //var official_data = JSON.parse(new_data);
        Users = new UserList(new_data);
        var Users_copy = new UserList();
        for(var i=0;i<new_data.length; i++){
            var serial_no = i+1;
            console.log(serial_no+" "+new_data[i].mc);
            new_data[i].serial_no = serial_no;
            var user = new User(new_data[i]);
            Users_copy.add(user);
        }
        var filter_users;

        if(current_filter == 0 ){
            console.log('there is default render new data');
            filter_users = Users_copy;
        }
        else{
            console.log("there soldier role current filter is "+current_filter);
            filter_users = Users_copy.filter(function(model) {
                return model.get("role") == current_filter;
            });
            //console.log("filter_soldiers are "+JSON.stringify(filter_soldiers));
        }

        console.log("filter_userss are "+JSON.stringify(filter_users));
        var render_user = JSON.stringify(filter_users);
        render_user = JSON.parse(render_user);

        for(var j=0;j<filter_users.length;j++){
            console.log("adding to user list "+j);
            var serial_no = j+1;
            //console.log('filter_soldiers j is '+JSON.stringify(filter_soldiers[j]));
            //filter_soldiers[j].serial_no = serial_no;
            var user = new User(render_user[j]);//new Soldier(filter_soldiers[j]);//Soldiers.at(j);
            user.update_serial_no(serial_no);
            var view = new UserView({ model: user });
            $('#user_list').append( view.render().el );

        }


    }
    // render all the data in backbone
}


function render_paths_data(current_filter){
    //$("#new_soldier").modal("hide");
    console.log('render paths data is calling .. current_filter is '+current_filter);
    var new_data = get_localStorage("paths");
    if(new_data == null ){
        console.log("there is no path data in localStorage");
        return;
    }
    else{
        console.log("there is some data");
        $('#path_list').empty();
        $("#path_list").append("<thead><tr><th>No.</th><th>MC</th><th>Date</th><th>Time</th><th>Distance</th><th>From Latitude</th><th>From Longitude</th><th>To Latitude</th><th>To Longitude</th><th>Created Date</th><th>Created Date</th><th>#Update</th><th>#Delete</th></tr></thead><tbody>");
        //var official_data = JSON.parse(new_data);
        Paths = new PathList(new_data);
        var Paths_copy = new PathList();
        for(var i=0;i<new_data.length; i++){
            var serial_no = i+1;
            console.log(serial_no+" "+new_data[i].user_mc);
            new_data[i].serial_no = serial_no;
            var path = new Path(new_data[i]);
            Paths_copy.add(path);
        }
        var filter_paths= Paths_copy;

        /* no more filter about path */
        
        if(current_filter == 0 ){
            console.log('there is default render new data');
            filter_paths = Paths_copy;
        }
        else{
            console.log("there path current filter is "+current_filter);
            filter_paths = Paths_copy.filter(function(model) {
                return model.get("role") == current_filter;
            });
            //console.log("filter_soldiers are "+JSON.stringify(filter_soldiers));
        }
        

        console.log("filter_paths are "+JSON.stringify(filter_paths));
        //var filter_paths = JSON.stringify(filter_paths);

        //render_path = filter_paths;

        for(var j=0;j<filter_paths.length;j++){
            console.log("adding to path list "+j);
            var serial_no = j+1;
            var path = filter_paths.at(j);
            console.log(" final path "+j+" is "+JSON.stringify(path));
            path.update_serial_no(serial_no);
            var view = new PathView({ model: path });
            $('#path_list').append( view.render().el );

        }


    }
    // render all the data in backbone
}


function add_notification_alert(msg){
    $("#notification_alert_list").prepend("<li>"+msg+"</li>");
    $('#notification_alert_count_span').text(++notification_alert_count_int);
    show_notification(msg,'info');

    var audio = new Audio('sounds/notification_alert.ogg');
    audio.play();
}

function play_noti_sound(){
    var audio = new Audio('sounds/notification_alert.ogg');
    audio.play();
}

var universal_audio;

function play_video_incoming_sound(){
    universal_audio = new Audio('sounds/pickup.ogg');
    universal_audio.addEventListener('ended',function(){
        this.currentTime = 0;
        this.play();
    },false);
    universal_audio.play();
}

function stop_sound(){
    if(universal_audio == null ) return;
    universal_audio.pause();
    universal_audio = null;
}


$(".nav_link").click(function(){
        // hide all three div 
        $("#users_div").addClass('hidden');
        $("#notification_alert_list").addClass('hidden');
        $("#realtime_tracking_div").addClass('hidden');


        // active highlight on clicked nav
    $(this).parent().siblings().removeClass('active');
    $(this).parent().addClass('active');
    // we need to render appropriate data to soldier according to navigation link click
    // and we need to hide some columns and show some columns 
    console.log("navigation bar is clicked to "+$(this).attr('href'));
    var nav = $(this).attr('href');
    nav = nav.slice(1,nav.length);
    console.log("the real answer is "+nav);

    if(nav == 'users'){
        $("#users_div").removeClass('hidden');
    }
    else if(nav == 'realtime_tracking'){
        $("#realtime_tracking_div").removeClass('hidden');

    }
    else if(nav == 'notification_alert_list'){
        $("#notification_alert_list").removeClass('hidden');
    }
});

// we need a function to notify user 
// write message 
// show noti
function show_notification(msg,type){
    if(type == 'success'){
        $("#notification_msg_span").parent().removeClass('alert-danger').removeClass('alert-info').addClass('alert-success');
    }
    else if(type == 'error'){
        $("#notification_msg_span").parent().removeClass('alert-success').removeClass('alert-info').addClass('alert-danger');
    }
    else if(type == 'info'){
        $("#notification_msg_span").parent().removeClass('alert-danger').removeClass('alert-success').addClass('alert-info');        
    }

    $("#notification_msg_span").text(msg);
    $("#notification_div" ).removeClass('hidden');
    $('html, body').animate({
        scrollTop: $("#notification_div").offset().top
    }, 2000);
    console.log("show notification");   
}

function user_login(user){
    var socket_id = get_localStorage('socket_id');
    user.socket_id = socket_id;
    //var requested_data = {type:"user_login",user_data:user,socket_id:socket_id};
    show_loading_modal("Loging In....");
    var token = get_localStorage("token");
var settings = {
  "async": true,
  "crossDomain": true,
  "url": localhost_ip+"/user/login",
  "method": "POST",
  "headers": {
    "Content-Type": "application/x-www-form-urlencoded",
    "x-access-token": token
  },
  "data": user
}

console.log('user post request is starting...');
$.ajax(settings).done(function (response) {
  console.log("user post response is "+JSON.stringify(response));
setTimeout(hide_loading_modal, 1000);
  if(response.status == "success"){
    // just show noti
    show_notification(response.message,'success');
    // user is authorized
    // so we need to save token
    // hide login page and 
            $("#login_form_div").addClass('hidden');
            // show main div
            $("#main_div").removeClass('hidden');

            // save user data and token to localStroage
            save_localStorage("user_data",user);
            save_localStorage("token",response.token);
    // make get all request for all data update
    get_user(0);
    get_path(0);

  }
  else{
    // show noti with error
    show_notification(response.error_msg,'error');
  }
})
.error(function(err){
setTimeout(hide_loading_modal, 1000);
    console.log("error is "+JSON.stringify(err));
    show_notification(JSON.stringify(err),'error');
});
console.log("user login request is end...");

};

    // login button function

    $("#login_button").on('click',function(){
        console.log("login button is clicked");
        // validate mc and password
        var login_mc = $('#login_mc_input').val();
        var login_password = $('#login_password_input').val();
        console.log("login mc : "+login_mc+" and login_password : "+login_password);
        // old data api request
        //var user = {login_mc:login_mc,login_password:login_password};

        // new hr api v1
        var user = {mc:login_mc,password:login_password};
        user_login(user);
    });

    // logout button function
    // just delete all localStorage
    // there is nothing with server request
    $("#logout_button").on('click',function(){
        remove_localStorage('user_data');
        remove_localStorage('users');
        remove_localStorage('data');
        remove_localStorage('token');

        $("#login_form_div").removeClass('hidden');
        $("#main_div").addClass('hidden');
        //window.location.assign('https://localhost:8081');
        window.location.reload();
        //Android.logout();
    });

    function get_user(db_id){

        show_loading_modal('get users data from server..');

    var token = get_localStorage("token");
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": localhost_ip+"/user/"+db_id,
            "method": "GET",
            "headers": {
                "x-access-token": token
            }
        } ;

        console.log('user get (list) request is starting...');
        $.ajax(settings)
        .done(function (response) {
            console.log("user gets response is "+JSON.stringify(response));
            setTimeout(hide_loading_modal, 1000);
            if(response.status == "success"){
                // just show noti
                show_notification(response.message,'success');
                // we get data <= array of soldier object
            // requested data is ok 
            // we need to re-render our data view with latest server returned data
            // initialize localStorage and 
            // set new Data
            // render backbone view for retrieveing
            // and reload the whole app
            var new_data = response.data;
            save_localStorage("users",new_data);
            render_users_data(0);
            }
            else{
                setTimeout(hide_loading_modal, 1000);
                // show noti with error
                show_notification(response.error_msg,'error');
            }
        })
        .error(function(err){
            setTimeout(hide_loading_modal, 1000);
            console.log("error is "+JSON.stringify(err));
            show_notification(JSON.stringify(err),'error');
        });
        console.log("soldier get list  request is end...");
    } // get all functin end 

    function get_path(db_id){

        show_loading_modal('get path data from server..');

    var token = get_localStorage("token");
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": localhost_ip+"/path/"+db_id,
            "method": "GET",
            "headers": {
                "x-access-token": token
            }
        } ;

        console.log('path get (list) request is starting...');
        $.ajax(settings)
        .done(function (response) {
            console.log("path gets response is "+JSON.stringify(response));
            setTimeout(hide_loading_modal, 1000);
            if(response.status == "success"){
                // just show noti
                show_notification(response.message,'success');
                // we get data <= array of soldier object
            // requested data is ok 
            // we need to re-render our data view with latest server returned data
            // initialize localStorage and 
            // set new Data
            // render backbone view for retrieveing
            // and reload the whole app
            var new_data = response.data;
            console.log("returned path data is "+JSON.stringify(response));
            save_localStorage("paths",new_data);
            render_paths_data(0);
            }
            else{
                setTimeout(hide_loading_modal, 1000);
                // show noti with error
                show_notification(response.error_msg,'error');
            }
        })
        .error(function(err){
            setTimeout(hide_loading_modal, 1000);
            console.log("error is "+JSON.stringify(err));
            show_notification(JSON.stringify(err),'error');
        });
        console.log("path get list  request is end...");
    } // get all functin end 


    $("#update_users_button").on('click',function(){
        get_user(0);
        //get_path(0);
    });

    $("#update_paths_button").on('click',function(){
        get_path(0);
        //get_path(0);
    });


// View initialization
// we need to determine to show Login or Main Div 
    if(get_localStorage('user_data') == null || get_localStorage('token') == null){
        console.log("there is no login information and token ");
        $('#login_form_div').removeClass('hidden');
            // hide main div
            $("#main_div").addClass('hidden');
    }
    else{
        console.log("login_status is ok");
        $('#main_div').removeClass('hidden');
        $('#login_form_div').addClass('hidden');

        // just initialize the localStorage
        render_users_data(0);
        render_paths_data(0);
    }



        /* 
        $('#main_div').removeClass('hidden');
        // just initialize the localStorage
        render_new_data(0);
        */
        $("#notification_div" ).removeClass('hidden');
        $('html, body').animate({
                    scrollTop: $("#notification_div").offset().top
                }, 1000);

        console.log("i need attention");

        $("#notification_div" ).on('click',function(){
            $("#notification_div" ).addClass('hidden');
        });





    show_loading_modal('HR Management System is starting...');
    setTimeout(hide_loading_modal, 1000);


    $("#notification_div").addClass('hidden');
    //alert("hello");
    //show_loading_modal('HR Management System is starting...');

function show_loading_modal(msg){
    //sleep(1000);
    $('body').loadingModal({
      position: 'auto',
      text: msg,
      color: '#fff',
      opacity: '0.7',
      backgroundColor: 'rgb(0,0,0)',
      animation: 'wave'
    });
}

function hide_loading_modal(){
    $('body').loadingModal('hide');
    // destroy the plugin
    $('body').loadingModal('destroy');
}



    // socket section

  var socket = io(localhost_ip_socket);
  // returns a random integer from 0 to 9
  var r_no = Math.floor(Math.random() * 10);  
  var r_name = "user_"+r_no;

  var connected = false;

    if(get_localStorage('user_data') == null){
        console.log("there is no login information");
        //$('#login_form_div').removeClass('hidden');
        // just initialize the localStorage
        var socket_message = {
            user_mc:null
        };
        socket.emit('add user', socket_message);
    }
    else{
        console.log("login_status is ok");
        // just initialize the localStorage
        var user_data = get_localStorage('user_data');
        var user_mc = user_data.mc;
        var socket_message = {
            user_mc:user_mc
        };
        socket.emit('add user', socket_message);
    }


  // Whenever the server emits 'login', log the login message
  socket.on('login', (data) => {
    connected = true;
    console.log("there is "+data.numUsers+" user online");
  });


  // Whenever the server emits 'user joined', log it in the chat body
  socket.on('user joined', (data) => {
    console.log(data.username + ' joined');
    console.log(data.numUsers+" online");
    add_notification_alert(data.username+" is joined");
    add_notification_alert(data.numUsers+" are online");
  });


  // Whenever the server emits 'user left', log it in the chat body
  socket.on('user left', (data) => {
    console.log(data.username + ' left');
    console.log("there is "+data.numUsers+" online");
    add_notification_alert(data.username+" is left");
    add_notification_alert(data.numUsers+" are online");
  });

  socket.on('assign_id',(data)=>{
    console.log('socket id is '+data.id);
    window.save_localStorage("socket_id",data.id);
  });

  socket.on('location_update',(data)=>{
    console.log('location_update is '+JSON.stringify(data));
    // we need to parse mc, lat, long and time
    // we also nedd to show those marker of data on google map
	/* that structure */
	/* 
        var socket_message = {
            user_mc:user_mc,
            latitude:latitude,
            longitude:longitude,
            time:timeString
        };
    */

    // remove all marker
    //window.remove_marker();

    // create point
    var current_location = {lat: data.latitude, lng: data.longitude};

          if(window.google_map_markers.length > 0){
            console.log("window.google_map_marker.length   is greter than zero");
            // remove latest marker 
            window.google_map_markers[0].setMap(null);
          }
    console.log("current location is "+JSON.stringify(current_location));
    // add to marker with name 
    var latest_marker = window.add_marker(current_location,data.user_mc,data.time);


          window.google_map_markers = new Array();
          window.google_map_markers[0] = latest_marker;
          console.log("window.google_map_marker  is "+window.google_map_markers );


     window.moveToLocation(data.latitude, data.longitude);

  });

  socket.on('mode_on_mcs',(data)=>{
    console.log("mode_on_mcs data is "+JSON.stringify(data));
    // this should be array of mc 
    // data itself is array of mc 
    var active_now_mcs = data; 
    var active_list_item = '';

    //    <button type="button" class="btn btn-primary">Apple</button>
    //    "<button type='button' class='btn btn-primary'>"+active_now_mcs[i]+"</button>"
    for(var i = 0; i<active_now_mcs.length; i++){
        //active_list_item = active_list_item + "<li>"+active_now_mcs[i]+"</li>";
        active_list_item = active_list_item + "<button type='button' class='btn  active_now_mcs'>"+active_now_mcs[i]+"</button>";
    }
    // add to active list 
    //$("#active_now_ul").html(active_list_item);
    $("#realtime_tracking_on_list_div").html(active_list_item);
    realtime_tracking_on_list_div_listner();
    //$("#active_now_ul").append("<li>"+user_mc+"</li>");
  });

// set null to current tracking mc for default 
var current_tracking_mc = null;


$("#realtime_tracking_on_button").on('click',function(){
    console.log("on is clicked");
   var user_data = get_localStorage("user_data");
    if(user_data == null ){
        console.log("there is no users data in localStorage");
        return;
    }
    console.log("user data is ok ");
    var user_mc = user_data.mc;
    var socket_message = {
        user_mc:user_mc
    };
    socket.emit('realtime_tracking_mode_on', socket_message);
    console.log("socke emit ok");

    $(this).addClass('hidden');
    $("#realtime_tracking_off_button").removeClass('hidden');
});



$("#realtime_tracking_off_button").on('click',function(){
    console.log("off is clicked");

   var user_data = get_localStorage("user_data");
    if(user_data == null ){
        console.log("there is no users data in localStorage");
        return;
    }
    console.log("user data is ok ");
    var user_mc = user_data.mc;
    var socket_message = {
        user_mc:user_mc
    };
    socket.emit('realtime_tracking_mode_off', socket_message);
    console.log("socke emit ok");

    $(this).addClass('hidden');
    $("#realtime_tracking_on_button").removeClass('hidden');
});


$("#send_location_update_button").on('click',function(){
    // get user data 
    // get lat , lng 
    // get time 
    // send to server via socket 


        var latitude = $("#location_lat_input").val();
        var longitude = $("#location_lng_input").val();
        if(latitude == "" || longitude == ""){
            alert("enter both lat and longitude data ");
            return;
        }

        latitude = parseFloat(latitude);
        longitude = parseFloat(longitude);


    var user_data = get_localStorage('user_data');
    if(user_data == null ){
        console.log("user data is not availbale ");
        return;
    }

    var user_mc = user_data.mc;
        /* well formated Date time string */
        /*
        var date = new Date();
        var timeString = date.toISOString().substr(11, 8);
        */
        Date.prototype.toIsoString = function() {
            var tzo = -this.getTimezoneOffset(),
                dif = tzo >= 0 ? '+' : '-',
                pad = function(num) {
                    var norm = Math.floor(Math.abs(num));
                    return (norm < 10 ? '0' : '') + norm;
                };
            return this.getFullYear() +
                '-' + pad(this.getMonth() + 1) +
                '-' + pad(this.getDate()) +
                ',' + pad(this.getHours()) +
                ':' + pad(this.getMinutes()) +
                ':' + pad(this.getSeconds()) +
                dif + pad(tzo / 60) +
                ':' + pad(tzo % 60);
        }  // end date prototype declaration

        var dt = new Date();
        console.log(dt.toIsoString());
        var timeString = dt.toIsoString();

        var socket_message = {
            user_mc:user_mc,
            latitude:latitude,
            longitude:longitude,
            time:timeString
        };

        // send user location data
        socket.emit('location_update', socket_message);
        
});


function realtime_tracking_on_list_div_listner(){
    $(".active_now_mcs").on('click',function(){
        console.log($(this).text());
        // update the current_tracking_mc 
         current_tracking_mc = $(this).text();
        console.log("current tracking mc  => "+current_tracking_mc );
        // inform user by displaying message 
        var message = "Current tracking on : "+current_tracking_mc ;
        $('#current_tracking_status').text(message);
    });  
}



window.google_map_markers = new Array();


function toggleBounce(marker) {
        if (marker.getAnimation() !== null) {
          marker.setAnimation(null);
        } else {
          marker.setAnimation(google.maps.Animation.BOUNCE);
        }
}

function animateMarker(marker) {
 marker.setAnimation(google.maps.Animation.BOUNCE);
}

window.toggleBounce = toggleBounce;
window.animateMarker = animateMarker;

// draw path on map using path data
function draw_on_map(path){
        /*
        var flightPlanCoordinates = [
          {lat: 37.772, lng: -122.214},
          {lat: 21.291, lng: -157.821},
          {lat: -18.142, lng: 178.431},
          {lat: -27.467, lng: 153.027}
        ];
        */
        console.log("draw_on_map function path is "+JSON.stringify(path));


        path.data = JSON.parse(path.data);

        var flightPlanCoordinates = new Array();
        for(var i = 0; i< path.data.length; i++){
            flightPlanCoordinates[i] = {lat:path.data[i].latitude, lng: path.data[i].longitude } ;
        }
        var flightPath = new google.maps.Polyline({
          path: flightPlanCoordinates,
          geodesic: true,
          strokeColor: '#0066ff',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });
        flightPath.setMap(map);
        map.setCenter(new google.maps.LatLng(path.data[0].latitude, path.data[0].longitude));

        google.maps.event.addListener(flightPath, 'click', function (event) {
            flightPath.setMap(null);
        });  
        return flightPath;
}

window.draw_on_map = draw_on_map;
//}); // main self call function END

// function remove path from google map
function remove_line_on_map(path){
    // we get  google.maps.Polyline
    // and set to null 
    path.setMap(null);
}

// socket section


      function initMap() {
        var myLatLng = {lat: 22.037211, lng: 96.488178};

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 16,
          center: myLatLng
        });

        window.map = map;


        /*
        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'Hello World!'
        });
        */

        window.map_status = "ready";
        console.log("Google Map is Ready");
        

        /*
        var flightPlanCoordinates = [
          {lat: 37.772, lng: -122.214},
          {lat: 21.291, lng: -157.821},
          {lat: -18.142, lng: 178.431},
          {lat: -27.467, lng: 153.027}
        ];
        var flightPath = new google.maps.Polyline({
          path: flightPlanCoordinates,
          geodesic: true,
          strokeColor: '#0066ff',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });

        flightPath.setMap(map);
        console.log("flightPath is ready");
        
        */



        /* add upCafe in Google Map */
        /*
        var upCafe = {lat: 22.037211, lng: 96.488178};
        window.add_marker(upCafe,"Up Cafe");
        */

        /* remove all marker on google map */
        /*
        setMapOnAll(null);
        */

        /* change marker position */
        /*
        function changeMarkerPosition(marker, lat, lng) {
          var latlng = new google.maps.LatLng(lat, lng);
          marker.setPosition(latlng);
        }
        */


      }
      


