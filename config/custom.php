<?php
return [
  'custom' => [
    'mainLayoutType' => 'vertical', // Options[String]: vertical(default), horizontal
    'theme' => 'light', // options[String]: 'light'(default), 'dark', 'bordered', 'semi-dark'
    'sidebarCollapsed' => false, // options[Boolean]: true, false(default) (warning:this option only applies to the vertical theme.)
    'navbarColor' => '', // options[String]: bg-primary, bg-info, bg-warning, bg-success, bg-danger, bg-dark (default: '' for #fff)
    'horizontalMenuType' => 'floating', // options[String]: floating(default) / static /sticky (Warning:this option only applies to the Horizontal theme.)
    'verticalMenuNavbarType' => 'floating', // options[String]: floating(default) / static / sticky / hidden (Warning:this option only applies to the vertical theme)
    'footerType' => 'static', // options[String]: static(default) / sticky / hidden
    'layoutWidth' => 'full', // options[String]: full(default) / boxed,
    'showMenu' => true, // options[Boolean]: true(default), false //show / hide main menu (Warning: if set to false it will hide the main menu)
    'bodyClass' => '', // add custom class
    'pageHeader' => false, // options[Boolean]: true(default), false (Page Header for Breadcrumbs)
    'contentLayout' => 'default', // options[String]: default, content-left-sidebar, content-right-sidebar, content-detached-left-sidebar, content-detached-right-sidebar (warning:use this option if your whole project with sidenav Otherwise override this option as page level )
    'defaultLanguage' => 'en',    //en(default)/de/pt/fr here are four optional language provided in theme
    'blankPage' => false, // options[Boolean]: true, false(default) (warning:only make true if your whole project without navabr and sidebar otherwise override option page wise)
    'direction' => env('MIX_CONTENT_DIRECTION', 'ltr'), // Options[String]: ltr(default), rtl
    'apipath' => 'http://web.smsgw.in/smsapi/httpapi.jsp?',
    'username' => 'IRBHARAT03',
    'otpvalidtime' => '5',
    'password' => 'DAN@10Cr2024',
    'sender_id' => 'INBART',
    'PE_ID' => '1001873533044526874',
    'template_id' => '1007252692316482946',
    'facebook_id' => 'https://www.facebook.com/inrbharathelp',
    'youtube_id' => 'https://youtube.com/@INRBharat',
    'twitter_id' => 'https://twitter.com/inr_bharat/',
    'instagram_id' => 'https://www.instagram.com/inrbharathelp/',
    'whatsapp_id_old' => 'https://api.whatsapp.com/send?phone=919975702645',
    'whatsapp_id' => 'https://whatsapp.com/channel/0029VaG7rJT1noz3l7OLis2P',
    'telegram_id' => 'https://t.me/INR_Bharat/',
    'plan_invest_amount' => 2000,
    'pin_amount' => 100,
    'upi_pay_amount' => 2000,
    'plan_income_amt' => 2000,
    'admin_income' => 15,
    'leader_income' => 10,
    'level_1'	=> 10,
    'level_2'	=> 7,
    'level_3'	=> 5,
    'level_4'	=> 7,
    'level_5'	=> 10,
    'level_6'	=> 7,
    'level_7'	=> 10,
    'user_id_limit'=>10,
    'telegram_bot_name'=>'INRBAdmin@23',
    'telegram_bot_join'=>'https://t.me/inrbadmin23_bot',
    'telegram_bot_token'=>'6400971457:AAExvpR8uacc1ytXwlVkhcgoovR11W1Y1ZE',
    'telegram_bot_API'=>'https://api.telegram.org/bot6400971457:AAExvpR8uacc1ytXwlVkhcgoovR11W1Y1ZE',
    'telegram_bot_user_name'=>'inrbadmin23_bot',
    // // 'razor_key'=>'rzp_live_o49lKN7oSl3P7P',
    // // 'razor_secret'=>'85AME1R6E4Ktkhsiji2EGOCC', // Old Key
    // 'razor_key'=>'rzp_live_k6raQAGtfIhPCJ',
    // 'razor_secret'=>'1AzTfgfDLuUNT5QN7l3sJvv2',
    'cashfree_key'=>'144598aeb9177623a0679abcdd895441',
    'cashfree_secret'=>'f7f7e355e51d5c39cf1bd824153a63c025156473',
    'withdraw_money_rpin_price'=>100,
  ]
];

/* Do changes in this file if you know what it effects to your template. For more infomation refer the <a href="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template//documentation/documentation-laravel.html"> documentation </a> */
