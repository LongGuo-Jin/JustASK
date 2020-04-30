<?php

/* @author    2codeThemes
*  @package   WPQA/templates/profile
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

if (!function_exists('get_relation_data')) :
    function get_relation_data($user_id , $releation) {
        global $wpdb;
        $query = "SELECT Relation_ID FROM wpo6_relation WHERE MY_ID={$user_id} AND Relation={$releation} " ;
        $result = $wpdb->get_results($query);
        return $result;
    }
endif;


$author_name           = esc_attr(get_query_var("author_name"));
$wpqa_user_id          = esc_attr(get_query_var(apply_filters('wpqa_user_id','wpqa_user_id')));
$author_box            = wpqa_options("author_box");
$active_points         = wpqa_options('active_points');
$user_profile_pages    = wpqa_options("user_profile_pages");
$user_stats            = wpqa_options('user_stats');
$user_stats_2          = wpqa_options('user_stats_2');
$show_point_favorite   = get_user_meta($wpqa_user_id,"show_point_favorite",true);
$ask_question_to_users = wpqa_options("ask_question_to_users");
$pay_ask               = wpqa_options("pay_ask");
$my_role = get_the_author_meta('user_role',$wpqa_user_id);

if ($my_role == "student") {
    $mom_data = get_relation_data($wpqa_user_id,1);
    $dad_data = get_relation_data($wpqa_user_id,2);
    $sibling_data = get_relation_data($wpqa_user_id,4);
//    wp_die(json_encode($dad_data));
} else if ($my_role == "parent"){
    $child_data = get_relation_data($wpqa_user_id,3);
}

if ($ask_question_to_users == "on") {
	/* asked_questions */
	$asked_questions = wpqa_count_asked_question($wpqa_user_id,"=");
}


include wpqa_get_template("head-tabs.php","profile/");?>

