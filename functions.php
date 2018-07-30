<?php
if (!function_exists('check_contact_permissions')) {

	function check_contact_permissions($userid) {
		global $wpdb;
        $current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		$dsp_user_privacy_table = $wpdb->prefix . DSP_USER_PRIVACY_TABLE;
		$dsp_question_details = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
		if ($user_id != $userid) {
			$member_privacy_settings = $wpdb->get_row("SELECT * FROM $dsp_user_privacy_table WHERE user_id = '$userid'");
			if ($member_privacy_settings != null) {
				//viewed user privacy profile questions
				$saved_contact_permission = unserialize($member_privacy_settings->contact_permission);
				$profile_gender = $wpdb->get_var("select gender from $dsp_user_profiles where user_id='$user_id'");
				//loggein user edit profile questions
				$profile_answers = $wpdb->get_results("select profile_question_option_id from $dsp_question_details where user_id='$user_id' and profile_question_option_id!=0"); 
				if (in_array($profile_gender, explode(',', $saved_contact_permission['gender']))) {
					$flag = 0;
					$contact_questions = explode(',', $saved_contact_permission['profile_questions']);
					foreach ($profile_answers as $answers) {
						if (!in_array($answers->profile_question_option_id, $contact_questions)) {
							$flag+=1;
						}
					}
					if ($flag != 0) {
						return false;
					} else {
						return true;
					}
				} else {
					return false;
				}
			} else {
				return true;
			}
		} else {
			return true;
		}
		//return $saved_contact_permission;
	}

}


if (!function_exists('getSSOToken')) {

	function getSSOToken($apiKey, $displayName, $email, $avatarIcon, $avatarThumb, $avatarFull, $line1, $line2, $line3, $line4, $userId) {

		$rv = "";

		$raw = '&avatarFull=' . base64_encode($avatarFull);

		$raw .= '&avatarIcon=' . base64_encode($avatarIcon);

		$raw .= '&avatarThumb=' . base64_encode($avatarThumb);

		$raw .= '&displayName=' . base64_encode($displayName);

		$raw .= '&email=' . base64_encode($email);

		$raw .= '&line1=' . base64_encode($line1);

		$raw .= '&line2=' . base64_encode($line2);

		$raw .= '&line3=' . base64_encode($line3);

		$raw .= '&line4=' . base64_encode($line4);

		$raw .= '&ts=' . base64_encode(date("U"));

		$raw .= '&userId=' . base64_encode($userId);



		$raw .= '&role=default';

		$rv = $raw . '&token=' . md5($raw . '&apiKey=' . $apiKey);

		return $rv;
	}

}

if (!function_exists('GetAge')) {

	function GetAge($Birthdate) {

		$dob = strtotime($Birthdate);

		$y = date('Y', $dob);

		if (($m = (date('m') - date('m', $dob))) < 0) {

			$y++;
		} elseif ($m == 0 && date('d') - date('d', $dob) < 0) {

			$y++;
		}

		return date('Y') - $y;
	}

}

// START FUNCTION CREATE MEMBER PHOTO PATH

if (!function_exists('display_members_original_photo')) {

	function display_members_original_photo($photo_member_id, $path) { 

		global $wpdb;

		$dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;

		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

		$count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE user_id='$photo_member_id' AND status_id=1");

		if ($count_member_images > 0) {

			$member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '$photo_member_id' AND status_id=1");

			if ($member_exist_picture->picture == "") {

				$check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");

				if ($check_gender->gender == 'M') {

					$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
				} else if ($check_gender->gender == 'F') {

					$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
				} else if ($check_gender->gender == 'C') {

					$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
				}
			} else {

				$Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/" . $member_exist_picture->picture;
				$Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);
				$physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/" . $member_exist_picture->picture;

				if (file_exists($physical_image_path)) {

					$Mem_Image_path = $Mem_Image_path;
				} else {

					$check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");

				if ($check_gender->gender == 'M') {

					$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
				} else if ($check_gender->gender == 'F') {

					$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
				} else if ($check_gender->gender == 'C') {

					$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
				}
				}
			}
		} else {

			$check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");

            if(isset($check_gender)) {
                if ($check_gender->gender == 'M') {
                    $Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
                } else if ($check_gender->gender == 'F') {

                    $Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
                } else if ($check_gender->gender == 'C') {

                    $Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
                }
            } else {
                $Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
            }

//$Mem_Image_path=$path."images/no-image.jpg";
		}

		return $Mem_Image_path;
	}

}

// END FUNCTION CREATE MEMBER PHOTO PATH
// START FUNCTION CREATE thumb2 MEMBER PHOTO PATH

if (!function_exists('display_thumb2_members_photo')) {

	function display_thumb2_members_photo($photo_member_id, $path) {

		global $wpdb;

		$dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;

		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

		$count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE user_id='$photo_member_id' AND status_id=1");

		if ($count_member_images > 0) {

			$member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '$photo_member_id' AND status_id=1");

			if ($member_exist_picture->picture == "") {

				$check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");

				if ($check_gender->gender == 'M') {

					$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
				} else if ($check_gender->gender == 'F') {

					$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
				} else if ($check_gender->gender == 'C') {

					$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
				}

//$Mem_Image_path=$path."images/no-image.jpg";
			} else {

				$Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
				$Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);
				$physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;

				if (file_exists($physical_image_path)) {

					$Mem_Image_path = $Mem_Image_path;
				} else {

					$check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");

					if ($check_gender->gender == 'M') {

						$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
					} else if ($check_gender->gender == 'F') {

						$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
					} else if ($check_gender->gender == 'C') {

						$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
					}
				}
			}
		} else {

			$check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");

			if ($check_gender->gender == 'M') {

				$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
			} else if ($check_gender->gender == 'F') {

				$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
			} else if ($check_gender->gender == 'C') {

				$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
			}

//$Mem_Image_path=$path."images/no-image.jpg";
		}

		return $Mem_Image_path;
	}

// END FUNCTION CREATE thumb2  MEMBER PHOTO PATH
}

