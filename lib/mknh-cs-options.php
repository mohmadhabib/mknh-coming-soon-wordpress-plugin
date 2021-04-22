<?php

add_action('admin_init', 'mknh_cs_general_options_init');
add_action('admin_init', 'mknh_cs_content_options_init');
add_action('admin_init', 'mknh_cs_design_options_init');
add_action('admin_init', 'mknh_cs_mailing_options_init');
add_action('admin_menu', 'mknh_cs_admin_add');
add_action('admin_enqueue_scripts', 'mknh_cs_stylesheet_admin');

function mknh_cs_general_options_init()
{
    register_setting('mknh_cs_general', 'mknh_cs_options_settings', 'mknh_cs_general_options_validate');

}

function mknh_cs_content_options_init()
{
    register_setting('mknh_cs_set', 'mknh_cs_options', 'mknh_cs_content_options_validate');

}

function mknh_cs_design_options_init()
{
    register_setting('mknh_cs_design', 'mknh_cs_design_options', 'mknh_cs_design_options_validate');

}

function mknh_cs_mailing_options_init()
{
    register_setting('mknh_cs_mail_list', 'mknh_cs_mailing_options', 'mknh_cs_mailing_options_validate');

}

/**
 * Add stylesheet to the admin page
 */
function mknh_cs_stylesheet_admin()
{
    wp_enqueue_media();
    wp_enqueue_style('wp-color-picker');

    wp_enqueue_style('prefix-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_script('prefix-style', plugins_url('js/scripts-admin.js', __FILE__));
    //  wp_enqueue_script( 'prefix-style', plugins_url('js/jquery.js', __FILE__) );
}

/**
 * Load up the menu page
 */
function mknh_cs_admin_add()
{
    add_theme_page(__('MKNH Soon Options', 'soon'), __('MKNH Coming Soon', 'soon'), 'edit_mknh_cs_options', 'mknh_cs_options', '');
}

/**
 * Create arrays for our select and radio options
 */
$select_options = array(
    '0' => array(
        'value' => 'color',
        'label' => __('Background Color', 'soon')
    ),
    '1' => array(
        'value' => 'image',
        'label' => __('Background Image', 'soon')
    )
);

$radio_options = array(
    'enabled' => array(
        'value' => 'enabled',
        'label' => __('Enabled', 'soon')
    ),
    'disabled' => array(
        'value' => 'disabled',
        'label' => __('Disabled', 'soon')
    )
);

/**
 * Create the options page
 */
function mknh_cs_admin_settings()
{
    global $select_options, $radio_options;

    if (!isset($_REQUEST['settings-updated']))
        $_REQUEST['settings-updated'] = false;

    ?>
    <div class="wrap">

        <h2 class="wpmm-title"><?php _e('MKNH Coming Soon Page', 'soon'); ?></h2>

        <?php if (false !== $_REQUEST['settings-updated']) : ?>
            <div class="updated fade"><p><strong><?php _e('Options saved', 'mknh-cs'); ?></strong></p></div>
        <?php endif; ?>

        <div class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active" href="#general"><?php _e('General Settings', 'mknh-cs'); ?></a>
            <a class="nav-tab" href="#content"><?php _e('Page Content', 'mknh-cs'); ?></a>
            <a class="nav-tab" href="#design"><?php _e('Design Page', 'mknh-cs'); ?></a>
            <a class="nav-tab" href="#maillist"><?php _e('Mail List Options', 'mknh-cs'); ?></a>
        </div>

        <div class="wpmm-wrapper">
            <div id="content" class="wrapper-cell">
                <div class="tabs-content">
                    <div id="tab-general" class="">
                        <form method="post" id="genralform" action="options.php">
                            <?php settings_fields('mknh_cs_general'); ?>
                            <?php $options = get_option('mknh_cs_options_settings'); ?>

                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row"><?php _e('Mode', 'mknh-cs'); ?></th>
                                    <td>
                                        <fieldset>
                                            <legend class="screen-reader-text">
                                                <span><?php _e('Radio buttons', 'mknh-cs'); ?></span></legend>
                                            <?php
                                            if (!isset($checked))
                                                $checked = '';
                                            foreach ($radio_options as $option) {
                                                $radio_setting = $options['radioinput'];

                                                if ('' != $radio_setting) {
                                                    if ($options['radioinput'] == $option['value']) {
                                                        $checked = "checked=\"checked\"";
                                                    } else {
                                                        $checked = '';
                                                    }
                                                }
                                                ?>
                                                <label class="description"><input type="radio"
                                                                                  name="mknh_cs_options_settings[radioinput]"
                                                                                  value="<?php esc_attr_e($option['value']); ?>" <?php echo $checked; ?> /> <?php echo $option['label']; ?>
                                                </label><br/>
                                                <?php
                                            }
                                            ?>
                                        </fieldset>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Footer Credits', 'mknh-cs'); ?></th>
                                    <td>
                                        <input id="mknh_cs_options_settings[credits]"
                                               name="mknh_cs_options_settings[credits]" type="checkbox"
                                               value="1" <?php checked('1', $options['credits']); ?> />
                                        <label class="description"
                                               for="mknh_cs_options_settings[credits]"><?php _e('Support The Developer and Add the Footer Link', 'mknh-cs'); ?></label>
                                    </td>
                                </tr>


                            </table>
                            <p class="submit">
                                <input type="submit" name="mknh_cs_save" class="button-primary" value="<?php _e('Save Options', 'mknh-cs'); ?>"/>
                            </p>
                        </form>

                        <?php

                        echo "<img src='" . plugin_dir_url(__FILE__) . "images/loading.gif' class='mknh_cs_social_load'/>";

                        ?>

                        <script type="text/javascript">

                            jQuery(document).ready(function () {

                                jQuery(".mknh_cs_social_load").hide();

                                jQuery('#genralform').submit(function (e) {
                                    e.preventDefault();

                                    jQuery(".mknh_cs_social_load").show();

                                    jQuery(this).ajaxSubmit({


                                        success: function () {

                                            jQuery(".mknh_cs_social_load").hide();

                                            jQuery('#saveResult').html("<div id='saveMessage' class='successModal'></div>");

                                            jQuery('#saveMessage').append("<p><?php	echo htmlentities(__('Settings Saved Successfully', 'wp'), ENT_QUOTES);	?></p>").show();

                                        },

                                        timeout: 5000,
                                        error: function (data) {
                                            jQuery(".mknh_cs_social_load").hide();

                                            jQuery('#saveResult').html("<div id='saveMessage' class='successModal'></div>");

                                            jQuery('#saveMessage').append("<p><?php

					echo htmlentities(__('Settings Saved Successfully', 'wp'), ENT_QUOTES);

					?></p>").show();
                                        }
                                    });

                                    setTimeout("jQuery('#saveMessage').hide('slow');", 5000);

                                    return false;

                                });

                            });

                        </script>

                    </div>
                    <div id="tab-content" class="hidden">


                        <form method="post" action="options.php" id="contenttab">
                            <?php settings_fields('mknh-cs'); ?>
                            <?php $options = get_option('mknh_cs_options'); ?>

                            <table class="form-table">
                                <?php
                                /**
                                 * A sample text input option
                                 */
                                ?>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Page Title', 'mknh-cs'); ?></th>
                                    <td>
                                        <input id="mknh_cs_options[title]" class="regular-text" type="text"
                                               name="mknh_cs_options[title]" value="<?php if ($options['title']) {
                                            esc_attr_e($options['title']);
                                        } else {
                                            esc_attr_e('Coming mknh-cs');
                                        }; ?>"/>
                                        <label class="description"
                                               for="mknh_cs_options[title]"><?php _e('Add your Page Title Here', 'mknh-cs'); ?></label>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><?php _e('Page Heading', 'mknh-cs'); ?></th>
                                    <td>
                                        <input id="mknh_cs_options[heading]" class="regular-text" type="text"
                                               name="mknh_cs_options[heading]" value="<?php if ($options['heading']) {
                                            esc_attr_e($options['heading']);
                                        } else {
                                            esc_attr_e('Coming mknh-cs');
                                        }; ?>"/>
                                        <label class="description"
                                               for="mknh_cs_options[heading]"><?php _e('Add your Heading Here, The Hero Title', 'mknh-cs'); ?></label>
                                    </td>
                                </tr>


                                <?php
                                /**
                                 * A sample textarea option
                                 */
                                ?>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Description', 'mknh-cs'); ?></th>
                                    <td>

                                        <?php if ($options['abouttext']) {
                                        } else {
                                            $options['abouttext'] = 'Find the best Bootstrap 3 freebies and themes on the web.';
                                        } ?>

                                        <?php wp_editor($options['abouttext'], 'desired_id_of_textarea', $settings = array('textarea_name' => 'mknh_cs_options[abouttext]')); ?>


                                    </td>
                                </tr>
                            </table>

                            <p class="submit">
                                <input type="submit" name="mknh_cs_comingsoon_save" class="button-primary"
                                       value="<?php _e('Save Options', 'mknh-cs'); ?>"/>
                            </p>
                        </form>

                        <?php

                        echo "<img src='" . plugin_dir_url(__FILE__) . "images/loading.gif' class='mknh_cs_social_load'/>";

                        ?>

                        <script type="text/javascript">

                            jQuery(document).ready(function () {

                                jQuery(".mknh_cs_social_load").hide();

                                jQuery('#contenttab').submit(function (e) {
                                    e.preventDefault();

                                    jQuery(".mknh_cs_social_load").show();

                                    jQuery(this).ajaxSubmit({


                                        success: function () {

                                            jQuery(".mknh_cs_social_load").hide();

                                            jQuery('#saveResult').html("<div id='saveMessage' class='successModal'></div>");

                                            jQuery('#saveMessage').append("<p><?php

					echo htmlentities(__('Settings Saved Successfully', 'wp'), ENT_QUOTES);

					?></p>").show();

                                        },

                                        timeout: 5000,
                                        error: function (data) {
                                            jQuery(".mknh_cs_social_load").hide();

                                            jQuery('#saveResult').html("<div id='saveMessage' class='successModal'></div>");

                                            jQuery('#saveMessage').append("<p><?php

					echo htmlentities(__('Settings Saved Successfully', 'wp'), ENT_QUOTES);

					?></p>").show();
                                        }
                                    });

                                    setTimeout("jQuery('#saveMessage').hide('slow');", 5000);

                                    return false;

                                });

                            });

                        </script>


                    </div>
                    <div id="tab-design" class="hidden">

                        <form method="post" action="options.php">
                            <?php settings_fields('mknh_cs_design'); ?>
                            <?php $options = get_option('mknh_cs__design_options'); ?>

                            <table class="form-table">
                                <?php
                                /**
                                 * A sample select input option
                                 */
                                ?>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Background type', 'mknh-cs'); ?></th>
                                    <td>

                                        <select name="mknh_cs_design_options[bg]" id="bg_type">
                                            <?php
                                            $selected = $options['bg'];
                                            $p = '';
                                            $r = '';

                                            foreach ($select_options as $option) {
                                                $label = $option['label'];
                                                if ($selected == $option['value']) // Make default first in list
                                                    $p = "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr($option['value']) . "'>$label</option>";
                                                else
                                                    $r .= "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr($option['value']) . "'>$label</option>";
                                            }
                                            echo $p . $r;
                                            ?>
                                        </select>
                                        <label class="description"
                                               for="mknh_cs_options[bg]"><?php _e(' ', 'mknh-cs'); ?></label>
                                    </td>
                                </tr>

                                <tr valign="top" id="customcolor">
                                    <th scope="row"><?php _e('Background Color', 'mknh-cs'); ?></th>
                                    <td>
                                        <input type="text" name="mknh_cs_design_options[customcolor]"
                                               value="<?php esc_attr_e($options['customcolor']); ?>"
                                               class="color-picker"/>
                                    </td>
                                </tr>
                                <tr valign="top" id="bgimg" style="display:none;">
                                    <th scope="row"><?php _e('Background Image', 'mknh-cs'); ?></th>
                                    <td>

                                        <input type="text" name="mknh_cs_design_options[bgimg]"
                                               value="<?php esc_attr_e($options['bgimg']); ?>" id="image_url"
                                               class="regular-text">
                                        <input type="button" name="upload-btn" id="upload-btn" class="button-secondary"
                                               value="Upload Image">
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><?php _e('Social Media Share', 'mknh-cs'); ?></th>
                                    <td>
                                        <input id="mknh_cs_design_options[social] allowsocial"
                                               name="mknh_cs_design_options[social]"
                                               type="checkbox" value="1" <?php checked('1', $options['social']); ?> />
                                        <label class="description"
                                               for="mknh_cs_design_options[social]"><?php _e('Include Social Media Sharing', 'mknh-cs'); ?></label>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Facebook Share', 'mknh-cs'); ?></th>
                                    <td>
                                        <input id="mknh_cs_design_options[fb]" name="mknh_cs_design_options[fb]"
                                               type="checkbox" value="1" <?php checked('1', $options['fb']); ?> />
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Twitter Share', 'mknh-cs'); ?></th>
                                    <td>
                                        <input id="mknh_cs_design_options[tw]" name="mknh_cs_design_options[tw]"
                                               type="checkbox" value="1" <?php checked('1', $options['tw']); ?> />
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Mail Sharing', 'mknh-cs'); ?></th>
                                    <td>
                                        <input id="mknh_cs_design_options[email]" name="mknh_cs_design_options[email]"
                                               type="checkbox" value="1" <?php checked('1', $options['email']); ?> />
                                    </td>
                                </tr>
                            </table>

                            <p class="submit">
                                <input type="submit" name="mknh_cs_save" class="button-primary"
                                       value="<?php _e('Save Options', 'mknh-cs'); ?>"/>
                            </p>
                        </form>


                    </div>
                    <div id="tab-maillist" class="hidden">
                        <form method="post" action="options.php">
                            <?php settings_fields('mknh_cs_mail_list'); ?>
                            <?php $options = get_option('mknh_cs_mailing_options'); ?>

                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row"><?php _e('Enable Mail List Form', 'mknh-cs'); ?></th>
                                    <td>

                                        <legend class="screen-reader-text">
                                            <span><?php _e('Enable Mail List Form', 'mknh-cs'); ?></span></legend>
                                        <input id="mknh_cs_mailing_options[enable]"
                                               name="mknh_cs_mailing_options[enable]"
                                               type="checkbox" value="1" <?php checked('1', $options['enable']); ?> />
                                        <label class="description"
                                               for="mknh_cs_mailing_options[enable]"><?php _e('Check to enable the form in front-end', 'mknh-cs'); ?></label>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><?php _e('MailChimp API Key', 'mknh-cs'); ?></th>
                                    <td>

                                        <legend class="screen-reader-text">
                                            <span><?php _e('MailChimp API Key', 'mknh-cs'); ?></span></legend>
                                        <input id="mknh_cs_mailing_options[mailchimp_api]"
                                               name="mknh_cs_mailing_options[mailchimp_api]"
                                               type="text" value="<?php esc_attr_e($options['mailchimp_api']); ?>" />
                                        <label class="description"
                                               for="mknh_cs_mailing_options[enable]"><?php _e('Head over to MailChimp to get the API Key', 'mknh-cs'); ?></label>
                                    </td>
                                </tr>
                        <?php if($options['mailchimp_api']!='' && isset($options['mailchimp_api'])){

                            include_once("MailChimp.php");
                            $MailChimp = new MailChimp($options['mailchimp_api']);
                            $lists = $MailChimp->get('lists');
                            $lists = $lists['lists'];
                            ?>
                                <tr valign="top">
                                    <th scope="row"><?php _e('MailChimp List', 'mknh-cs'); ?></th>
                                    <td>

                                        <legend class="screen-reader-text">
                                            <span><?php _e('MailChimp List', 'mknh-cs'); ?></span></legend>
                                        <select name="mknh_cs_mailing_options[mclist]" id="mknh_cs_mailing_options[mclist]">
                                            <?php foreach($lists as $mclist){ ?>
                                                <option value="<?php echo $mclist['id']; ?>" <?php echo ($options['mclist']==$mclist['id'] ? 'selected':''); ?>><?php echo $mclist['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <label class="description"
                                               for="mknh_cs_mailing_options[mclist]"><?php _e('These are all the lists you have on MailChimp', 'mknh-cs'); ?></label>
                                    </td>
                                </tr>
    <?php } ?>


                            </table>

                            <p class="submit">
                                <input type="submit" name="mknh_cs_save" class="button-primary"
                                       value="<?php _e('Save Options', 'mknh-cs'); ?>"/>
                            </p>
                        </form>


                    </div>

                </div>
            </div>
        </div>
    <?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */


function mknh_cs_general_options_validate($input)
{
    global $select_options, $radio_options;
    return $input;
}


function mknh_cs_content_options_validate($input)
{
    global $select_options, $radio_options;

    // Say our textarea option must be safe text with the allowed tags for pos();
    $input['abouttext'] = $input['abouttext'];

    return $input;
}


function mknh_cs_design_options_validate($input)
{
    global $select_options, $radio_options;

    //$input['sometextarea'] = wp_filter_post_kses( $input['sometextarea'] );

    return $input;
}

function mknh_cs_mailing_options_validate($input){
    return $input;
}

// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/