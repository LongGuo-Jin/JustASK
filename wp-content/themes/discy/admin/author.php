<?php /* Author options */
function discy_admin_author($user_id = "") {
	
	$user = get_userdata($user_id);
	$get_current_user_id = get_current_user_id();
	
	$options = array();
	
	$options[] = array(
		'name' => esc_html__('Author Setting','discy'),
		'type' => 'heading-2'
	);

	$options[] = array(
		'id'    => 'from_admin',
		'std'   => 'yes',
		'type'  => 'hidden',
		'unset' => 'unset',
	);

	$options = apply_filters('discy_options_before_author_setting',$options,$user_id);
	
	$if_user_id = get_user_by("id",$user_id);
	if (((isset($if_user_id->caps["activation"]) && $if_user_id->caps["activation"] == 1) || (isset($if_user_id->caps["wpqa_under_review"]) && $if_user_id->caps["wpqa_under_review"] == 1)) && $get_current_user_id > 0 && $get_current_user_id != $user_id && is_super_admin($get_current_user_id)) {
		$protocol = is_ssl() ? 'https' : 'http';
		$options[] = array(
			'std'   => urldecode(wp_unslash($protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])),
			'id'    => 'redirect_to',
			'type'  => 'hidden',
			'unset' => 'unset',
		);
		if (isset($if_user_id->caps["activation"]) && $if_user_id->caps["activation"] == 1) {
			$options[] = array(
				'name'  => esc_html__('Select ON to activate this user.','discy'),
				'id'    => 'activate_user',
				'type'  => 'checkbox',
				'unset' => 'unset',
			);
		}else {
			$options[] = array(
				'name'  => esc_html__('Select ON to approve this user.','discy'),
				'id'    => 'approve_user',
				'type'  => 'checkbox',
				'unset' => 'unset',
			);
		}
	}

	$active_moderators = discy_options("active_moderators");
	if (!is_super_admin($user_id) && $active_moderators == "on") {
		$options[] = array(
			'name' => esc_html__('Choose this user as a moderator.','discy'),
			'id'   => prefix_author.'user_moderator',
			'type' => 'checkbox',
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_author.'user_moderator:not(0)',
			'type'      => 'heading-2'
		);
		$moderator_categories = get_user_meta($user_id,prefix_author."moderator_categories",true);
		$moderator_categories = (is_array($moderator_categories) && !empty($moderator_categories)?$moderator_categories:array());
		foreach ($moderator_categories as $key_cat => $value_cat) {
			$moderator_categories["cat-".$value_cat] = array("cat" => "yes","value" => $value_cat);
		}
		$options[] = array(
			'name'        => esc_html__('Choose the categories you need this user to moderator it.','discy'),
			'id'          => prefix_author.'categories_show',
			'type'        => 'questions_categories',
			'addto'       => prefix_author.'moderator_categories',
			'toadd'       => 'yes',
			'show_option' => esc_html__('All categories','discy'),
		);
		
		$options[] = array(
			'id'      => prefix_author.'moderator_categories',
			'type'    => 'multicheck',
			'sort'    => 'yes',
			'unset'   => 'unset',
			'val'     => $moderator_categories,
			'options' => array()
		);
		
		$options[] = array(
			'name' => esc_html__('Check ON if you need to choose custom moderators permissions for this user.','discy'),
			'id'   => prefix_author.'custom_moderators_permissions',
			'type' => 'checkbox',
		);

		$options[] = array(
			'div'       => 'div',
			'condition' => prefix_author.'custom_moderators_permissions:not(0)',
			'type'      => 'heading-2'
		);

		$options[] = array(
			'name'    => esc_html__('Select the custom permissions for this user','discy'),
			'id'      => prefix_author.'moderators_permissions',
			'type'    => 'multicheck',
			'std'     => array(
				"delete"  => "delete",
				"approve" => "approve",
				"edit"    => "edit",
				"ban"     => "ban",
			),
			'options' => array(
				"delete"  => esc_html__('Delete questions','discy'),
				"approve" => esc_html__('Approve questions','discy'),
				"edit"    => esc_html__('Edit questions','discy'),
				"ban"     => esc_html__("Ban users","discy"),
			)
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);

		$options[] = array(
			'type' => 'heading-2',
			'div'  => 'div',
			'end'  => 'end'
		);
	}
	
	if (current_user_can('upload_files')) {
		$user_meta_avatar = discy_options("user_meta_avatar");
		$user_meta_avatar = apply_filters("wpqa_user_meta_avatar",$user_meta_avatar);
		$user_meta_avatar = ($user_meta_avatar != ""?$user_meta_avatar:"you_avatar");
		$options[] = array(
			'name' => esc_html__('Your avatar','discy'),
			'id'   => $user_meta_avatar,
			'type' => 'upload'
		);

		$cover_image = discy_options("cover_image");
		if ($cover_image == "on") {
			$user_meta_cover = discy_options("user_meta_cover");
			$user_meta_cover = apply_filters("wpqa_user_meta_cover",$user_meta_cover);
			$user_meta_cover = ($user_meta_cover != ""?$user_meta_cover:"your_cover");
			$options[] = array(
				'name' => esc_html__('Your cover','discy'),
				'id'   => $user_meta_cover,
				'type' => 'upload'
			);
		}
	}
	
	$options[] = array(
		'name' => esc_html__('Add profile credential','discy'),
		'id'   => 'profile_credential',
		'type' => 'text',
	);
	
	$options[] = array(
		'name'    => esc_html__('Country','discy'),
		'id'      => 'country',
		'first'   => esc_html__('Select a country&hellip;','discy'),
		'type'    => 'select',
		'options' => apply_filters('wpqa_get_countries',false)
	);
	
	$options[] = array(
		'name' => esc_html__('City','discy'),
		'id'   => 'city',
		'type' => 'text',
	);
	
	$options[] = array(
		'name' => esc_html__('Age','discy'),
		'id'   => 'age',
		'type' => 'date',
		'js'   => array("changeMonth" => true,"changeYear" => true,"yearRange" => "-90:+00","dateFormat" => "yy-mm-dd"),
	);
	
	$options[] = array(
		'name' => esc_html__('Phone','discy'),
		'id'   => 'phone',
		'type' => 'text',
	);
	
	$gender_other = discy_options("gender_other");
	$gender_other = ($gender_other == "on"?array('3' => esc_html__('Other','discy')):array());
	$gender_options = array('1' => esc_html__('Male','discy'),'2' => esc_html__('Female','discy'))+$gender_other;
	
	$options[] = array(
		'name'    => esc_html__('Gender','discy'),
		'id'      => 'gender',
		'type'    => 'radio',
		'options' => $gender_options
	);
	
	$options[] = array(
		'name' => esc_html__('Check ON if you need this user is verified user.','discy'),
		'id'   => 'verified_user',
		'type' => 'checkbox',
	);
	
	$options[] = array(
		'name' => esc_html__('Show your private pages for all the users? (Points, favorite and followed pages).','discy'),
		'id'   => 'show_point_favorite',
		'type' => 'checkbox',
	);
	
	$active_message = discy_options("active_message");
	if ($active_message == "on") {
		$options[] = array(
			'name'      => esc_html__('Do you need to receive message from another users?','discy'),
			'id'        => 'received_message',
			'condition' => prefix_author.'unsubscribe_mails:not(on)',
			'type'      => 'checkbox',
		);
	}
	
	if (is_super_admin($get_current_user_id) && !is_super_admin($user->ID) && $active_message == "on") {
		$options[] = array(
			'name' => esc_html__('Do you need this user blocked to send messages?','discy'),
			'id'   => 'block_message',
			'type' => 'checkbox',
		);
	}
	
	$active_points = discy_options("active_points");
	if (is_super_admin($get_current_user_id) && $active_points == "on") {
		$options[] = array(
			'name'    => esc_html__('Add or remove points for the user','discy'),
			'id'      => 'add_remove_point',
			'type'    => 'select',
			'unset'   => 'unset',
			'options' => array('add' => esc_html__('Add','discy'),'remove' => esc_html__('Remove','discy'))
		);
		
		$options[] = array(
			'name'  => esc_html__('The points','discy'),
			'id'    => 'the_points',
			'type'  => 'text',
			'unset' => 'unset',
		);
		
		$options[] = array(
			'name'  => esc_html__('The reason','discy'),
			'id'    => 'the_reason',
			'type'  => 'text',
			'unset' => 'unset',
		);
	}
	
	if ($get_current_user_id > 0 && $get_current_user_id != $user_id && is_super_admin($get_current_user_id)) {
		$options[] = array(
			'name'  => esc_html__('Check ON if you need this user choose or remove the best answer','discy'),
			'id'    => 'user_best_answer',
			'type'  => 'checkbox',
		);
		
		$options[] = array(
			'id'    => 'admin',
			'std'   => 'save',
			'type'  => 'hidden',
			'unset' => 'unset',
		);
	}
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Mails Setting','discy'),
		'type' => 'heading-2'
	);

	$options[] = array(
		'name' => esc_html__('Show follow up email?','discy'),
		'id'   => 'follow_email',
		'type' => 'checkbox',
	);

	$options[] = array(
		'name'      => esc_html__('Do you need to get mails for the new payments?','discy'),
		'id'        => 'new_payment_mail',
		'std'       => 'on',
		'condition' => 'unsubscribe_mails:not(on)',
		'type'      => 'checkbox',
	);

	$options[] = array(
		'name'      => esc_html__('Do you need to get mails when new message sent?','discy'),
		'id'        => 'send_message_mail',
		'std'       => 'on',
		'condition' => 'unsubscribe_mails:not(on)',
		'type'      => 'checkbox',
	);

	$options[] = array(
		'name'      => esc_html__('Do you need to get mails when new answer added for your question?','discy'),
		'id'        => 'answer_on_your_question',
		'std'       => 'on',
		'condition' => 'unsubscribe_mails:not(on)',
		'type'      => 'checkbox',
	);

	$options[] = array(
		'name'      => esc_html__('Do you need to get mails when new answer added for your following question?','discy'),
		'id'        => 'answer_question_follow',
		'std'       => 'on',
		'condition' => 'unsubscribe_mails:not(on)',
		'type'      => 'checkbox',
	);
	
	$question_schedules = discy_options("question_schedules");
	$question_schedules_groups = discy_options("question_schedules_groups");
	if ($question_schedules == "on" && is_array($question_schedules_groups) && isset($user->roles[0]) && in_array($user->roles[0],$question_schedules_groups)) {
		$options[] = array(
			'name'      => esc_html__('Do you need to get schedules mails for the recent questions?','discy'),
			'id'        => 'question_schedules',
			'condition' => 'unsubscribe_mails:not(on)',
			'type'      => 'checkbox',
		);
	}
	
	$send_email_new_question = discy_options("send_email_new_question");
	$send_email_question_groups = discy_options("send_email_question_groups");
	if ($send_email_new_question == "on" && is_array($send_email_question_groups) && isset($user->roles[0]) && in_array($user->roles[0],$send_email_question_groups)) {
		$options[] = array(
			'name'      => esc_html__('Do you need to get mails when new question added?','discy'),
			'id'        => 'received_email',
			'condition' => 'unsubscribe_mails:not(on)',
			'std'       => 'on',
			'type'      => 'checkbox',
		);
	}

	$options[] = array(
		'name' => esc_html__('Do you need to unsubscribe all the mails?','discy'),
		'id'   => 'unsubscribe_mails',
		'type' => 'checkbox',
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	$options[] = array(
		'name' => esc_html__('Social Networking','discy'),
		'type' => 'heading-2'
	);
	
	$options[] = array(
		'name' => esc_html__('Facebook','discy'),
		'id'   => 'facebook',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Twitter','discy'),
		'id'   => 'twitter',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Linkedin','discy'),
		'id'   => 'linkedin',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Pinterest','discy'),
		'id'   => 'pinterest',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Instagram','discy'),
		'id'   => 'instagram',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Youtube','discy'),
		'id'   => 'youtube',
		'type' => 'text'
	);
	
	$options[] = array(
		'name' => esc_html__('Vimeo','discy'),
		'id'   => 'vimeo',
		'type' => 'text'
	);
	
	$options[] = array(
		'type' => 'heading-2',
		'end'  => 'end'
	);
	
	if (is_super_admin($get_current_user_id)) {
		$options[] = array(
			'name' => esc_html__('Advertising','discy'),
			'type' => 'heading-2'
		);
		
		$options[] = array(
			'type' => 'info',
			'name' => esc_html__('Advertising after header 1','discy')
		);
		
		$options[] = array(
			'name'    => esc_html__('Advertising type','discy'),
			'id'      => prefix_author.'header_adv_type_1',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		);
		
		$options[] = array(
			'name'      => esc_html__('Image URL','discy'),
			'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
			'id'        => prefix_author.'header_adv_img_1',
			'condition' => prefix_author.'header_adv_type_1:is(custom_image)',
			'type'      => 'upload'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => prefix_author.'header_adv_href_1',
			'std'       => '#',
			'condition' => prefix_author.'header_adv_type_1:is(custom_image)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => prefix_author.'header_adv_code_1',
			'condition' => prefix_author.'header_adv_type_1:is(display_code)',
			'type'      => 'textarea'
		);
		
		$options[] = array(
			'type' => 'info',
			'name' => esc_html__('Advertising after left menu','discy')
		);
		
		$options[] = array(
			'name'    => esc_html__('Advertising type','discy'),
			'id'      => prefix_author.'left_menu_adv_type',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		);
		
		$options[] = array(
			'name'      => esc_html__('Image URL','discy'),
			'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
			'id'        => prefix_author.'left_menu_adv_img',
			'type'      => 'upload',
			'condition' => prefix_author.'left_menu_adv_type:is(custom_image)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => prefix_author.'left_menu_adv_href',
			'std'       => '#',
			'type'      => 'text',
			'condition' => prefix_author.'left_menu_adv_type:is(custom_image)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => prefix_author.'left_menu_adv_code',
			'type'      => 'textarea',
			'condition' => prefix_author.'left_menu_adv_type:is(display_code)'
		);
		
		$options[] = array(
			'type' => 'info',
			'name' => esc_html__('Advertising after content','discy')
		);
		
		$options[] = array(
			'name'    => esc_html__('Advertising type','discy'),
			'id'      => prefix_author.'content_adv_type',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		);
		
		$options[] = array(
			'name'      => esc_html__('Image URL','discy'),
			'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
			'id'        => prefix_author.'content_adv_img',
			'type'      => 'upload',
			'condition' => prefix_author.'content_adv_type:is(custom_image)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => prefix_author.'content_adv_href',
			'std'       => '#',
			'type'      => 'text',
			'condition' => prefix_author.'content_adv_type:is(custom_image)'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising Code html ( Ex: Google ads)','discy'),
			'id'        => prefix_author.'content_adv_code',
			'type'      => 'textarea',
			'condition' => prefix_author.'content_adv_type:is(display_code)'
		);
		
		$options[] = array(
			'name' => esc_html__('Between questions or posts','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name'    => esc_html__('Advertising type','discy'),
			'id'      => prefix_author.'between_adv_type',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		);
		
		$options[] = array(
			'name'      => esc_html__('Image URL','discy'),
			'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
			'id'        => prefix_author.'between_adv_img',
			'condition' => prefix_author.'between_adv_type:is(custom_image)',
			'type'      => 'upload'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => prefix_author.'between_adv_href',
			'std'       => '#',
			'condition' => prefix_author.'between_adv_type:is(custom_image)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising Code html (Ex: Google ads)','discy'),
			'id'        => prefix_author.'between_adv_code',
			'condition' => prefix_author.'between_adv_type:not(custom_image)',
			'type'      => 'textarea'
		);
		
		$options[] = array(
			'name' => esc_html__('Between comments or answers','discy'),
			'type' => 'info'
		);
		
		$options[] = array(
			'name'    => esc_html__('Advertising type','discy'),
			'id'      => prefix_author.'between_comments_adv_type',
			'std'     => 'custom_image',
			'type'    => 'radio',
			'options' => array("display_code" => esc_html__("Display code","discy"),"custom_image" => esc_html__("Custom Image","discy"))
		);
		
		$options[] = array(
			'name'      => esc_html__('Image URL','discy'),
			'desc'      => esc_html__('Upload a image, or enter URL to an image if it is already uploaded.','discy'),
			'id'        => prefix_author.'between_comments_adv_img',
			'condition' => prefix_author.'between_comments_adv_type:is(custom_image)',
			'type'      => 'upload'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising url','discy'),
			'id'        => prefix_author.'between_comments_adv_href',
			'std'       => '#',
			'condition' => prefix_author.'between_comments_adv_type:is(custom_image)',
			'type'      => 'text'
		);
		
		$options[] = array(
			'name'      => esc_html__('Advertising Code html (Ex: Google ads)','discy'),
			'id'        => prefix_author.'between_comments_adv_code',
			'condition' => prefix_author.'between_comments_adv_type:not(custom_image)',
			'type'      => 'textarea'
		);
	}
	
	return $options;
}