if (!function_exists('display_members_photo')) {

	/** *******************START FUNCTION CREATE thumb MEMBER PHOTO PATH************************ */

	function display_members_photo($photo_member_id, $path) { 
		global $wpdb;
		$favt_mem = array();
		
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;  // print session USER_ID

		$dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;

		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

		$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;

		$count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE user_id='$photo_member_id' AND status_id=1");
		if ($count_member_images > 0) {
			$member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '$photo_member_id' AND status_id=1");
			$check_gender = $wpdb->get_row("SELECT gender,make_private FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
			if ($member_exist_picture->picture == "") {
				if ($check_gender->gender == 'M') {

					$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
				} else if ($check_gender->gender == 'F') {

					$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
				} else if ($check_gender->gender == 'C') {

					$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
				}
			//$Mem_Image_path=$path."images/no-image.jpg";
			} else {
				if ($photo_member_id == $user_id) {
					$Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
					$physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
				} else {
					$private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$photo_member_id'");

					foreach ($private_mem as $private) {

						$favt_mem[] = $private->favourite_user_id;
					}
					if ($check_gender->make_private == 'Y') {
						if (!in_array($user_id, $favt_mem)) {
							$Mem_Image_path = plugins_url('dsp_dating/images/private-photo-pic.jpg');
							$physical_image_path = ABSPATH . '/wp-content/plugins/'.'dsp_dating/images/private-photo-pic.jpg';
						} else {
							$Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
							$physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
						}
					} else {
						$Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
						$physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
					}
				}
				$Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);

				if (file_exists($physical_image_path)) {

					$Mem_Image_path = $Mem_Image_path;
				} else {
                   if ($check_gender->gender == 'M') {

						$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
					} else if ($check_gender->gender == 'F') {

						$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
					} else if ($check_gender->gender == 'C') {

						$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
					}
				}
			}
		} else {

            $check_gender = $wpdb->get_row("SELECT * FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
            if (isset($check_gender)) {
                if ($check_gender->gender == 'M') {

                    $Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
                } else if ($check_gender->gender == 'F') {

                    $Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
                } else if ($check_gender->gender == 'C') {

                    $Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
                }
            }
            else {
                $Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
            }

//$Mem_Image_path=$path."images/no-image.jpg";
		}

		return $Mem_Image_path;
	}

}

/* * *********************** END FUNCTION CREATEthumb  MEMBER PHOTO PATH ************************ */

if (!function_exists('display_members_photo_thumb')) {

	/*     * *******************START FUNCTION CREATE thumb MEMBER PHOTO PATH************************ */

	function display_members_photo_thumb($photo_member_id, $path) {

		global $wpdb;

		$favt_mem = array();
        $current_user = wp_get_current_user();

		$user_id = $current_user->ID;  // print session USER_ID

		$dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;

		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

		$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;

		$count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE user_id='$photo_member_id' AND status_id=1");

		if ($count_member_images > 0) {

			$member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '$photo_member_id' AND status_id=1");
			$check_gender = $wpdb->get_row("SELECT gender,make_private FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");

			if ($member_exist_picture->picture == "") {



				if ($check_gender->gender == 'M') {

					$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
				} else if ($check_gender->gender == 'F') {

					$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
				} else if ($check_gender->gender == 'C') {

					$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
				}

//$Mem_Image_path=$path."images/no-image.jpg";
			} else {
				if ($photo_member_id == $user_id) {
					$Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
					$physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
				} else {
					$private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$photo_member_id'");

					foreach ($private_mem as $private) {

						$favt_mem[] = $private->favourite_user_id;
					}
					if ($check_gender->make_private == 'Y') {
						if (!in_array($user_id, $favt_mem)) {
							$Mem_Image_path = plugins_url('dsp_dating/images/private-photo-pic.jpg');
							$physical_image_path = ABSPATH . '/wp-content/plugins/'.'dsp_dating/images/private-photo-pic.jpg';
						} else {
							$Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
							$physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
						}
					} else {
						$Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
						$physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
					}
				}
				$Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);

				if (file_exists($physical_image_path)) {

					$Mem_Image_path = $Mem_Image_path;
				} else {
					if ($check_gender->gender == 'M') {

						$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
					} else if ($check_gender->gender == 'F') {

						$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
					} else if ($check_gender->gender == 'C') {

						$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
					}
				}
			}
		} else {

			$check_gender = $wpdb->get_row("SELECT * FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");

			if ($check_gender->gender == 'M') {

				$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
			} else if ($check_gender->gender == 'F') {

				$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
			} else if ($check_gender->gender == 'C') {

				$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
			}

//$Mem_Image_path=$path."images/no-image.jpg";
		}

		return $Mem_Image_path;
	}

}

/* * *********************** END FUNCTION CREATEthumb  MEMBER PHOTO PATH ************************ */

/* * *******************START FUNCTION CREATE thumb MEMBER PARTNER PHOTO PATH************************ */

if (!function_exists('display_members_partner_photo')) {

	function display_members_partner_photo($photo_member_id, $path) {

		global $wpdb;

		$dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;

		$dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;

		$count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_partner_photos_table WHERE user_id='$photo_member_id' AND status_id=1");

		if ($count_member_images > 0) {

			$member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_partner_photos_table WHERE user_id = '$photo_member_id' AND status_id=1");

			if ($member_exist_picture->picture == "") {

				$check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_partner_profiles_table  WHERE user_id = '$photo_member_id'");
					if ($check_gender->gender == 'M') {

						$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
					} else if ($check_gender->gender == 'F') {

						$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
					} else if ($check_gender->gender == 'C') {

						$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
					}

//$Mem_Image_path=$path."images/no-image.jpg";
			} else {

				$Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
				$physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;

				$Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);

				if (file_exists($physical_image_path)) {

					$Mem_Image_path = $Mem_Image_path;
				} else {

					$check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");

					if ($check_gender->gender == 'M') {

						$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
					} else if ($check_gender->gender == 'F') {

						$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
					} else if ($check_gender->gender == 'C') {

						$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
					}
				}
			}
		} else {

			$check_gender = $wpdb->get_row("SELECT * FROM $dsp_user_partner_profiles_table  WHERE user_id = '$photo_member_id'");

			$count_profile_partner = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_partner_profiles_table  WHERE user_id = '$photo_member_id'");

			if ($count_profile_partner > 0) {

				if ($check_gender->gender == 'M') {

					$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
				} else if ($check_gender->gender == 'F') {

					$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
				} else if ($check_gender->gender == 'C') {

					$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
				}
			} else {

				$Mem_Image_path =  WPDATE_URL . "/images/male-generic.jpg";
			}

//$Mem_Image_path=$path."images/no-image.jpg";
		}

		return $Mem_Image_path;
	}

}

