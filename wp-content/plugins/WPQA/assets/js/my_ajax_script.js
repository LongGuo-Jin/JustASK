jQuery(document).ready(function($){
    $('form.find_parent').on('submit', function(e){
        e.preventDefault();
        var that = $(this);
        var first_name = $('#parent_first_name',that).val();
        var last_name = $('#parent_last_name',that).val();
        var nric = $('#parent_nric',that).val();
        $('input[type="submit"]',that).hide();
        $('.load_span',that).show().css({"display":"block"});
        console.log("sdsdds");
        $.ajax({
            url: ajax_object.ajax_url,
            type:"POST",

            data: {
                action: 'parent_form_process',
                first_name:first_name,
                last_name:last_name,
                nric:nric,
                type:1,
            }, success: function(response){
                $('input[type="submit"]',that).show();
                $('.load_span',that).show().css({"display":"none"});

                var result = $.parseJSON(response);

                if (result.length == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: "I am so sorry , it seems we can't find what you enter , please try again?",
                        showConfirmButton: false,
                         timer: 2500
                    })
                } else {
                    $('#relation_to_user_id').val(result['user_id']);
                    $('#relation_my_id').val(result['my_id']);
                    $('.send_relation_wrap-pop1').show().css({"display":"block"});
                    $('.avatar').html(result['avatar']);
                    $('.user_name').html(result['user_name']);
                }
            }, error: function(data){
                $('input[type="submit"]',that).show();
                $('.load_span',that).show().css({"display":"none"});
            }
        });
        $('.find_parent')[0].reset();
    });

    $('form.find_sibling').on('submit', function(e){
        e.preventDefault();
        console.log("sibling");
        var that = $(this);
        var first_name = $('#sibling_first_name',that).val();
        var last_name = $('#sibling_last_name',that).val();
        var nric = $('#sibling_nric',that).val();
        $('input[type="submit"]',that).hide();
        $('.load_span',that).show().css({"display":"block"});

        $.ajax({
            url: ajax_object.ajax_url,
            type:"POST",

            data: {
                action: 'parent_form_process',
                first_name:first_name,
                last_name:last_name,
                nric:nric,
                type:2,
            }, success: function(response){
                $('input[type="submit"]',that).show();
                $('.load_span',that).show().css({"display":"none"});
                console.log(response);
                var result = $.parseJSON(response);
                console.log(result);
                if (result.length == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: "I am so sorry , it seems we can't find what you enter , please try again?",
                        showConfirmButton: false,
                        timer: 2500
                    })
                } else {
                    $('#relation_to_user_id').val(result['user_id']);
                    $('#relation_my_id').val(result['my_id']);
                    $('.send_relation_wrap-pop1').show().css({"display":"block"});
                    $('.avatar').html(result['avatar']);
                    $('.user_name').html(result['user_name']);
                }
            }, error: function(data){
                $('input[type="submit"]',that).show();
                $('.load_span',that).show().css({"display":"none"});
            }
        });
        $('.find_sibling')[0].reset();
    });

    $('form.find_child').on('submit', function(e){
        e.preventDefault();
        var that = $(this);
        var first_name = $('#child_first_name',that).val();
        var last_name = $('#child_last_name',that).val();
        var nric = $('#child_nric',that).val();
        $('input[type="submit"]',that).hide();
        $('.load_span',that).show().css({"display":"block"});

        $.ajax({
            url: ajax_object.ajax_url,
            type:"POST",

            data: {
                action: 'parent_form_process',
                first_name:first_name,
                last_name:last_name,
                nric:nric,
                type:1,
            }, success: function(response){
                $('input[type="submit"]',that).show();
                $('.load_span',that).show().css({"display":"none"});

                var result = $.parseJSON(response);

                if (result.length == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: "I am so sorry , it seems we can't find what you enter , please try again?",
                        showConfirmButton: false,
                        timer: 2500
                    })
                } else {
                    $('#relation_to_user_id').val(result['user_id']);
                    $('#relation_my_id').val(result['my_id']);
                    $('.send_relation_wrap-pop1').show().css({"display":"block"});
                    $('.avatar').html(result['avatar']);
                    $('.user_name').html(result['user_name']);
                }
            }, error: function(data){
                $('input[type="submit"]',that).show();
                $('.load_span',that).show().css({"display":"none"});
            }
        });
        $('.find_child')[0].reset();
    });

    $('form.send_relation_notification').on('submit', function(e) {
        e.preventDefault();
        var that = $(this);
        var to_user_id = $('#relation_to_user_id' , that).val();
        var my_id = $('#relation_my_id' , that).val();
        console.log(to_user_id  , my_id);
        $.ajax({
            url: ajax_object.ajax_url,
            type:"POST",
            data: {
                action: 'relation_notify_process',
                to_user_id:to_user_id,
                my_id:my_id,
            }, success: function(response) {
                $('.send_relation_wrap-pop1').show().css({"display":"none"});
            },
            error: function(data){
                $('.send_relation_wrap-pop1').show().css({"display":"none"});
            }
        });
    });

    $('form.send_accept_notification').on('submit', function(e) {
        e.preventDefault();
        var that = $(this);
        var to_user_id = $('#send_accept_to_user_id' , that).val();

        $.ajax({
            url: ajax_object.ajax_url,
            type:"POST",
            data: {
                action: 'send_accept_notification',
                to_user_id:to_user_id,
            }, success: function(response) {
                console.log(response);
                $('.send_relation_wrap-pop2').show().css({"display":"none"});
            },
            error: function(data){
                $('.send_relation_wrap-pop2').show().css({"display":"none"});
            }
        });
    });

    if (jQuery("#send_relation_cancel").length) {
        jQuery("#send_relation_cancel").click(function () {
            console.log("sddssd");
            $('.send_relation_wrap-pop1').show().css({"display":"none"});
        });
    }
    if (jQuery("#send_relation_no").length) {
        jQuery("#send_relation_no").click(function () {
            $('.send_relation_wrap-pop1').show().css({"display":"none"});
        });
    }


    if (jQuery("#send_accept_cancel").length) {
        jQuery("#send_accept_cancel").click(function () {
            console.log("sddssd");
            $('.send_relation_wrap-pop2').show().css({"display":"none"});
        });
    }

    if (jQuery("#send_accept_no").length) {
        jQuery("#send_accept_no").click(function () {
            $('.send_relation_wrap-pop2').show().css({"display":"none"});
        });
    }

});

function notification_relation_link_clicked(from_user_id , avatar_image , from_username, my_role , from_user_role) {
    console.log(from_user_id , avatar_image,from_username,my_role,from_user_role);
    jQuery("#send_accept_username").html(from_username);
    jQuery("#send_accept_to_user_id").val(from_user_id);

    if (my_role == "student" && from_user_role=="parent") {
        jQuery("#send_accept_description").html("Is this your Parent?");
    }
    if (my_role == "parent" && from_user_role=="student") {
        jQuery("#send_accept_description").html("Is this your child?");
    }
    if (my_role == "student" && from_user_role=="student") {
        jQuery("#send_accept_description").html("Is this your sibling?");
    }
    jQuery('#send_accept_from_user_avatar').attr("src",avatar_image);
    jQuery('.send_relation_wrap-pop2').show().css({"display":"block"});
}