<?php if (!wpqa_user_title()) {?>
	<div class="user-area-content">
		<?php if ($author_box == "on") {
			$cover_image = wpqa_options("cover_image");
			echo wpqa_author($wpqa_user_id,"advanced",wpqa_is_user_owner(),"","","user-area-head",($cover_image == "on"?"cover":""));
		}
		
		/* following */
		$following_me  = get_user_meta($wpqa_user_id,"following_me",true);
		$following_me  = (is_array($following_me) && !empty($following_me)?get_users(array('fields' => 'ID','include' => $following_me,'orderby' => 'registered')):array());
		$following_you = get_user_meta($wpqa_user_id,"following_you",true);
		$following_you = (is_array($following_you) && !empty($following_you)?get_users(array('fields' => 'ID','include' => $following_you,'orderby' => 'registered')):array());
        if (count($user_stats_2) == 1) {
            $column_follow = "col12";
        }else {
            $column_follow = "col6";
        }

        wpqa_get_user_stats($wpqa_user_id,$user_stats,$active_points,$show_point_favorite);
		
		$size = 29;
        ?>
        <?php if ($my_role == "student") { ?>
	    <div class="user-follower">
            <ul class="row">
                <li class="col <?php echo esc_attr($column_follow)?> user-followers">
                    <div>
                        <a href="<?php echo esc_url(wpqa_get_profile_permalink($wpqa_user_id,"myparents"))?>"></a>
                        <h4><i class="icon-users"></i><?php esc_html_e("My Parents","wpqa")?></h4>
                        <div>
                            <?php
                            if (count($mom_data) > 0 || count($dad_data) > 0) {
                                if (isset($following_you) && is_array($following_you)) {
                                    if (count($mom_data) > 0) {
                                         ?> <a href="<?php echo esc_url(wpqa_get_profile_permalink($mom_data[0]->Relation_ID,"myparents"))?>"> <?php  echo wpqa_get_user_avatar(array("user_id" => $mom_data[0]->Relation_ID, "size" => $size)); ?> </a> <?php
                                    }
                                    if (count($dad_data) > 0) {
                                        ?> <a href="<?php echo esc_url(wpqa_get_profile_permalink($dad_data[0]->Relation_ID,"myparents"))?>"> <?php  echo wpqa_get_user_avatar(array("user_id" => $dad_data[0]->Relation_ID, "size" => $size)); ?> </a> <?php
                                    }
                                }
                            } else {?>
                            <span>
                                 <?php  esc_html_e("User doesn't have parents yet.","wpqa"); ?>
                                <?php }?>
                            </span>
                        </div>
                    </div>
                </li>
                <li class="col <?php echo esc_attr($column_follow)?> user-following">
                    <div>
                        <a href="<?php echo esc_url(wpqa_get_profile_permalink($wpqa_user_id,"following"))?>"></a>
                        <h4><i class="icon-users"></i><?php esc_html_e("My Sibling","wpqa")?></h4>
                        <div>
                            <?php

                            if (count($sibling_data) > 0) {
                                foreach ($sibling_data as $index_data) {
                                    ?> <a href="<?php echo esc_url(wpqa_get_profile_permalink($index_data->Relation_ID,"myparents"))?>"> <?php  echo wpqa_get_user_avatar(array("user_id" => $index_data->Relation_ID, "size" => $size)); ?> </a> <?php
                                    
                                }
                            } else {?>
                            <span>
                                  <?php  esc_html_e("User doesn't have siblings yet.","wpqa"); ?>
                               <?php }?>
                                </span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <?php } else if($my_role == "parent") {?>
            <div class="user-follower">
                <ul class="row">

                    <li class="col <?php echo esc_attr($column_follow)?> user-following">
                        <div>
                            <a href="<?php echo esc_url(wpqa_get_profile_permalink($wpqa_user_id,"following"))?>"></a>
                            <h4><i class="icon-users"></i><?php esc_html_e("My Children","wpqa")?></h4>
                            <div>
                                <?php
                                if (count($child_data) > 0) {
                                        foreach ($child_data as $index_data) {
                                            echo wpqa_get_user_avatar(array("user_id" => $index_data->Relation_ID, "size" => $size));
                                        }
                                } else {?>
                                <span>
                                  <?php  esc_html_e("User doesn't have Children yet.","wpqa"); ?>
                                  <?php }?>
                                </span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        <?php } ?>
        <?php
		if ((isset($user_stats_2["followers"]) && $user_stats_2["followers"] == "followers") || (isset($user_stats_2["i_follow"]) && $user_stats_2["i_follow"] == "i_follow")) {
			?>
        <div class="user-follower">
            <ul class="row">
                <?php if (isset($user_stats_2["followers"]) && $user_stats_2["followers"] == "followers") {?>
                    <li class="col <?php echo esc_attr($column_follow)?> user-followers">
                        <div>
                            <a href="<?php echo esc_url(wpqa_get_profile_permalink($wpqa_user_id,"followers"))?>"></a>
                            <h4><i class="icon-users"></i><?php esc_html_e("Followers","wpqa")?></h4>
                            <div>
                                <?php $followers = $last_followers = 0;
                                if (isset($following_you) && is_array($following_you)) {
                                    $followers = count($following_you);
                                }

                                if ($followers > 0) {
                                    $last_followers = $followers-4;
                                    if (isset($following_you) && is_array($following_you)) {
                                        $sliced_array = array_slice($following_you,0,4);
                                        foreach ($sliced_array as $key => $value) {
                                            echo wpqa_get_user_avatar(array("user_id" => $value,"size" => $size));
                                        }
                                    }
                                }?>
                                <span>
                                    <?php if ($last_followers > 0) {?>
                                        <span>+ <?php echo wpqa_count_number($last_followers)?></span> <?php echo _n("Follower","Followers",$last_followers,"wpqa")?>
                                    <?php }else if ($followers == 0) {
                                        esc_html_e("User doesn't have any followers yet.","wpqa");
                                    }?>
                                </span>
                            </div>
                        </div>
                    </li>
                <?php }
                if (isset($user_stats_2["i_follow"]) && $user_stats_2["i_follow"] == "i_follow") {?>
                    <li class="col <?php echo esc_attr($column_follow)?> user-following">
                        <div>
                            <a href="<?php echo esc_url(wpqa_get_profile_permalink($wpqa_user_id,"following"))?>"></a>
                            <h4><i class="icon-users"></i><?php esc_html_e("Following","wpqa")?></h4>
                            <div>
                                <?php $following = $last_following = 0;
                                if (isset($following_me) && is_array($following_me)) {
                                    $following = count($following_me);
                                }
                                if ($following > 0) {
                                    $last_following = $following-4;
                                    if (isset($following_me) && is_array($following_me)) {
                                        $sliced_array = array_slice($following_me,0,4);
                                        foreach ($sliced_array as $key => $value) {
                                            echo wpqa_get_user_avatar(array("user_id" => $value,"size" => $size));
                                        }
                                    }
                                }?>
                                <span>
                                    <?php if ($last_following > 0) {?>
                                        <span>+ <?php echo wpqa_count_number($last_following)?></span> <?php echo _n("Member","Members",$last_following,"wpqa")?>
                                    <?php }else if ($following == 0) {
                                        esc_html_e("User doesn't follow anyone.","wpqa");
                                    }?>
                                </span>
                            </div>
                        </div>
                    </li>
                <?php }?>
            </ul>
        </div> <!-- End user-follower -->
		<?php } if ($my_role == "student" && (count($mom_data) == 0 ||  count($dad_data) == 0)){ ?>
           <div class="user-follower">
            <h4><i class="icon-users"></i> Search Your Parent Profile</h4>
            <div>
                <form action="" method="post" class="find_parent"  enctype="multipart/form-data">
                <ul class="row" style=" padding-left: 15px;  padding-right: 15px;" >
                    <p style="margin: 0"> <label>Your Parent First Name*</label></p>
                    <input type="text" name="parent_first_name"  id="parent_first_name" style="width: 100%">
                </ul>
                <ul class="row"  style=" padding-left: 15px;  padding-right: 15px;" >
                    <label>Your Parent Last Name*</label>
                    <input type="text" name="parent_last_name" id="parent_last_name" style="width: 100%">
                </ul>
                <ul class="row"  style=" padding-left: 15px;  padding-right: 15px;" >
                    <label>Your Parent NRIC*</label>
                    <input type="text" name="parent_nric" id="parent_nric" style="width: 100%">
                </ul>
                <ul class="row" style="text-align: center">
                    <input type="hidden" value="parent_form_process" name="action">
                    <span class="load_span"><span class="loader_2"></span></span>
                    <input type="submit" style="width: 50%; text-align: center" class="button-default profile-button" value="Link" >
                </ul>
                </form>
            </div>
          </div>
        <?php }
        if ($my_role == "student") { ?>
            <div class="user-follower">
                <h4><i class="icon-users"></i> Search Your Sibling Profile</h4>
                <div>
                    <form action="" method="post" class="find_sibling"  enctype="multipart/form-data">
                        <ul class="row" style=" padding-left: 15px;  padding-right: 15px;" >
                            <p style="margin: 0"> <label>Your Sibling First Name*</label></p>
                            <input type="text" name="sibling_first_name"  id="sibling_first_name" style="width: 100%">
                        </ul>
                        <ul class="row"  style=" padding-left: 15px;  padding-right: 15px;" >
                            <label>Your Sibling Last Name*</label>
                            <input type="text" name="sibling_last_name" id="sibling_last_name" style="width: 100%">
                        </ul>
                        <ul class="row"  style=" padding-left: 15px;  padding-right: 15px;" >
                            <label>Your Sibling NRIC*</label>
                            <input type="text" name="sibling_nric" id="sibling_nric" style="width: 100%">
                        </ul>
                        <ul class="row" style="text-align: center">

                            <span class="load_span"><span class="loader_2"></span></span>
                            <input type="submit" style="width: 50%; text-align: center" class="button-default profile-button" value="Link" >
                        </ul>
                    </form>
                </div>
            </div>
        <?php }
            if ($my_role == "parent") { ?>
                <div class="user-follower">
                    <h4><i class="icon-users"></i> Search Your Child Profile</h4>
                    <div>
                        <form action="" method="post" class="find_child"  enctype="multipart/form-data">
                            <ul class="row" style=" padding-left: 15px;  padding-right: 15px;" >
                                <p style="margin: 0"> <label>Your Child First Name*</label></p>
                                <input type="text" name="child_first_name"  id="child_first_name" style="width: 100%">
                            </ul>
                            <ul class="row"  style=" padding-left: 15px;  padding-right: 15px;" >
                                <label>Your Child Last Name*</label>
                                <input type="text" name="child_last_name" id="child_last_name" style="width: 100%">
                            </ul>
                            <ul class="row"  style=" padding-left: 15px;  padding-right: 15px;" >
                                <label>Your Child NRIC*</label>
                                <input type="text" name="child_nric" id="child_nric" style="width: 100%">
                            </ul>
                            <ul class="row" style="text-align: center">

                                <span class="load_span"><span class="loader_2"></span></span>
                                <input type="submit" style="width: 50%; text-align: center" class="button-default profile-button" value="Link" >
                            </ul>
                        </form>
                    </div>
                </div>
            <?php }
        ?>

	</div><!-- End user-area-content -->
    <div class="send_relation_wrap-pop1" >
        <div class="card-container">
           <button class="cancel-button" name="send_relation_cancel" id="send_relation_cancel"> X </button>
            <div class="avatar">
                <img class="round"
                     src="https://randomuser.me/api/portraits/women/79.jpg"
                     alt="user"
                />
            </div>
            <h3 class="user_name">Ricky Park</h3>
            <h5>
                Yeah! We found who you are looking for, shall I send a request for link?
            </h5>

            <form action="" method="post"  enctype="multipart/form-data" class="send_relation_notification">
                <input type="hidden" name="relation_to_user_id" id="relation_to_user_id">
                <input type="hidden" name="relation_my_id" id="relation_my_id">
                <button type="submit" class="button-default" name="send_relation_yes" id="send_relation_yes">Yes</button>
                <button class="button-default" name="send_relation_no" id="send_relation_no">No</button>
            </form>

        </div>
    </div>
<?php }


do_action("wpqa_after_head_content_profile",$wpqa_user_id);?>