/* * *********************** END FUNCTION CREATEthumb  MEMBER PARTNER PHOTO PATH ************************ */

// START FUNCTION CREATE MEMBER PARTNER PHOTO PATH

if (!function_exists('display_members_partner_original_photo')) {

	function display_members_partner_original_photo($photo_member_id, $path) {

		global $wpdb;

		$dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;

		$dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;

		$count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_partner_photos_table WHERE user_id='$photo_member_id' AND status_id=1");

		if ($count_member_images > 0) {

			$member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_partner_photos_table WHERE user_id = '$photo_member_id' AND status_id=1");

			if ($member_exist_picture->picture == "") {

				$check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_partner_profiles_table  WHERE user_id = '$photo_member_id'");

				if ($check_gender->gender == 'M') {

					$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
				} else if ($check_gender->gender == 'F') {

					$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
				} else if ($check_gender->gender == 'C') {

					$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
				}
			} else {

				$Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/" . $member_exist_picture->picture;
				$physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/" . $member_exist_picture->picture;
				$Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);

				if (file_exists($physical_image_path)) {

					$Mem_Image_path = $Mem_Image_path;
				} else {

					$check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");

					if ($check_gender->gender == 'M') {

						$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
					} else if ($check_gender->gender == 'F') {

						$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
					} else if ($check_gender->gender == 'C') {

						$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
					}
				}
			}
		} else {

			$check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_partner_profiles_table  WHERE user_id = '$photo_member_id'");

			if ($check_gender->gender == 'M') {

				$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
			} else if ($check_gender->gender == 'F') {

				$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
			} else if ($check_gender->gender == 'C') {

				$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
			}
//$Mem_Image_path=$path."images/no-image.jpg";
		}

		return $Mem_Image_path;
	}

}

// END FUNCTION CREATE MEMBER PARTNER PHOTO PATH
// START FUNCTION CREATE thumb2 MEMBER PHOTO PATH

if (!function_exists('display_thumb2_members_partner_photo')) {

	function display_thumb2_members_partner_photo($photo_member_id, $path) {

		global $wpdb;

		$dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;

		$dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;

		$count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_partner_photos_table WHERE user_id='$photo_member_id' AND status_id=1");

		if ($count_member_images > 0) {

			$member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_partner_photos_table WHERE user_id = '$photo_member_id' AND status_id=1");

			if ($member_exist_picture->picture == "") {

				$check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_partner_profiles_table  WHERE user_id = '$photo_member_id'");

				if ($check_gender->gender == 'M') {

					$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
				} else if ($check_gender->gender == 'F') {

					$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
				} else if ($check_gender->gender == 'C') {

					$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
				}

//$Mem_Image_path=$path."images/no-image.jpg";
			} else {

				$Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
				$physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
				$Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);

				if (file_exists($physical_image_path)) {

					$Mem_Image_path = $Mem_Image_path;
				} else {

					$check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");

					if ($check_gender->gender == 'M') {

						$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
					} else if ($check_gender->gender == 'F') {

						$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
					} else if ($check_gender->gender == 'C') {

						$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
					}
				}
			}
		} else {

			$check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_partner_profiles_table  WHERE user_id = '$photo_member_id'");

			if ($check_gender->gender == 'M') {

				$Mem_Image_path = WPDATE_URL . "/images/male-generic.jpg";
			} else if ($check_gender->gender == 'F') {

				$Mem_Image_path = WPDATE_URL . "/images/female-generic.jpg";
			} else if ($check_gender->gender == 'C') {

				$Mem_Image_path = WPDATE_URL . "/images/couples-generic.jpg";
			}

//$Mem_Image_path=$path."images/no-image.jpg";
		}

		return $Mem_Image_path;
	}

}

// END FUNCTION CREATE thumb2  MEMBER PHOTO PATH

if (!function_exists('daysDifference')) {

	function daysDifference($endDate, $beginDate) {

		//explode the date by "-" and storing to array

		$date_parts1 = explode("-", $beginDate);

		$date_parts2 = explode("-", $endDate);



		//gregoriantojd() Converts a Gregorian date to Julian Day Count

		@$start_date = gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);

		@$end_date = gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
		
		return $end_date - $start_date;
	}

}



// ------------------ calculate date difrence -----------------------//
// ------------------function to check member has a membershp plan to Access Premium feature -----------------------//

