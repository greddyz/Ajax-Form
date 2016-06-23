<?php
    $settings = array(
        'mail_to'           => 'greddyz@mail.ru',
        'mail_from'         => '', // for mail() is empty. They will apply the server settings. Otherwise possible DMARC policy mail is spam or use SMPT authentication.
        'name_from'         => '', // for mail() is empty. They will apply the server settings. Otherwise possible DMARC policy mail is spam or use SMPT authentication.
        'subject'           => 'Письмо с сайта',
        'email_required'    => true, // email is required
        'antispam_name'     => 'antsm', // name of hidden antispam input
        // SMTP
        'is_smtp'           => false, // use SMTP
        'smtp_host'         => '', // SMTP servers
        'smtp_auth'         => '', // true/false
        'smtp_user'         => '',
        'smtp_password'     => '',
        'smtp_secure'       => '', // ssl/tls
        'smtp_port'         =>   ''
    );

    $msg = array(
        // ui messages
        'antispam'          => 'Вы не прошли антиспам проверку!',
        'mail'              => 'Укажите корректный email адрес!',
        'success'           => 'Сообщение отправлено!',
        'error'             => 'Ошибка отправки! Повторите попытку позже.',
        // debug mode console messages
        'debug_success_send'=> 'Message sent successfully',
        'debug_err_send'    => 'Error send a message',
        'debug_no_tpl'      => 'Tpl file not found',
        'debug_no_email'    => 'E-mail unknown',
        'debug_antispam'    => 'Antispam check is not passed'
    );
?>