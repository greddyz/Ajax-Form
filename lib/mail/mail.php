<?php
/**
 * todo:
 * валидация email с . в адресе
 * доп адрес для отправки reply
 *
 */
    // ajax request only
    if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die();

    require('config.php');
    require('class.template.php');
    require('../phpmailer/PHPMailerAutoload.php');



    parse_str($_POST['formData'], $form_data);

    $res = array();

    // check hidden antispam input
    if(!empty($form_data[$settings['antispam_name']])) {
        $res['error'] = $msg['antispam'];
        $res['debug'] = $msg['debug_antispam'];

        die(json_encode($res, JSON_UNESCAPED_UNICODE));
    }


    if($settings['email_required'] || !empty($form_data['email'])) {

        // check valid email
        if(!checkEmail($form_data['email'])) {
            $res['error'] = $msg['mail'];
            $res['debug'] = $msg['debug_no_email'];

            die(json_encode($res, JSON_UNESCAPED_UNICODE));
        }

    }


    // get mail template
    $parse = new parse_class;

    if($parse->get_tpl('template/mail.tpl') === false) {
        $res['error'] = $msg['error'];
        $res['debug'] = $msg['debug_no_tpl'];

        die(json_encode($res, JSON_UNESCAPED_UNICODE));
    }

    $parse->set_tpl('{SUBJECT}', $settings['subject']);

    // parse all form values at template file
    foreach ($form_data as $key => $value) {
        if($key != $settings['antispam_name']) {

            $parse->set_tpl( '{'.strtoupper($key).'}',  checkPostVarText($value));
        }
    }

    $parse->tpl_parse();

    $body = $parse->template;

    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    $mail->CharSet = "UTF-8";
    $mail->isHTML(true);
    $mail->addAddress($settings['mail_to']);
    $mail->setFrom($settings['mail_from'], $settings['name_from']);
    $mail->Subject = $settings['subject'];
    $mail->Body = $body;


    if($settings['is_smtp']) {
        //$mail->SMTPDebug = 3; // Enable verbose debug output
        $mail->isSMTP();
        $mail->Host = $settings['smtp_host'];
        $mail->SMTPAuth = $settings['smtp_auth'];
        $mail->Username = $settings['smtp_user'];
        $mail->Password = $settings['smtp_password'];
        $mail->SMTPSecure = $settings['smtp_secure'];
        $mail->Port = $settings['smtp_port'];
    }


    //send the message
    if(!$mail->send()) {
        $res['error'] = $msg['error'];
        $res['debug'] = $mail->ErrorInfo;

        die(json_encode($res, JSON_UNESCAPED_UNICODE));
    } else {
        $res['success'] = $msg['success'];
        $res['debug'] = $msg['debug_success_send'];

        die(json_encode($res, JSON_UNESCAPED_UNICODE));
    }



    function checkEmail($email) {
        if(!preg_match("/^[a-z0-9_-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|"."edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-"."9]{1,3}\.[0-9]{1,3})$/is", trim($email))) return false;
        else return true;
    }

    function checkPostVarText($post_var) {
        if(isset($post_var)) {
            $post_var = strip_tags($post_var);
            $post_var = htmlentities($post_var, ENT_QUOTES, "UTF-8");
            $post_var = htmlspecialchars($post_var, ENT_QUOTES);
            $post_var = trim($post_var);
            return $post_var;
        }
    }


    die();
?>