if (!function_exists('check_membership')) {

	function check_membership($access_feature_name, $user_id) { 
		global $wpdb;
		$dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
		$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
		$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
		$featureId = $wpdb->get_var("SELECT `feature_id` FROM $dsp_features_table where feature_name LIKE '%$access_feature_name%'");
		$pay_plan_id = $wpdb->get_var("SELECT pay_plan_id 	FROM $dsp_payments_table where pay_user_id=$user_id");
		$memberships_feature_row = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table where membership_id='" . $pay_plan_id . "'");
		$no_of_credits = dsp_get_credits_of_user($user_id);
		$giftSettingValue = dsp_get_credit_setting_value('emails_per_credit');
		$creditMode = apply_filters('dsp_get_general_setting_value','credit');
		$isUnderPremiumPlan = false;
		foreach ($memberships_feature_row as $membership_feature)
			$premium_access_feature = $membership_feature->premium_access_feature;

		if (!empty($premium_access_feature))
			$access_feature_id = explode(",", $premium_access_feature);
		else
			$access_feature_id = array('0');
		if(in_array($featureId,$access_feature_id)){
			$isUnderPremiumPlan = true;
		}
		for ($i = 0; $i < count($access_feature_id); ++$i) {
			$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
			$feature_name = $wpdb->get_var("SELECT `feature_name` FROM $dsp_features_table where feature_id=" . $access_feature_id[$i]);
			if (isset($feature_name) && ($feature_name == $access_feature_name)){
				$name = $feature_name;
			}
		}
		$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
		$dsp_premium_access_feature_table = $wpdb->prefix . DSP_PREMIUM_ACCESS_FEATURE_TABLE;

		if (isset($name)) {

			$features_list_id = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_name='$name'");

			$feature_id = $features_list_id->feature_id;
		} else {

			$feature_id = 0;
		}

		$premium_access_features = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_features_table where feature_id='$feature_id'");
		if ($premium_access_features > 0) {

			$check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");

			if ($check_member_payment > 0) {

				$check_account_expire = $wpdb->get_row("SELECT * FROM $dsp_payments_table where pay_user_id='$user_id'");

				$start_date = $check_account_expire->start_date;

				$payment_status = $check_account_expire->payment_status;

				$expiration_date = $check_account_expire->expiration_date;

				$pay_plan_days = $check_account_expire->pay_plan_days;

				$current_date = date("Y-m-d");

				//$cal_days = daysDifference($current_date, $start_date);
				$cal_days = daysDifference($expiration_date, $current_date);

				//if ($cal_days > $pay_plan_days) {
				if ($cal_days <= 0) {
					if ($payment_status == '1') {

						$wpdb->query("UPDATE $dsp_payments_table SET payment_status=2 WHERE pay_user_id = '$check_account_expire->pay_user_id'");
					}

					$msg = "Expired";
				} else {
					$msg = "Access";
				} // End if($cal_expire_date>=$expiration_date)
			} else {

				$msg = "Onlypremiumaccess";
			} // End if($check_member_payment>0)
		} else if ($premium_access_features == 0) {
			$check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id' AND DATEDIFF(`expiration_date`, NOW()) > 0");
			if ($check_member_payment > 0 && $isUnderPremiumPlan) {
				$msg = "Access";
			} else {
				$rows = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table",ARRAY_A);
				foreach ($rows as $row) {
					$premium_access_feature = $row['premium_access_feature'];

					if (!empty($premium_access_feature))
						$access_feature_id = explode(",", $premium_access_feature);
					else
						$access_feature_id = 0;

					for ($i = 0; $i < count($access_feature_id); ++$i) {

						$access_feature_id[$i];



						$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;



						$access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table");

						foreach ($access_feature_row as $access_feature) {

							$feature_id = $access_feature->feature_id;

							//echo $access_feature_id[$i]."-----------".$feature_id."<br>";

							if ($access_feature_id[$i] == $feature_id)
								$name = $access_feature_id[$i];
						}

						//echo $name;
						//echo "SELECT * FROM $dsp_features_table where feature_id=$name";

						$a = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_id='$name'");

                        if($a){
                            $feature_name = $a->feature_name;
                        }

						if (isset($feature_name) && $feature_name == $access_feature_name)
							$name1 = $feature_name;
					}
				}
//				Commented out
				if (
					(@$name1 == '' &&  $creditMode->setting_status == 'N') ||
					(@$name1 == '' && ($no_of_credits >= $giftSettingValue && $creditMode->setting_status == 'Y')) ||
					(@$name1 == '' && $check_member_payment > 0)
				) {
					$msg = "Access";
				} else {
					$msg = "Onlypremiumaccess";
				}
			}
		} else {
			$msg = "Access";
		} // End if($premium_access_features>0)

		return $msg;
	}

// End function 
}

// ------------------function to check member has a membershp plan to Access Premium feature -----------------------//
// ------------------function to check free trail mode  -----------------------//

if (!function_exists('check_free_trial_email_feature')) {

	function check_free_trial_email_feature($access_feature_name, $user_id) {
		global $wpdb;
		$dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
		$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
		$general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_trail_gender'");
		$free_trail_gender = $general_settings->setting_value;
		$general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_email_access_gender'");
		$free_email_access_gender = $general_settings->setting_value;
        $dateTimeFormat = dsp_get_date_timezone();
        extract($dateTimeFormat);
		$free_trail_days_limit = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_trail_mode'");
		$free_trail_days = $free_trail_days_limit->setting_value;
		$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
		$user_registered = $wpdb->get_row("SELECT * FROM $dsp_user_table where ID=$user_id");
        $current_date = date("Y-m-d H:i:s", time());
        $creditMode = apply_filters('dsp_get_general_setting_value','credit');
		$days = daysDifference($current_date, ($user_registered->user_registered));
		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
		$gender_field = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles where user_id=$user_id");
		$user_gender = $gender_field->gender;
		$dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
		$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
		$pay_plan_id = $wpdb->get_var("SELECT pay_plan_id 	FROM $dsp_payments_table where pay_user_id=$user_id");
		$memberships_feature_row = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table where membership_id='" . $pay_plan_id . "'");
		$no_of_credits = dsp_get_credits_of_user($user_id);
		$giftSettingValue = dsp_get_credit_setting_value('emails_per_credit');
		foreach ($memberships_feature_row as $membership_feature)
			$premium_access_feature = $membership_feature->premium_access_feature;

		if (!empty($premium_access_feature))
			$access_feature_id = explode(",", $premium_access_feature);
		else
			$access_feature_id = array('0');

		for ($i = 0; $i < count($access_feature_id); ++$i) {

			$access_feature_id[$i];

			$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;

			$access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table where feature_id=" . $access_feature_id[$i]);

			foreach ($access_feature_row as $access_feature)
				$feature_name = $access_feature->feature_name;

			if (isset($feature_name) && $feature_name == $access_feature_name)
				$name = $feature_name;
		}

		$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;

		$dsp_premium_access_feature_table = $wpdb->prefix . DSP_PREMIUM_ACCESS_FEATURE_TABLE;

		if (isset($name)) {

			$features_list_id = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_name='$name'");

			$feature_id = $features_list_id->feature_id;
		} else {

			$feature_id = 0;
		}
		$premium_access_features = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_features_table where feature_id='$feature_id'");
		$check_free_email_access_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings WHERE setting_name = 'free_email_access'");
		$check_free_email_access_mode->setting_status;
		$check_force_profile_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings WHERE setting_name = 'force_profile'");
		$check_force_profile_mode->setting_status;
		$user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id");
		$user_profile = $wpdb->get_row("SELECT status_id FROM $dsp_user_profiles WHERE user_id=$user_id");
		$status_id = $user_profile->status_id;

		if ($premium_access_features > 0) {

			$check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");

			if ($check_member_payment > 0) {

				$check_account_expire = $wpdb->get_row("SELECT * FROM $dsp_payments_table where pay_user_id='$user_id'");

				$start_date = $check_account_expire->start_date;

				$payment_status = $check_account_expire->payment_status;

				$expiration_date = $check_account_expire->expiration_date;

				$pay_plan_days = $check_account_expire->pay_plan_days;

				$current_date = date("Y-m-d");

				//$cal_days = daysDifference($current_date, $start_date);
				$cal_days = daysDifference($expiration_date, $current_date);

				//if ($cal_days > $pay_plan_days) {
				if ($cal_days <= 0) {
					if ($payment_status == '1') {

						$wpdb->query("UPDATE $dsp_payments_table SET payment_status=2 WHERE pay_user_id = '$check_account_expire->pay_user_id'");
					}

					$msg = "Expired";
				} else {

					$msg = "Access";
				} // End if($cal_expire_date>=$expiration_date)
			} else {

				$msg = "Onlypremiumaccess";
			} // End if($check_member_payment>0)
		} else if ($premium_access_features == 0) {

			$check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id' AND DATEDIFF(`expiration_date`, NOW()) > 0");

			if ($check_member_payment > 0) {

				$msg = "Access";
			} else {


				$rows = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table",ARRAY_A);
				
				/*$memberships_feature_row = mysql_query("SELECT premium_access_feature FROM $dsp_memberships_table ");*/
				//while ($row = mysql_fetch_array($memberships_feature_row)) {
				if(count($rows) > 0) {

					foreach ($rows as $row) { 
						$premium_access_feature = $row['premium_access_feature'];
						if (!empty($premium_access_feature))
							$access_feature_id = explode(",", $premium_access_feature);
						else
							$access_feature_id = 0;

						
						for ($i = 0; $i < count($access_feature_id); ++$i) {

							$access_feature_id[$i];



							$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;



							$access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table");
							foreach ($access_feature_row as $access_feature) {

								$feature_id = $access_feature->feature_id;

								//echo $access_feature_id[$i]."-----------".$feature_id."<br>";

								if ($access_feature_id[$i] == $feature_id)
									$name = $access_feature_id[$i];
							}

							//echo $name;
							//echo "SELECT * FROM $dsp_features_table where feature_id=$name";

							$a = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_id=$name");
							$feature_name = $a->feature_name;

							if (isset($feature_name) && $feature_name == $access_feature_name)
								$name1 = $feature_name;
						}
					}
				}

				//echo $name1; 
				
				if (
					(@$name1 == '' &&  $creditMode->setting_status == 'N') || 
					(@$name1 == '' && ($no_of_credits >= $giftSettingValue && $creditMode->setting_status == 'Y')) || 
					(@$name1 == '' && $check_member_payment > 0) 
				) {
					$msg = "Access";
				} else if (($free_trail_gender == 1) && ($user_gender == 'M')) {

					if ($days <= $free_trail_days) {

						$msg = "Access";
					} else {//Expired	
						$msg = "Expired";
					}
				} else if (($free_trail_gender == 2) && ($user_gender == 'F')) {

					if ($days <= $free_trail_days) {

						$msg = "Access";
					} else {//Expired	
						$msg = "Expired";
					}
				} else if (($free_trail_gender == 3)) {

					if ($days <= $free_trail_days) {

						$msg = "Access";
					} else {//Expired	
						$msg = "Expired";
					}
				} else if ($user_profile_exist == 0) {

					$msg = "NotExist";
				} else if ($status_id == 0) {

					$msg = "Approved";
				} else if ($check_free_email_access_mode->setting_status == "Y") {



					if (($free_email_access_gender == 1) && ($user_gender == 'M')) {

						$msg = "Access";
					} else if (($free_email_access_gender == 2) && ($user_gender == 'F')) {

						$msg = "Access";
					} else {

						$msg = "Onlypremiumaccess";
					}
				} else {

					$msg = "Onlypremiumaccess";
				}
			}
		} else {
			$msg = "NoAccess";
		}
	return $msg;
	}

}

if (!function_exists('check_free_trial_feature')) {

	function check_free_trial_feature($access_feature_name, $user_id) {
		global $wpdb;
		$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
		$general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_trail_gender'");
		$isUnderPremiumPlan = false;
		$free_trail_gender = $general_settings->setting_value;
		$general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_email_access_gender'");
		$free_email_access_gender = $general_settings->setting_value;
		$free_trail_days_limit = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_trail_mode'");
		$free_trail_days = $free_trail_days_limit->setting_value;
		$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
		$user_registered = $wpdb->get_row("SELECT * FROM $dsp_user_table where ID=$user_id");
		$current_date = date("Y-m-d H:i:s", time());
		$creditMode = apply_filters('dsp_get_general_setting_value','credit');
		$no_of_credits = dsp_get_credits_of_user($user_id);
		$giftSettingValue = dsp_get_credit_setting_value('emails_per_credit');
		/* $diff = abs(strtotime($current_date) - strtotime($user_registered->user_registered)); 

		  $years   = floor($diff / (365*60*60*24));

		  $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)); */

		$days = daysDifference($current_date, ($user_registered->user_registered));


		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;



		$gender_field = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles where user_id=$user_id");

		$user_gender = $gender_field->gender;



		$dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;

		$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;

		$pay_plan_id = $wpdb->get_var("SELECT pay_plan_id 	FROM $dsp_payments_table where pay_user_id=$user_id");



		$memberships_feature_row = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table where membership_id='" . $pay_plan_id . "'");

		foreach ($memberships_feature_row as $membership_feature)
			$premium_access_feature = $membership_feature->premium_access_feature;
		if (!empty($premium_access_feature))
			$access_feature_id = explode(",", $premium_access_feature);
		else
			$access_feature_id = array('0');

		for ($i = 0; $i < count($access_feature_id); ++$i) {

			$access_feature_id[$i];

			$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;

			$access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table where feature_id=" . $access_feature_id[$i]);
			foreach ($access_feature_row as $access_feature)
				$feature_name = $access_feature->feature_name;
			if (isset($feature_name) && $feature_name == $access_feature_name){
				$isUnderPremiumPlan = true;
				$name = $feature_name;
			}
		}
		$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;

		$dsp_premium_access_feature_table = $wpdb->prefix . DSP_PREMIUM_ACCESS_FEATURE_TABLE;

		if (isset($name)) {

			$features_list_id = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_name='$name'");

			$feature_id = $features_list_id->feature_id;
		} else {

			$feature_id = 0;
		}
		$premium_access_features = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_features_table where feature_id='$feature_id'");
		$check_free_email_access_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings WHERE setting_name = 'free_email_access'");
		$check_free_email_access_mode->setting_status;
		$check_force_profile_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings WHERE setting_name = 'force_profile'");
		$check_force_profile_mode->setting_status;
		$user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id");
		$user_profile = $wpdb->get_row("SELECT status_id FROM $dsp_user_profiles WHERE user_id=$user_id");
		$status_id = $user_profile->status_id;
		$freeTrialGender = dsp_get_setting_free_mode_gender($free_trail_gender);
		if ($premium_access_features > 0 && ($freeTrialGender != $user_gender) && ($free_trail_gender != 3)) {
			$check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
			if ($check_member_payment > 0) {
				$check_account_expire = $wpdb->get_row("SELECT * FROM $dsp_payments_table where pay_user_id='$user_id'");
				$start_date = $check_account_expire->start_date;

				$payment_status = $check_account_expire->payment_status;

				$expiration_date = $check_account_expire->expiration_date;

				$pay_plan_days = $check_account_expire->pay_plan_days;

				$current_date = date("Y-m-d");

				//$cal_days = daysDifference($current_date, $start_date);
				$cal_days = daysDifference($expiration_date, $current_date);

				//if ($cal_days > $pay_plan_days) {
				if ($cal_days <= 0) {
					if ($payment_status == '1') {

						$wpdb->query("UPDATE $dsp_payments_table SET payment_status=2 WHERE pay_user_id = '$check_account_expire->pay_user_id'");
					}

					$msg = "Expired";
				} else {

					$msg = "Access";
				} // End if($cal_expire_date>=$expiration_date)
			} else {

				$msg = "Onlypremiumaccess";
			} // End if($check_member_payment>0) 

		} else if ($premium_access_features == 0 || ($freeTrialGender == $user_gender)  || ($free_trail_gender == 3)){

			$check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id' AND DATEDIFF(`expiration_date`, NOW()) > 0");

			if ($check_member_payment > 0 && $isUnderPremiumPlan ) {

				$msg = "Access";
			} else {
				$rows = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table ",ARRAY_A);
				/*$memberships_feature_row = mysql_query("SELECT premium_access_feature FROM $dsp_memberships_table ");*/
				//while ($row = mysql_fetch_array($memberships_feature_row)) {
				if (count($rows)) {
					foreach ($rows as $row) {
						$premium_access_feature = $row['premium_access_feature'];
						if (!empty($premium_access_feature))
							$access_feature_id = explode(",", $premium_access_feature);
						else
							$access_feature_id = 0;
						for ($i = 0; $i < count($access_feature_id); ++$i) {
							$access_feature_id[$i];
							$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
							$access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table");
							foreach ($access_feature_row as $access_feature) {
								$feature_id = $access_feature->feature_id;
								//echo $access_feature_id[$i]."-----------".$feature_id."<br>";
								if ($access_feature_id[$i] == $feature_id)
									$name = $access_feature_id[$i];
							}
							//echo $name;
							//echo "SELECT * FROM $dsp_features_table where feature_id=$name";
							$a = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_id=$name");
							$feature_name = $a->feature_name;
							if (isset($feature_name) && $feature_name == $access_feature_name)
								$name1 = $feature_name;
						}
					}
				}
				//echo $name1; 

				if ((@$name1 == '' && ($no_of_credits >= $giftSettingValue && $creditMode->setting_status == 'Y')) || (@$name1 == '' && $check_member_payment > 0) ) {
					$msg = "Access";
				} else if (($free_trail_gender == 1) && ($user_gender == 'M')) {
					if ($days <= $free_trail_days) {
						$msg = "Access";
					} else {//Expired	
						$msg = "Expired";
					}
				} else if (($free_trail_gender == 2) && ($user_gender == 'F')) {
					if ($days <= $free_trail_days) {
						$msg = "Access";
					} else {//Expired	
						$msg = "Expired";
					}
				} else if (($free_trail_gender == 3)) {
					if ($days <= $free_trail_days) {
						$msg = "Access";
					} else {//Expired	
						$msg = "Expired";
					}
				} else if ($user_profile_exist == 0) {

					$msg = "NotExist";
				} else if ($status_id == 0) {

					$msg = "Approved";
				} else {

					$msg = "Onlypremiumaccess";
				}
			}
		} else {
			$msg = "NoAccess";
		}
		return $msg;
	}
}

// ------------------End function to check free trail mode  -----------------------//
// ------------------End function to check free force profile mode  -----------------------//

if (!function_exists('check_free_force_profile_feature')) {

	function check_free_force_profile_feature($user_id) {
		global $wpdb;
		$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
		$general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='force_profile'");
		$force_profile = $general_settings->setting_status;
		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
		$user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id and seeking!=''");
		$user_profile = $wpdb->get_var("SELECT status_id FROM $dsp_user_profiles WHERE user_id=$user_id");
		$status_id = $user_profile;
		if( $_SESSION['free_member']){ // check free_mode_member match or not with current user gender
			$msg = "Access";
		}else{
			if ($user_profile_exist == 0) {
				$msg = "NoAccess";
			} else if ($status_id == 0) {
				$msg = "Approved";
			} else {
				$msg = "Access";
			}
		}	
		return $msg;
	}

}

// ------------------End function to check free force profile mode  -----------------------//
// ------------------End function to check force profile mode  -----------------------//

if (!function_exists('check_force_profile_feature')) {

	function check_force_profile_feature($access_feature_name, $user_id) {
		global $wpdb;
		$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
		$general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='force_profile'");
		$force_profile = $general_settings->setting_status;
		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
		$user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id AND country_id!=0");
		$user_profile = $wpdb->get_row("SELECT status_id FROM $dsp_user_profiles WHERE user_id=$user_id");
		$status_id = $user_profile->status_id;
		$dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
		$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
		$features_list_id = $wpdb->get_var("SELECT pay_plan_id 	FROM $dsp_payments_table where pay_user_id=$user_id");
		$pay_plan_id = $features_list_id;
		$isUnderPremiumPlan = false;
		$creditMode = apply_filters('dsp_get_general_setting_value','credit');
		$no_of_credits = dsp_get_credits_of_user($user_id);
		$giftSettingValue = dsp_get_credit_setting_value('emails_per_credit');
		$memberships_feature_row = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table where membership_id='" . $pay_plan_id . "'");

		foreach ($memberships_feature_row as $membership_feature)
			$premium_access_feature = $membership_feature->premium_access_feature;

		if (!empty($premium_access_feature))
			$access_feature_id = explode(",", $premium_access_feature);
		else
			$access_feature_id = array('0');

		for ($i = 0; $i < count($access_feature_id); ++$i) {

			$access_feature_id[$i];

			$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;

			$access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table where feature_id=" . $access_feature_id[$i]);

			foreach ($access_feature_row as $access_feature)
				$feature_name = $access_feature->feature_name;

			if (isset($feature_name) && $feature_name == $access_feature_name){
				$isUnderPremiumPlan = true;
				$name = $feature_name;
			}
		}

		$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;

		$dsp_premium_access_feature_table = $wpdb->prefix . DSP_PREMIUM_ACCESS_FEATURE_TABLE;

		if (isset($name)) {

			$features_list_id = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_name='$name'");

			$feature_id = $features_list_id->feature_id;
		} else {

			$feature_id = 0;
		}

		$premium_access_features = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_features_table where feature_id='$feature_id'");



		if ($user_profile_exist == 0) {

			$msg = "NoAccess";
		} else if ($status_id == 0) {

			$msg = "Approved";
		} elseif ($premium_access_features > 0) {

			$check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");

			if ($check_member_payment > 0) {

				$check_account_expire = $wpdb->get_row("SELECT * FROM $dsp_payments_table where pay_user_id='$user_id'");

				$start_date = $check_account_expire->start_date;

				$payment_status = $check_account_expire->payment_status;

				$expiration_date = $check_account_expire->expiration_date;

				$pay_plan_days = $check_account_expire->pay_plan_days;

				$current_date = date("Y-m-d");

				//$cal_days = daysDifference($current_date, $start_date);
				$cal_days = daysDifference($expiration_date, $current_date);
				//if ($cal_days > $pay_plan_days) {
				if ($cal_days <= 0) {
					if ($payment_status == '1') {

						$wpdb->query("UPDATE $dsp_payments_table SET payment_status=2 WHERE pay_user_id = '$check_account_expire->pay_user_id'");
					}

					$msg = "Expired";
				} else {

					$msg = "Access";
				} // End if($cal_expire_date>=$expiration_date)
			} else {

				$msg = "Onlypremiumaccess";
			} // End if($check_member_payment>0)
		} else if ($premium_access_features == 0) {

			$check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id' AND DATEDIFF(`expiration_date`, NOW()) > 0");

			if ($check_member_payment > 0 && $isUnderPremiumPlan) {

				$msg = "Access";
			} else {


				$rows = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table ",ARRAY_A);
				
				foreach ($rows as $row) {
					$premium_access_feature = $row['premium_access_feature'];
					if (!empty($premium_access_feature))
						$access_feature_id = explode(",", $premium_access_feature);
					else
						$access_feature_id = 0;

					for ($i = 0; $i < count($access_feature_id); ++$i) {

						$access_feature_id[$i];



						$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;



						$access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table");

						foreach ($access_feature_row as $access_feature) {

							$feature_id = $access_feature->feature_id;

							//echo $access_feature_id[$i]."-----------".$feature_id."<br>";

							if ($access_feature_id[$i] == $feature_id)
								$name = $access_feature_id[$i];
						}

						//echo $name;
						//echo "SELECT * FROM $dsp_features_table where feature_id=$name";

						$a = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_id=$name");

						$feature_name = $a->feature_name;

						if (isset($feature_name) && $feature_name == $access_feature_name)
							$name1 = $feature_name;
					}
				}

				//echo $name1; 
				if (
					(@$name1 == '' &&  $creditMode->setting_status == 'N') || 
					(@$name1 == '' && ($no_of_credits >= $giftSettingValue && $creditMode->setting_status == 'Y')) || 
					(@$name1 == '' && $check_member_payment > 0) 
				) {
					$msg = "Access";
				} else {
					$msg = "Onlypremiumaccess";
				}
			}
		} else {

			$msg = "Access";
		}
		return $msg;
	}

}

// ------------------End function to check force profile mode  -----------------------//
// ------------------function to Approved profile mode  -----------------------//

if (!function_exists('check_approved_profile_feature')) {

	function check_approved_profile_feature($user_id) {

		global $wpdb;



		// ----------------------------------------------- check prfile is approved or not------------------------------------------------------ // 

		$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

		$profile_status = $wpdb->get_row("SELECT * FROM $dsp_user_profiles_table WHERE user_id = '$user_id'");

		$pstatus = $profile_status->status_id;



		$count_user_profile = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles_table WHERE user_id='$user_id'");

		/* if($count_user_profile==0) 

		  {

		  $msg= "NoExist";

		  }else */ if ($pstatus == 0) {

			$msg = "NoAccess";
		} else {

			$msg = "Access";
		}

		return $msg;
	}

}

// ------------------function to check free email access mode  -----------------------//

if (!function_exists('check_free_email_feature')) {

	function check_free_email_feature($access_feature_name, $user_id) {
		global $wpdb;
		$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
		$general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_email_access_gender'");
		$free_email_access_gender = $general_settings->setting_value;
		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
		$gender_field = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles where user_id=$user_id");
		$user_gender = $gender_field->gender;
		$dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
		$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
		$isUnderPremiumPlan = false;
		$creditMode = apply_filters('dsp_get_general_setting_value','credit');
		$no_of_credits = dsp_get_credits_of_user($user_id);
		$giftSettingValue = dsp_get_credit_setting_value('emails_per_credit');
		$features_list_id = $wpdb->get_var("SELECT pay_plan_id 	FROM $dsp_payments_table where pay_user_id=$user_id");
		$pay_plan_id = $features_list_id;
		$memberships_feature_row = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table where membership_id='" . $pay_plan_id . "'");
		foreach ($memberships_feature_row as $membership_feature)
			$premium_access_feature = $membership_feature->premium_access_feature;

		if (!empty($premium_access_feature))
			$access_feature_id = explode(",", $premium_access_feature);
		else
			$access_feature_id = array('0');

		for ($i = 0; $i < count($access_feature_id); ++$i) {

			$access_feature_id[$i];

			$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;

			$access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table where feature_id=" . $access_feature_id[$i]);

			foreach ($access_feature_row as $access_feature)
				$feature_name = $access_feature->feature_name;

			if (isset($feature_name) && $feature_name == $access_feature_name){
				$isUnderPremiumPlan = true;
				$name = $feature_name;
			}
		}

		$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;

		$dsp_premium_access_feature_table = $wpdb->prefix . DSP_PREMIUM_ACCESS_FEATURE_TABLE;

		if (isset($name)) {

			$features_list_id = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_name='$name'");

			$feature_id = $features_list_id->feature_id;
		} else {

			$feature_id = 0;
		} 
		$premium_access_features = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_features_table where feature_id='$feature_id'");
		if (($free_email_access_gender == 1) && ($user_gender == 'M')) {
			$msg = "Access";
		} else if (($free_email_access_gender == 2) && ($user_gender == 'F')) {
			$msg = "Access";
		} else if(($free_email_access_gender == 3 ) && ($user_gender == 'M' || $user_gender =='F')){
            $msg = "Access";
        } else if ($premium_access_features > 0) {
			$check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
			if ($check_member_payment > 0 ) {

				$check_account_expire = $wpdb->get_row("SELECT * FROM $dsp_payments_table where pay_user_id='$user_id'");

				$start_date = $check_account_expire->start_date;

				$payment_status = $check_account_expire->payment_status;

				$expiration_date = $check_account_expire->expiration_date;

				$pay_plan_days = $check_account_expire->pay_plan_days;

				$current_date = date("Y-m-d");

				//$cal_days = daysDifference($current_date, $start_date);
				$cal_days = daysDifference($expiration_date, $current_date);

				//if ($cal_days > $pay_plan_days) {
				if ($cal_days <= 0) {
					if ($payment_status == '1') {

						$wpdb->query("UPDATE $dsp_payments_table SET payment_status=2 WHERE pay_user_id = '$check_account_expire->pay_user_id'");
					}

					$msg = "Expired";
				} else {

					$msg = "Access";
				} // End if($cal_expire_date>=$expiration_date)
			} else {

				$msg = "Onlypremiumaccess";
			} // End if($check_member_payment>0)
		} else if ($premium_access_features == 0) {

			$check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id' AND DATEDIFF(`expiration_date`, NOW()) > 0");

			if ($check_member_payment > 0 && $isUnderPremiumPlan) {

				$msg = "Access";
			} else {
				$rows = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table ",ARRAY_A);
				foreach ($rows as $row) {

					$premium_access_feature = $row['premium_access_feature'];

					if (!empty($premium_access_feature))
						$access_feature_id = explode(",", $premium_access_feature);
					else
						$access_feature_id = 0;

					for ($i = 0; $i < count($access_feature_id); ++$i) {

						$access_feature_id[$i];



						$dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;



						$access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table");

						foreach ($access_feature_row as $access_feature) {

							$feature_id = $access_feature->feature_id;

							//echo $access_feature_id[$i]."-----------".$feature_id."<br>";

							if ($access_feature_id[$i] == $feature_id)
								$name = $access_feature_id[$i];
						}

						//echo $name;
						//echo "SELECT * FROM $dsp_features_table where feature_id=$name";

						$a = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_id=$name");

						$feature_name = $a->feature_name;

						if (isset($feature_name) && $feature_name == $access_feature_name)
							$name1 = $feature_name;
					}
				}
				//echo $name1; 
				
				if (
					(@$name1 == '' &&  $creditMode->setting_status == 'N') || 
					(@$name1 == '' && ($no_of_credits >= $giftSettingValue && $creditMode->setting_status == 'Y')) || 
					(@$name1 == '' && $check_member_payment > 0) 
				) 
				{
					$msg = "Access";
				} else 
				{
					$msg = "Onlypremiumaccess";
				}
			}
		} else {

			$msg = "NoAccess";
		}

		return $msg;
	}

}

if (!function_exists('display_members_photo_no_generic')) {

	function display_members_photo_no_generic($photo_member_id, $path) {

		global $wpdb;

		$dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;

		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

		$count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE user_id='$photo_member_id' AND status_id=1");
		if ($count_member_images > 0) {

			$member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '$photo_member_id' AND status_id=1");
			if ($member_exist_picture->picture == "") {

				$Mem_Image_path = "";
			} else {

				$Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/" . $member_exist_picture->picture;
				$physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/" . $member_exist_picture->picture;
				$Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);
				if (file_exists($physical_image_path)) {

					$Mem_Image_path = $Mem_Image_path;
				} else {

					$Mem_Image_path = "";
				}
			}
		} else {

			$Mem_Image_path = "";
		}

		return $Mem_Image_path;
	}

}

// Check if user is blocked
if (!function_exists('dsp_is_user_blocked')) {
	function dsp_is_user_blocked($user_id,$member_id)
	{
		global $wpdb;
		$check=$wpdb->get_results("SELECT * FROM ". $wpdb->prefix.DSP_BLOCKED_MEMBERS_TABLE." WHERE user_id=$member_id AND block_member_id=$user_id");
		if( count($check) > 0 )
		{  
			echo '<script>window.location="' .  site_url().'/members' . '";</script>';
		}
	}
}

/**
 * This function is used to get setting from dsp plugin
 */

if(!function_exists('get_facebook_login_setting')){
    function get_facebook_login_setting($condition,$column = 'setting_value'){
        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . "dsp_general_settings";
        $facebookSettingStatus = $wpdb->get_var($wpdb->prepare("SELECT `".$column."` FROM $dsp_general_settings_table WHERE setting_name = '%s'",$condition));
        return $facebookSettingStatus;
    }
}

/**
 * This function is used to get setting from dsp plugin
 */

if(!function_exists('get_facebook_login_setting')){
    function get_facebook_login_setting($condition,$column = 'setting_value'){
        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . "dsp_general_settings";
        $facebookSettingStatus = $wpdb->get_var($wpdb->prepare("SELECT `".$column."` FROM $dsp_general_settings_table WHERE setting_name = '%s'",$condition));
        return $facebookSettingStatus;
    }
}

/*
=================================================
Search Form Class Add
=================================================
*/

include(dirname(__FILE__).'/class/class_search_form_normal.php');
